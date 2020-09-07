<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=cit2202', 'cit2202', 'letmein');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
	echo "Oh no, there was a problem" . $exception->getMessage();
}
//the id from the query string e.g. details.php?id=4
$cityId=$_GET['id'];
//prepared statement uses the id to select a the city
//uses a JOIN to get the country details
$stmt = $conn->prepare("SELECT cities.name AS city, countries.name AS country, countries.population AS population FROM cities
INNER JOIN countries ON cities.country_id = countries.id
WHERE cities.id = :id");
$stmt->bindValue(':id',$cityId);
$stmt->execute();
$city=$stmt->fetch(); //only need one row

$conn=NULL;
?>


<!DOCTYPE HTML>
<html>
<head>
<title>Display the details for a city</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<?php
//simple validation to see if we found a city
if($city){
	echo "<h1>{$city['city']}</h1>";
	echo "<p>{$city['city']} is located in {$city['country']}. {$city['country']} has a population of {$city['population']}.</p>";
}
else
{
	echo "<p>Can't find the city</p>";
}
?>
</body>
</html>
