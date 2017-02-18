<?php
$ytotal = 5;
$xtotal = 12;
$y = 1;
while($y <= $ytotal)
{
	$x = 1;
	while($x <= $xtotal)
	{
		echo '<div style="height: 20px; width: 20px; margin: 2px 2px 2px 2px; background-color: #ff0000; float: left; ';
		if ($x == 1)
		{
			echo 'clear: left';
		}
		echo '">&nbsp</div>';
		$x++;
	}
	$y++;
}
?>
