<html>
<head>
<?php
include 'vsocheckinpie.php';
?>
</head>
<body style="background-color: #000000; margin: 0; padding: 0; font-family: Consolas">
<div style="width: 100%; height: 100px; background-color: #C00000; padding: 20px; font-size: 80px;">
	AVALON
</div>
<div style="width: 288px; height: 120px; background-color: white; margin: 20px; padding: 20px; border-color: white; border-radius: 10px 35px; float: left">
<?php
include 'vsocheckintable.php';
?>
</div>
<div style="width: auto; height: 120px; background-color: white; margin: 20px; padding: 20px; border-color: white; border-radius: 10px 35px; float: left">
<?php
include 'vsocheckinaverage.php';
?>
</div>
<div style="width 300px; height: 300px; background-color: white; margin: 20px; padding: 20px; border-color: white; border-radius: 10px 35px; float: left" id="chart_div"></div>
</body>
</html>
