<?php if(isset($_SESSION['sludge_id'])){ ?>
<ul class="dropdown" style="margin-left: -24px;">
            <?php if($person->isFriendly() == false ){ ?>
                <li>Management
                    <ul class="sub_menu">
                        <li><a href="cost_calc.php" target="_blank">Job Cost Calculator</a></li>
                        <li><a href="management.php?task=overview">Dash</a>
                            <!-- <ul><li><a href="management.php?task=driverslog">Drivers Log</a></li></ul> --!>
                        </li>
                        <!--
                        <li><a>Tools</a><ul><li>
                         <a href="roi.php" target="_blank">ROI Calculator</a>
                        </li>
                        <li><a href="management.php?task=jobcost">Job Cost Calculator</a></li>
                        </ul></li>-->
                        <li><a href="management.php?task=reports">Reports</a>
                            <ul>
                            <li><a href="management.php?task=gpsin">( Inbound )</a></li>
                            <li><a href="management.php?task=gps">(Outbound )</a></li>
                            <?php if($person->isCreditManager()){
                                ?>
                                <!--<li><a href="management.php?task=creport">Credit Report</a></li>
                                <?php
                            } ?>
                            <li><a href="management.php?task=cancel">Account Cancellations</a></li>
                            <li><a href="management.php?task=expire">Account Expirations</a></li>
                            <li><a href="management.php?task=newloc">New Locations</a></li>--!>
                            <li><a href="management.php?task=forecast">Sludge Forecast</a>"</li>
                            
                            
                           <!--</a> <li><a href="management.php?task=ops">Oil Pickup Summary</a></li>
                            <li><a href="management.php?task=ocd">Oil Collection by Driver</a></li>
                            -->
                           <!--- <li><a href="management.php?task=freq">Pickup Frequency</a></li
                            <li><a href="management.php?task=zero">Zero-Gallon Pickups</a></li>
                            <li><a href="management.php?task=theft">Oil Theft</a></li>
                            <li><a href="management.php?task=delivery">Container Deliveries</a></li>
                            <li><a href="management.php?task=collected">Collected Code Reds</a></li>
                            <li><a href="management.php?task=csupport">Customer Support Activity</a></li>
                            >---!>
                            </ul>
                        </li>
                        <li><a href="management.php">Exports </a>
                            <ul>
                                <!--<li><a href="management.php?task=picknpay">Pickups &amp; Payments</a></li>
                                <li><a href="management.php?task=alloil">All Oil Collections</a></li>
                                <li><a href="management.php?task=oilperloc">Oil Collections Per Location</a></li>
                                <li><a href="management.php?task=affil">Affiliate Breakout Per Route</a></li>-->
                                 <li><a href="management.php?task=gpexp">Sludge Inbound</a></li>
                                 <li><a href="management.php?task=gpexpout">Sludge Outbound</a></li>
                            </ul>
                        </li>
                        <!--<li><a href="management.php?task=indices">Payment Indices</a></li>-->
                        
                        <li><a href="management.php?task=users">Users</a>
                            <ul>
                            <li><a href="management.php?task=staff">Staff</a></li>
                            <li><a href="management.php?task=adduser">Add User</a></li>
                            </ul>
                        </li>    
                        <li><a href="management.php?task=friendly">Friendly</a></li>                    
                        <li><a href="management.php?task=xlog">Transaction Log</a></li>
                        <!--
                        <li><a href="management.php?task=friendly">Friendly Comp.</a></li>--!>
                        <!--<li><a href="management.php?task=notes">Notes</a></li>
                         <li><a href="management.php?task=asset">Asset List</a></li>-->
                        <!-- 
                        <li><a href="management.php?task=containers">Containers</a>
                            <ul><li>Sub Containers</li></ul>
                        </li>-->
                        <li><a href="management.php?task=vehicles">Add Truck</a></li>
                        <li><a href="management.php?task=trailer">Add Trailer</a></li>
                    </ul>
                </li>
                <?php } ?>
                <li>Customers
                    <ul class="sub_menu">                        
                        <li><a href="customers.php?task=accounts">Accounts</a></li>
                        <?php if($person->isFriendly() == false ){ ?>
                            <li><a href="customers.php?task=newaccount">New Account</a></li>
                        <?php } ?>
                        <li>
                        <?php 
                             if(count($_SESSION['sludge_history'])>0){
                                echo "<a href='#'>History</a>";   
                             } else {
                                echo "History";
                             }
                        ?>
                        
                           <ul class="sub_menu">
                            <?php 
                            if(count($_SESSION['sludge_history'])>0){
                                foreach ($_SESSION['sludge_history'] as $places){
                                    echo "<li><a href='$places[url]'>$places[name]</a></li>";
                                }
                            } else {
                                echo "<li>empty</li>";
                            }
                            
                            ?>
                            
                            </ul>
                        </li>
                        <?php if($person->isFriendly() == false ){ ?>
                            <li><a href="customers.php?task=issues">Service Issues</a></li>
                            <li><a href="customers.php?task=tracker">Startup Tracker</a></li>
                            <li><a href="customers.php?task=services">Ending Service</a></li>
                        <?php } ?>                           
                    </ul>
                </li> 
                <li>Scheduling
                    <ul class="sub_menu">
                        <!--
                        <?php if(!$person->isFriendly() && !$person->isCoWest()){ ?>
                        <li><a href="scheduling.php?task=fgrid">Facility Grid</a></li>
                        <?php } ?>
                        
                        <?php if(!$person->isCoWest()){ ?>
                        <li><a href="scheduling.php">Oil Code Red</a>
                            <ul>
                                <li><a href="scheduling.php?task=oilcomplete">Completed</a></li>
                                <li><a href="scheduling.php?task=oilongoing">Ongoing</a></li>
                            </ul>
                        </li>
                        <?php } ?>
                        <?php if(!$person->isFriendly() && !$person->isCoWest()){ ?>
                        <li><a href="scheduling.php">Utility Code Red</a>
                            <ul>
                                <li><a href="scheduling.php?task=utilalarmcomplete">Completed</a></li>
                                <li><a href="scheduling.php?task=utilongoign">Ongoing</a></li>
                            </ul>
                        </li>-->
                        <?php } ?>
                        <?php if(!$person->isFriendly() && !$person->isCoWest() ){ ?>
                       <!-- <li><a href="scheduling.php?task=crequest">Container Requests</a></li>-->                    
                        <?php } 
                         ?>
                            <!--<li><a href="scheduling.php">Kitchen Jetting</a>
                                <ul>
                                    <li><a href="scheduling.php?task=suc">Scheduled Kitchen Call</a></li>
                                    <li><a href="scheduling.php?task=ruc">Routed Kitchen Call</a></li>
                                    <li><a href="scheduling.php?task=cuc">Completed Kitchen Call</a></li>
                                </ul>
                            </li>
                            --!>
                             <li><a href="scheduling.php">Confined Space</a>
                                <ul>
                                    <li><a href="scheduling.php?task=schoipu">Scheduled Space Pickups</a></li>                            
                                    <li><a href="scheduling.php?task=rop">Routed Space Pickups</a></li>
                                    <li><a href="scheduling.php?task=cop">Completed Space Pickups</a></li>
                                </ul>
                            </li>
                            <li><a href="scheduling.php">Sludges</a>
                                <ul>
                                     <li><a href="scheduling.php?task=sgt">Scheduled Sludge</a></li>
                                    <li><a href="scheduling.php?task=rgt">Routed Sludge</a></li>
                                    <li><a href="scheduling.php?task=cgt">Completed Sludge</a></li>
                                </ul>
                            </li>
                            <li><a href="scheduling.php">Mainline Jetting</a>
                                <ul>
                                    <li><a href="scheduling.php?task=suc">Scheduled Mainline</a></li>
                                    <li><a href="scheduling.php?task=ruc">Routed Mainline</a></li>
                                    <li><a href="scheduling.php?task=cuc">Completed Mainline</a></li>
                                </ul>
                            </li>
                            <?php if(!$person->isCoWest()){ ?>
                            <!--<li><a href="scheduling.php?task=shifts">Shifts</a></li>                    
                            <li><a href="scheduling.php?task=pickexp">Pickups Export</a></li>-->
                            <?php } ?>
                       
                    </ul>
                </li>    
                <li>
                    <a href="operator.php" target="_blank">Operator</a>
                </li>
                <!--<li>Sales
                    <ul class="sub_menu">
                        <li>My Leads</li>
                        <li>New Sales Lead</li>
                        <li>Sales Reps</li>
                        <li>Sales Lead Assignments</li>
                        <li>Sales Lead Clean Up</li>
                        <li>Report: Monthly Sales by Rep</li>
                    </ul>
                </li>-->                      
            </ul>
<?php } ?>