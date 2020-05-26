<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>jQuery UI Sortable - Default functionality</title>

<script src="js/jquery-1.11.1.js"></script>
<script src="js/jquery-ui.js"></script>
<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    #sortable tr { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; cursor:pointer;}
    #sortable tr span { position: absolute; margin-left: -1.3em; }
</style>
<script>
var fixHelper = function(e, ui) {
	ui.children().each(function() {
		$(this).width($(this).width());
	});
	return ui;
};

$(function() {
$( "#sortable tbody" ).sortable({    
    helper: fixHelper
}).disableSelection();
});
</script>
</head>
<body>
<table id="sortable">
<tbody>
<tr class="ui-state-default"><td><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 1</td></tr>
<tr class="ui-state-default"><td><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 2</td></tr>
<tr class="ui-state-default"><td><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 3</td></tr>
<tr class="ui-state-default"><td><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 4</td></tr>
<tr class="ui-state-default"><td><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 5</td></tr>
<tr class="ui-state-default"><td><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 6</td></tr>
<tr class="ui-state-default"><td><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 7</td></tr>
</tbody>

</body>
</html>