<?php 

// Configure here timeouts / concurrent connections for jobs/HTTP-requests 

$max_simultaneous_connections = 10;        // concurrent http-GET connections 
$timeout_connection = 10;                            // seconds to wait for a connection (return code will be 0 if no success) 
$timeout_completion = 30;                            // seconds to wait for the request to complete (return code will be 0 if connected, but request timeouts) 

/****************************************************************************** 

    doJobs.php 

    Requires PHP >= 5.0, tested under Linux and Windows 

    This script is asynchronously executed from "JobServer.php" (on demand) 

    Expects: 

        A 'batch_id' number as an argument 

    Purpose: 

        Processes the provided batch_id of one or more jobs (URLs) 
        as a controlled queue of parallel http-GET requests using 'multi_curl' 
        (if PHP < 5.2.0 then fallback to single, blocking 'curl' requests) 

        Updates statuses of 'jobs' table for each request / job 
        Saves the response code for each request into jobs table, including 
        timestamps, elapsed times, etc. 

******************************************************************************* 

    Written by Juanga 2010 for a programming test 

    Ack's: 
        - class_RollingCurl.inc.php 
            - Authored by Josh Fraser 
            - From http://code.google.com/p/rolling-curl/ 
            - Maintained by Alexander Makarov 


******************************************************************************/ 


set_time_limit(0); 
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); 
ini_set('display_errors', '1'); 

require "include/cli.functions.inc.php"; 

(strnatcmp(phpversion(),'5.0') >= 0) ? cli_print("\nPHP 5 check OK...\n") : die("\n### ERROR: PHP 5 is required\n\n"); 

// DEBUG_MODE: Set 0 for no logging, 2 for logging to 'logs' folder 

define("DEBUG_MODE", 2); 
require "include/lib_debug.inc.php"; 

require "include/class_handleMYSQL.inc.php"; 
require "include/class_RollingCurl.inc.php"; 

// show banner 

cli_print("\ndoJobs.php - Written by Juanga 2010\n\n"); 
cli_print("Executes a jobs_batch as a controlled queue of parallel http-GET requests\n"); 
cli_print("-------------------------------------------------------------------------------\n"); 


// sanitize batch_id from argument (only numeric chars accepted) 

$batch_id = preg_replace("/[^.0-9]/","",$argv[1]); 

// exit if no argument or valid batch_id has been provided 

if ($argc < 2 || !$batch_id || !is_numeric($batch_id)) { 
    cli_print("A batch_id number must be provided as an argument\nExample: php doJobs.php 121"); 
    exit(1); 
} 

// read and eval the mysql conf file that can be 'included' from Perl too 
eval(file_get_contents("mysql.conf.txt")); 

// mysql connection: get $sql object 
include("include/mysql.inc.php"); 

cli_print("Connected to mySQL localhost server\n"); 

// ready to go, extract jobs that matches the provided batch_id 

cli_print("Retrieving jobs for batch_id: ".$batch_id."\n"); 

if (! $n_jobs = $sql->send("SELECT job_id, job_url FROM jobs WHERE job_batch_id='".$batch_id."'")) 
{ 
    cli_print("### ERROR: No jobs found."); 
    exit(1); 
} 

cli_print($n_jobs." jobs found.\n"); 

// create an associative array of id_job/URL 
foreach($sql->rows as $row) 
{ 
    $jobs[$row['job_id']] = $row['job_url']; 
} 

// create our response code collector 

$collector = new MyResponseCodeCollector( $max_simultaneous_connections, $timeout_connection, $timeout_completion ); 

cli_print("Processing jobs...\n"); 

// run the jobs until done 

$collector->run($jobs); 

cli_print("Done!\n"); 

// set this batch as finished updating 'time_finished' 

$sql->send("UPDATE jobs_batches SET time_finished=NOW() WHERE batch_id='".$batch_id."' LIMIT 1"); 

exit(0); 




class MyResponseCodeCollector 
{ 
    private $multicurl=null; 

    function __construct( $n_parallel_jobs, $connect_timeout, $timeout ) 
    { 
        // prepare a new 'multicurl' object that will make parallel http-GET requests 
        // set two callbacks, for 'execute' and 'done' 
      $this->multicurl = new RollingCurl(array($this, 'executeCallback'), array($this, 'doneCallback')); 

      // configure options to: 
      //         not retrieve the body (faster response, just retrieve header) 
      //        set connection timeouts 

      $this->multicurl->options = array(CURLOPT_HEADER => true, 
                                                                          CURLOPT_NOBODY => true, 
                                                                          CURLOPT_CONNECTTIMEOUT => $connect_timeout, 
                                                                          CURLOPT_TIMEOUT => $timeout 
                                                                          ); 

      $this->multicurl->window_size = $n_parallel_jobs; 
    } 

    function executeCallback($job_id) 
    { 
        cli_print("executeCallback ---> (job_id {$job_id})\n"); 
        if ($job_id) 
        { 
            global $sql; 

            $sql->send(" 
                                    UPDATE jobs 
                                    SET status='executing', 
                                            executed_at=NOW() 
                                    WHERE job_id='{$job_id}' 
                                    LIMIT 1 
                                 ", NoDieOnErrors); 
        } 
    } 

    function doneCallback($response, $info, $job_id) 
    { 
        cli_print("doneCallback ---> Received [{$info['http_code']}] response code in {$info['total_time']} secs. for job_id[{$job_id}] URL [{$info['url']}]\n"); 
        if ($info && is_array($info)) 
        { 
            /* 
                info[http_code][200] 
                info[total_time][0.891] 
            */ 

            ($info['http_code'] != 0) ? $new_status = 'done' : $new_status = 'timeout'; 

            global $sql; 

            $sql->send(" 
                                    UPDATE jobs 
                                    SET job_response_code='{$info['http_code']}', 
                                            elapsedTime='{$info['total_time']}', 
                                            status='{$new_status}' 
                                    WHERE job_id='{$job_id}' 
                                    LIMIT 1 
                                 ", NoDieOnErrors); 

            if (! $sql->n_affected) 
                cli_print("### ERROR: Could not update jobs table for job_id({$job_id})\n"); 

        } 
        //cli_print("\n---\n"); 
    } 

    function run($jobs) 
    { 
      foreach ($jobs as $id => $url) { 
        $request = new Request($url, $id); 
        $this->multicurl->add($request); 
      } 
      cli_print("MyResponseCodeCollector will run ".count($jobs)." jobs, max. ".$this->multicurl->window_size." simultaneous!\n"); 
      $this->multicurl->execute(); 
      cli_print("MyResponseCodeCollector finished!\n"); 
    } 

    function __destruct() 
    { 
        //$this->multicurl->__destruct(); 
        unset($this->multicurl); 
    } 
} 



?>

