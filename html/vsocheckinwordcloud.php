    var data2 = google.visualization.arrayToDataTable([
        ['Word','Parent','Count Size','Count Color'],
        ['Check In',null,0,0]
<?php
$mysecrets = json_decode(file_get_contents("/home/pi/dev/WorkPi/setup/secrets.json"), true);
$first = true;
$mysqli = new mysqli('localhost',$mysecrets["sql"]["user"],$mysecrets["sql"]["pass"],$mysecrets["sql"]["dbname"]);
$sql_wordcloud_stmt = $mysqli->prepare("SELECT Word, Count FROM VSOWordCloud ORDER BY Count DESC LIMIT 20");

$sql_wordcloud_stmt->execute();
$sql_wordcloud_stmt->bind_result($word,$count);
$sql_wordcloud_stmt->store_result();
while ($sql_wordcloud_stmt->fetch())
{
	echo ",['" . $word . "','Check In'," . $count . "," . $count . "]";
}
?>
	]);

	tree = new google.visualization.TreeMap(document.getElementById('chart_div_cloud'));

	tree.draw(data2, {
		minColor: '#C0C0C0',
		midColor: '#C04040',
		maxColor: '#C00000',
		headerHeight: 20,
		fontColor: 'black',
		showScale: false
	});
