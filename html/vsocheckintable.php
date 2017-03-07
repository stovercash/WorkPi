<?php
$mysecrets = json_decode(file_get_contents("/home/pi/dev/WorkPi/setup/secrets.json"), true);

$bordersize = 2;
$boxsize = 20;
$ytotal = 5;
$xtotal = 12;
$maxchanges = 50;

$y = 1;
$total_days = $ytotal * $xtotal;
$offset_days = $total_days + 1;

$mysqli = new mysqli('localhost',$mysecrets["sql"]["user"],$mysecrets["sql"]["pass"],$mysecrets["sql"]["dbname"]);
$sql_stmt = $mysqli->prepare("SELECT IFNULL(SUM(NoOfObjects),0) AS Changes FROM VSOCheckIn WHERE DateCheckedIn BETWEEN ? AND ?");

while($y <= $ytotal)
{
	$x = 1;
	while($x <= $xtotal)
	{
		$subtract_days = $offset_days - ((($x - 1) * $ytotal) + $y);
		$this_date = new DateTime();
		$this_date = $this_date->modify("-$subtract_days days");
		$this_month = intval($this_date->format('m'));

		$this_start_time = $this_date->format('Y-m-d') . " 00:00:00.00";
		$this_end_time = $this_date->format('Y-m-d') . " 23:59:59.999";
		$sql_stmt->bind_param("ss",$this_start_time,$this_end_time);
		$sql_stmt->execute();
		$sql_stmt->bind_result($changes);
		$sql_stmt->fetch();

		echo '<div style="height: ' . $boxsize . 'px; width: ' . $boxsize . 'px; border-width: ' . $bordersize . 'px; border-style: solid; background-color: #ff0000; float: left; ';
		if (($this_month % 2) == 0)
		{
			echo 'border-color: #D9D9D9; ';
			$this_gray = 217;
		}
		else
		{
			echo 'border-color: #E5E5E5; ';
			$this_gray = 229;
		}
		if ($changes > $maxchanges)
		{
			$changes = $maxchanges;
		}
		$changes = round($this_gray * (($maxchanges - $changes) / $maxchanges));
		$hexchanges = dechex($changes);
		if ($changes < 16)
		{
			$hexchanges = '0' . dechex($changes);
		}
		else
		{
			$hexchanges = dechex($changes);
		}
		echo 'background-color: #' . dechex($this_gray) . $hexchanges . $hexchanges . '; ';
		if ($x == 1)
		{
			echo 'clear: left';
		}


		echo '">&nbsp</div>';
		$x++;
	}
	$y++;
}
$sql_stmt->close();
?>
