    var data3 = google.visualization.arrayToDataTable([
<?php
$mysecrets = json_decode(file_get_contents("/home/pi/dev/WorkPi/setup/secrets.json"), true);
$first = true;

$dateEnd = new DateTime('now');
$dateLoop = new DateTime('now');
$dateLoop = date_sub($dateLoop,date_interval_create_from_date_string("30 days"));

$mysqli = new mysqli('localhost',$mysecrets["sql"]["user"],$mysecrets["sql"]["pass"],$mysecrets["sql"]["dbname"]);
$sql_wordcloud_stmt = $mysqli->prepare("SELECT OpenJobHours FROM JobUserStats WHERE (UserID = ?) AND (DateChecked = ?)");
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

while ($dateLoop <= $dateEnd)
{
	echo ",['" . date_format($dateLoop,"m/d") . "'";
//	echo ",[new Date(" . date_format($dateLoop,"Y, m, d") . ")";
	$sql_user_stmt->execute();
	$sql_user_stmt->bind_result($userid);
	$sql_user_stmt->store_result();
	while ($sql_user_stmt->fetch())
	{
		$sql_wordcloud_stmt->bind_param("ss",$userid,date_format($dateLoop,"Y-m-d"));
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
		chartArea: {width:'300',height:'150'},
	}

	var chart3 = new google.visualization.AreaChart(document.getElementById('chart_div_jobhours'));
	chart3.draw(data3,options3);
