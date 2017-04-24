<?php
$mysecrets = json_decode(file_get_contents("/home/pi/dev/WorkPi/setup/secrets.json"), true);
$total_days = 60;
$first = true;
$mysqli = new mysqli('localhost',$mysecrets["sql"]["user"],$mysecrets["sql"]["pass"],$mysecrets["sql"]["dbname"]);
$sql_checkin_stmt = $mysqli->prepare("SELECT IFNULL(OverdueJobs,0) AS OverdueJobs FROM JobUserStats WHERE (DateChecked = ?) AND (UserID = ?)");
$sql_user_stmt = $mysqli->prepare("SELECT UserID FROM VSOUser");

$today_date = new DateTime();

$this_time = $today_date->format('Y-m-d') . " 00:00:00.00";

$sql_user_stmt->execute();
$sql_user_stmt->bind_result($userid);
$sql_user_stmt->store_result();
while ($sql_user_stmt->fetch())
{
	$sql_checkin_stmt->bind_param("ss",$this_time,$userid);
	$sql_checkin_stmt->execute();
	$sql_checkin_stmt->bind_result($overdue);
	$sql_checkin_stmt->fetch();
	echo '<div style="width: 120px; height: 240px; background-color: white; margin: 20px; border-color: white; border-radius: 10px 35px; position: relative; float: left">';
	echo '<div style="width: 100%; height: 20px; background-color: transparent; clear:left; float: left"></div>';
	for ($i = 1; $i <= 10; $i++)
	{
		echo '<div style="width: 25%; height: 20px; background-color: transparent; clear:left; float: left"></div>';
		echo '<div style="width: 50%; height: 20px; background-color: ';
		if (($overdue > (10-$i)) && ($overdue > 10))
		{
			echo '#C00000';
		}
		elseif ($overdue > (10-$i))
		{
			echo '#C0C0C0';
		}
		else
		{
			echo 'transparent';
		}
		echo '; float: left"></div>';
		echo '<div style="width: 25%; height: 20px; background-color: transparent; float: left"></div>';
	}
	echo '<div style="width: 100%; height: 50px; position: absolute; bottom: 15px; left: 0px; background-color: transparent; font-color: black; font-family: consolas; font-size: 300%; text-align: center; text-shadow: 0 0 5px #000000;">' . $userid . '</div>';
	if ($overdue > 10)
	{
		echo '<div style="width: 100%; height: 80px; position: absolute; top: 30px; left: 0px; background-color: transparent; font-color: black; font-family: consolas; font-size: 500%; text-align: center; text-shadow: 0 0 5px #000000;">' . $overdue . '</div>';
	}
	echo '</div>';
}
?>
