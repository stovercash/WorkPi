<?php
$mysecrets = json_decode(file_get_contents("/home/pi/dev/WorkPi/setup/secrets.json"), true);

$total_days = 60;
$offset_days = $total_days + 1;

$mysqli = new mysqli('localhost',$mysecrets["sql"]["user"],$mysecrets["sql"]["pass"],$mysecrets["sql"]["dbname"]);
$sql_stmt = $mysqli->prepare("SELECT IFNULL(SUM(NoOfObjects),0) AS Changes FROM VSOCheckIn WHERE DateCheckedIn BETWEEN ? AND ?");

$end_date = new DateTime();
$start_date = new DateTime();
$start_date = $start_date->modify("-$offset_days days");
$end_date = $end_date->modify("1 days");

$this_start_time = $start_date->format('Y-m-d') . " 00:00:00.00";
$this_end_time = $end_date->format('Y-m-d') . " 23:59:59.999";

$sql_stmt->bind_param("ss",$this_start_time,$this_end_time);
$sql_stmt->execute();
$sql_stmt->bind_result($changes);
$sql_stmt->fetch();

echo '<div style="height: 20px; width: 100%; float: left; clear: left; font-size: 20px; text-align: center"><span style="position: relative; top: -4px; color: LightGray">' . $total_days . ' day average</span></div>';
echo '<div style="height: 80px; width: 100%; float:left; clear: left; font-size: 80px; vertical-align: middle; text-align: center"><span style="position: relative; top: -10px">' . round(($changes/$total_days),1) .  '</span></div>';

$start_date = new DateTime();
$end_date = new DateTime();
$prior_period_days = ($total_days * 2) + 1;
$start_date = $start_date->modify("-$prior_period_days days");
$end_date = $end_date->modify("-$offset_days days");

$this_start_time = $start_date->format('Y-m-d') . " 00:00:00.00";
$this_end_time = $end_date->format('Y-m-d') . " 23:59:59.999";

$sql_stmt->bind_param("ss",$this_start_time,$this_end_time);
$sql_stmt->execute();
$sql_stmt->bind_result($prior_changes);
$sql_stmt->fetch();

echo '<div style="height: 20px; width: 100%; float: left; clear: left; font-size: 20px; text-align: center">';
if ($prior_changes > $changes)
{
	echo '<span style="position: relative; top: -4px; color: red">' . round((($changes-$prior_changes)/$total_days),1) .  '</span></div>';
}
else
{
	echo '<span style="position: relative; top: -4px; color: green">+' . round((($changes-$prior_changes)/$total_days),1) .  '</span></div>';
}

$sql_stmt->close();
?>
