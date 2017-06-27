    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Dev');
    data.addColumn('number', 'Check Ins');

    data.addRows([

<?php
$mysecrets = json_decode(file_get_contents("/home/pi/dev/WorkPi/setup/secrets.json"), true);
$total_days = 60;
$first = true;
$mysqli = new mysqli('localhost',$mysecrets["sql"]["user"],$mysecrets["sql"]["pass"],$mysecrets["sql"]["dbname"]);
$sql_checkin_stmt = $mysqli->prepare("SELECT IFNULL(SUM(NoOfObjects),0) AS Changes FROM VSOCheckIn WHERE (DateCheckedIn BETWEEN ? AND ?) AND (UserID = ?)");
$sql_user_stmt = $mysqli->prepare("SELECT UserID FROM VSOUser");

$end_date = new DateTime();
$start_date = new DateTime();
$start_date = $start_date->modify("-$total_days days");

$this_start_time = $start_date->format('Y-m-d') . " 00:00:00.00";
$this_end_time = $end_date->format('Y-m-d') . " 23:59:59.999";

$sql_user_stmt->execute();
$sql_user_stmt->bind_result($userid);
$sql_user_stmt->store_result();
while ($sql_user_stmt->fetch())
{
	$sql_checkin_stmt->bind_param("sss",$this_start_time,$this_end_time,$userid);
	$sql_checkin_stmt->execute();
	$sql_checkin_stmt->bind_result($changes);
	$sql_checkin_stmt->fetch();
	if ($changes > 0)
	{
		if ($first)
		{
			$first = false;
		}
		else
		{
			echo ",";
		}
		echo "['" . $userid . "', " . $changes . "]";
	}
}
?>
	]);

	var options = {'legend':'none',
		'pieSliceText':'label',
		'backgroundColor':'transparent',
		'chartArea.top':0,
		'chartArea.left':0,
		'width':350,
		'height':350,
		'colors':['#C00000','#C03333','#C06666','#C09999','#C0C0C0','#888888','#444444','#000000'],
		'fontSize':14,
		'fontName':'Consolas'
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	chart.draw(data, options);
