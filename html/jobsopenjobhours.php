    var data3 = google.visualization.arrayToDataTable([
<?php
$mysecrets = json_decode(file_get_contents("/home/pi/dev/WorkPi/setup/secrets.json"), true);
$first = true;

$dateEnd = new DateTime('now');
$dateLoop = new DateTime('now');
$dateLoop = date_sub($dateLoop,date_interval_create_from_date_string("30 days"));
$dateLoop = date_sub($dateLoop,date_interval_create_from_date_string("10 weeks"));
$dateLoop = date_sub($dateLoop,date_interval_create_from_date_string("10 months"));
$dateLoopEnd = new DateTime('now');
$dateLoopEnd = date_sub($dateLoopEnd,date_interval_create_from_date_string("30 days"));
$dateLoopEnd = date_sub($dateLoopEnd,date_interval_create_from_date_string("10 weeks"));
$dateLoopEnd = date_sub($dateLoopEnd,date_interval_create_from_date_string("10 months"));

$mysqli = new mysqli('localhost',$mysecrets["sql"]["user"],$mysecrets["sql"]["pass"],$mysecrets["sql"]["dbname"]);
$sql_wordcloud_stmt = $mysqli->prepare("SELECT SUM(OpenJobHours) FROM JobUserStats WHERE (UserID = ?) AND (DateChecked >= ?) AND (DateChecked < ?)");
$sql_user_stmt = $mysqli->prepare("SELECT UserID FROM VSOUser");

$sql_user_stmt->execute();
$sql_user_stmt->bind_result($userid);
$sql_user_stmt->store_result();

echo "['Date'";
while ($sql_user_stmt->fetch())
{
	echo ",'" . $userid . "'";
}
echo "]";

$counter = 0;
while ($counter < 10)
{
	echo ",['M: " . date_format($dateLoop,"M") . "'";
	$dateLoopEnd = date_add($dateLoopEnd,date_interval_create_from_date_string("1 month"));
	$sql_user_stmt->execute();
	$sql_user_stmt->bind_result($userid);
	$sql_user_stmt->store_result();
	while ($sql_user_stmt->fetch())
	{
		$hours = 0;
		$sql_wordcloud_stmt->bind_param("sss",$userid,date_format($dateLoop,"Y-m-d"),date_format($dateLoopEnd,"Y-m-d"));
		$sql_wordcloud_stmt->execute();
		$sql_wordcloud_stmt->bind_result($hours);
		$sql_wordcloud_stmt->store_result();
		$sql_wordcloud_stmt->fetch();
		if (is_null($hours))
		{
			$hours = 0;
		}
		$dateDiff = $dateLoop->diff($dateLoopEnd);
		$dateDiffDays = $dateDiff->format('%a');
		$hours = $hours / $dateDiffDays;
		echo "," . $hours;
	}
	$dateLoop = date_add($dateLoop,date_interval_create_from_date_string("1 month"));
	$counter += 1;
	echo "]";
}

$counter = 0;
while ($counter < 10)
{
	echo ",['W: " . date_format($dateLoop,"m/d") . "'";
	$dateLoopEnd = date_add($dateLoopEnd,date_interval_create_from_date_string("1 week"));
	$sql_user_stmt->execute();
	$sql_user_stmt->bind_result($userid);
	$sql_user_stmt->store_result();
	while ($sql_user_stmt->fetch())
	{
		$hours = 0;
		$sql_wordcloud_stmt->bind_param("sss",$userid,date_format($dateLoop,"Y-m-d"),date_format($dateLoopEnd,"Y-m-d"));
		$sql_wordcloud_stmt->execute();
		$sql_wordcloud_stmt->bind_result($hours);
		$sql_wordcloud_stmt->store_result();
		$sql_wordcloud_stmt->fetch();
		if (is_null($hours))
		{
			$hours = 0;
		}
		$hours = $hours / 7;
		echo "," . $hours;
	}
	$dateLoop = date_add($dateLoop,date_interval_create_from_date_string("1 week"));
	$counter += 1;
	echo "]";
}
while ($dateLoop <= $dateEnd)
{
	echo ",['D: " . date_format($dateLoop,"m/d") . "'";
	$dateLoopEnd = date_add($dateLoopEnd,date_interval_create_from_date_string("1 day"));
	$sql_user_stmt->execute();
	$sql_user_stmt->bind_result($userid);
	$sql_user_stmt->store_result();
	while ($sql_user_stmt->fetch())
	{
		$hours = 0;
		$sql_wordcloud_stmt->bind_param("sss",$userid,date_format($dateLoop,"Y-m-d"),date_format($dateLoopEnd,"Y-m-d"));
		$sql_wordcloud_stmt->execute();
		$sql_wordcloud_stmt->bind_result($hours);
		$sql_wordcloud_stmt->store_result();
		$sql_wordcloud_stmt->fetch();
		if (is_null($hours))
		{
			$hours = 0;
		}
		echo "," . $hours;
	}
	$dateLoop = date_add($dateLoop,date_interval_create_from_date_string("1 day"));
	echo "]";
}
?>
	]);

	var options3 = {
		isStacked: true,
		fontName: 'Consolas',
		chartArea: {width:'300',height:'142'},
	}

	var chart3 = new google.visualization.AreaChart(document.getElementById('chart_div_jobhours'));
	chart3.draw(data3,options3);
