    var data3 = google.visualization.arrayToDataTable([
        ['Date','User','Hours']
<?php
$mysecrets = json_decode(file_get_contents("/home/pi/dev/WorkPi/setup/secrets.json"), true);
$first = true;

$dateEnd = new DateTime('now');
$dateStart = new DateTime('now');
$dateStart = date_sub($dateStart,date_interval_create_from_date_string("5 days"));

$mysqli = new mysqli('localhost',$mysecrets["sql"]["user"],$mysecrets["sql"]["pass"],$mysecrets["sql"]["dbname"]);
$sql_wordcloud_stmt = $mysqli->prepare("SELECT DateChecked, UserID, OpenJobHours FROM JobUserStats WHERE (DateChecked > ?) AND (DateChecked < ?)");

echo "SELECT DateChecked, UserID, OpenJobHours FROM JobUserStats WHERE (DateChecked > '" . date_format($dateStart,"Y-m-d") . "') AND (DateChecked < '" . date_format($dateEnd,"Y-m-d") . "')";

$sql_wordcloud_stmt->bind_param("ss",date_format($dateStart,"Y-m-d"),date_format($dateEnd,"Y-m-d"));
$sql_wordcloud_stmt->execute();
$sql_wordcloud_stmt->bind_result($dateText,$user,$hours);
$sql_wordcloud_stmt->store_result();
while ($sql_wordcloud_stmt->fetch())
{
	$date = date_create($dateText);
	date_format($date,"m/d/Y");
	echo ",['" . date_format($date,"m/d/Y") . "','" . $user . "'," . $hours . "]";
}
?>
	]);

	var options3 = {
		title: 'Open Job Hours',
		hAxis: {title: 'Date'}
	}

	var chart3 = new google.visualization.AreaChart(document.getElementById('chart3'));
	chart3.draw(data3,options3);
