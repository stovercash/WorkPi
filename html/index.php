<html>
<head>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

google.charts.load('current', {'packages':['corechart','treemap']});

google.charts.setOnLoadCallback(drawChart);

function drawChart()
{
<?php
include 'vsocheckinpie.php';
include 'vsocheckinwordcloud.php';
include 'jobsopenjobhours.php';
?>
}
</script>
</head>
<body style="background-color: #000000; margin: 0; padding: 0; font-family: Consolas">
<div style="width: 100%; height: 140px; margin: 0; padding: 0">
	<div style="width: auto; height: 100px; background-color: #C00000; padding: 20px; font-size: 80px; float: left">
		<span style="text-shadow: 0 0 10px #000000;">
			AVALON
		</span>
	</div>
	<div style="width: 75px; height: 140px; background-color: #C00000; padding: 0; margin: 0; float: right; background-image: url('WorkPiBanner1.png')"></div>
	<div style="background-color: #C00000; height: 140px; overflow: hidden;">
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1010100010 &nbsp &nbsp &nbsp 
	&nbsp &nbsp &nbsp &nbsp &nbsp 1 &nbsp &nbsp 1 &nbsp 101 &nbsp &nbsp 01 &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 100000101 &nbsp 
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 01 &nbsp &nbsp &nbsp &nbsp &nbsp 1001 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 100000 1 &nbsp 1 &nbsp 
	&nbsp 010 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1010010001 &nbsp 10 &nbsp &nbsp &nbsp &nbsp 01 &nbsp &nbsp 001 &nbsp &nbsp &nbsp &nbsp &nbsp 
	&nbsp &nbsp 010 &nbsp &nbsp &nbsp &nbsp &nbsp 111001001 &nbsp &nbsp 1 &nbsp &nbsp &nbsp &nbsp 101 11 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
	&nbsp &nbsp &nbsp 1 &nbsp 11100101011100010 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 11 &nbsp &nbsp  1 &nbsp  01 &nbsp &nbsp &nbsp &nbsp &nbsp 
	&nbsp &nbsp &nbsp &nbsp 1 11011110 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1 &nbsp &nbsp &nbsp &nbsp &nbsp 010 &nbsp 1 &nbsp &nbsp &nbsp &nbsp &nbsp 
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 01 &nbsp 100 &nbsp &nbsp &nbsp &nbsp 111 &nbsp &nbsp &nbsp &nbsp 000 &nbsp &nbsp &nbsp 
	00011 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1001 &nbsp 1 &nbsp &nbsp &nbsp  01 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1011011 &nbsp &nbsp &nbsp &nbsp 
	&nbsp &nbsp &nbsp &nbsp 101 &nbsp &nbsp &nbsp 101 &nbsp &nbsp 110 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 00000001 &nbsp 
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 0 101011101110000101111000 &nbsp &nbsp 
	&nbsp &nbsp &nbsp 1 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 010 &nbsp &nbsp &nbsp &nbsp &nbsp 
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1100 &nbsp &nbsp 011 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 0 &nbsp &nbsp &nbsp &nbsp &nbsp 
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1100110 &nbsp 1 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 01110 
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 01 &nbsp 01 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1101000111 
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 010 &nbsp 0100 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1111 
	&nbsp &nbsp &nbsp 1110 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 11 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 111100011 &nbsp &nbsp &nbsp &nbsp 
	&nbsp &nbsp 11 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1 &nbsp 00111 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 000 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 00 &nbsp &nbsp 
	&nbsp &nbsp 1111000011011 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 01011 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
	00011 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1001 &nbsp 1 &nbsp &nbsp &nbsp  01 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1011011 &nbsp &nbsp &nbsp &nbsp 
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 1010100010 &nbsp &nbsp &nbsp 
	&nbsp &nbsp &nbsp &nbsp &nbsp 1010 &nbsp &nbsp &nbsp 10101 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 2
	</div>
</div>
<div style="width: 360px; height: 215px; background-color: white; margin: 20px; padding: 20px; border-color: white; border-radius: 10px 35px; float: left">
<?php
include 'vsocheckintable.php';
?>
</div>
<div style="width: auto; height: 215px; background-color: white; margin: 20px; padding: 20px; border-color: white; border-radius: 10px 35px; float: left">
<?php
include 'vsocheckinaverage.php';
?>
</div>
<div style="width: 255px; height: 255px; background-color: white; margin: 20px; border-color: white; border-radius: 10px 35px; float: left">
<div style="position: relative; top: -45px; left: -45px" id="chart_div"></div>
</div>
<?php
include 'jobsoverduebyuser.php';
?>
<div style="width: 420px; height: 215px; background-color: white; margin: 20px; border-color: white; border-radius: 10px 35px; padding: 20px; float: left">
<div id="chart_div_cloud" style="width: 100%; height: 100%;"></div>
</div>
<div style="width: 420px; height: 215px; background-color: white; margin: 20px; border-color: white; border-radius: 10px 35px; padding: 20px; float: left">
<div style="width: 100%; height: 20px; float: left; text-align: center; font-size: 20px"><span style="position: relative; top: -4px; color: LightGray">Open Job Hours</span></div>
<div id="chart_div_jobhours" style="width: 100%; height: auto; float: left; clear: left"></div>
</div>
</body>
</html>
