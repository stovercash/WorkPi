<html>
<head>
<?php
include 'vsocheckinpie.php';
?>
</head>
<body style="background-color: #000000; margin: 0; padding: 0; font-family: Consolas">
<div style="width: 100%; height: 140px; margin: 0; padding: 0">
	<div style="width: auto; height: 100px; background-color: #C00000; padding: 20px; font-size: 80px; float: left">
		<span style="text-shadow: 0 0 10px #C0C0C0, 0 0 20px #C0C0C0, 0 0 50px #C0C0C0, 0 0 100px #C0C0C0;">
			AVALON
		</span>
	</div>
	<div style="width: 75px; height: 140px; background-color: #C00000; padding: 0; margin: 0; float: right; background-image: url('WorkPiBanner1.png')"></div>
	<div style="background-color: #C00000; height: 140px;"></div>
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
<div style="width: 160px; height: 160px; background-color: white; margin: 20px; border-color: white; border-radius: 10px 35px; float: left">
<div style="position: relative; top: -30px; left: -30px" id="chart_div"></div>
</div>
</body>
</html>
