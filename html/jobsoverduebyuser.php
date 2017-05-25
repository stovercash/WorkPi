<?php
$mysecrets = json_decode(file_get_contents("/home/pi/dev/WorkPi/setup/secrets.json"), true);
$total_days = 60;
$first = true;
$mysqli = new mysqli('localhost',$mysecrets["sql"]["user"],$mysecrets["sql"]["pass"],$mysecrets["sql"]["dbname"]);
$sql_checkin_stmt = $mysqli->prepare("SELECT IFNULL(OverdueJobs,0) AS OverdueJobs FROM JobUserStats WHERE (DateChecked = ?) AND (UserID = ?)");

$today_date = new DateTime();

$this_time = $today_date->format('Y-m-d') . " 00:00:00.00";

echo '<div style="width: 480px; height: 255px; background-color: white; margin: 20px; border-color: white; border-radius: 10px 35px; position: relative; float: left">';
echo '<div style="width: 100%; height: 10px; background-color: transparent; clear:left; float: left"></div>';
echo '<div style="width: 100%; height: 25px; background-color: transparent; text-align: center; clear:left; float: left"><span style="font-size: 20px; font-family: consolas; color: LightGray;">Overdue Logs</span></div>';
for ($i = 1; $i <= 10; $i++)
{
	echo '<div style="width: 30px; height: 20px; background-color: transparent; clear:left; float: left"></div>';
	$sql_user_stmt = $mysqli->prepare("SELECT UserID FROM VSOUser");
	$sql_user_stmt->execute();
	$sql_user_stmt->bind_result($userid);
	$sql_user_stmt->store_result();
	$userno = 0;
	while ($sql_user_stmt->fetch())
	{
		$userno++;
		$sql_checkin_stmt->bind_param("ss",$this_time,$userid);
		$sql_checkin_stmt->execute();
		$sql_checkin_stmt->bind_result($overdue);
		$sql_checkin_stmt->fetch();
		echo '<div style="width: 60px; height: 20px; background-color: ';
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
		if ($i == 10)
		{
			echo '<div style="width: 60px; height: 50px; position: absolute; bottom: 0px; ';
			echo 'left: ' . (30 + (($userno-1) * 60)) . 'px; ';
			echo 'background-color: transparent; font-color: black; font-family: consolas; font-size: 150%; text-align: center; text-shadow: 0 0 5px #000000;">' . $userid . '</div>';
			if ($overdue > 10)
			{
				echo '<div style="width: 60px; height: 80px; position: absolute; top: 35px; ';
				echo 'left: ' . (30 + (($userno-1) * 60)) . 'px; ';
				echo 'background-color: transparent; font-color: black; font-family: consolas; font-size: 250%; text-align: center; text-shadow: 0 0 5px #000000;">' . $overdue . '</div>';
			}
		}
	}
	echo '<div style="width: 30px; height: 20px; background-color: transparent; float: left"></div>';
	$sql_user_stmt->close();
}
echo '</div>';
?>
