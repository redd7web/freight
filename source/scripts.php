<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/jquery.dropdown.js"></script>
<script type="text/javascript" src="js/hoverIntent.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="plugins/shadow/shadowbox.js"></script>
<script type="text/javascript" src="js/timepicker.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/Chart.js" ></script>
<script type="text/javascript">
Shadowbox.init({
    showOverlay:true,
    modal:false, 
    loadingImage:"shadow/loading.gif",
    displayNav: true,
    slideshowDelay: 2,        
    overlayOpacity: '0.9',
    overlayColor:"#FFFFFF",
    gallery: "gall" ,
        
});

var fixHelper = function(e, ui) {
	ui.children().each(function() {
		$(this).width($(this).width());
	});
	return ui;
};

$(function() {
	$( "#tabs" ).tabs();
    $( "#sortable tbody" ).sortable({    
            helper: fixHelper
     }).disableSelection();
    
});
</script>
