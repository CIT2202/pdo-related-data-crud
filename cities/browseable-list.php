<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=cit2202', 'cit2202', 'letmein');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
	echo "Oh no, there was a problem" . $exception->getMessage();
}

//select all the cities
$query = "SELECT id, name FROM cities";
$resultset = $conn->query($query);
$cities = $resultset->fetchAll();
$conn=NULL;

?>
<!DOCTYPE HTML>
<html>
<head>
<title>List the cities</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
	<ul>
<?php

//loop over the array of cities
foreach ($cities as $city) {
    echo "<li>";
    echo "<a href='details.php?id={$city["id"]}'>{$city["name"]}</a>";
    echo "</li>";
}

?>
</ul>
</body>
</html>
