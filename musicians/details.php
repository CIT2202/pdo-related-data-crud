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
$musicianId=$_GET['id'];
//prepared statement uses the id to select a musician
$stmt = $conn->prepare("SELECT musicians.first_name, musicians.last_name FROM musicians
WHERE musicians.id = :id;");
$stmt->bindValue(':id',$musicianId);
$stmt->execute();
$musician=$stmt->fetch(); //only need one row

//run a second statement to get the instruments this musician plays
$stmt = $conn->prepare("SELECT instruments.name FROM musicians
INNER JOIN instrument_musician ON musicians.id = instrument_musician.musician_id
INNER JOIN instruments ON instrument_musician.instrument_id = instruments.id
WHERE musicians.id = :id;");
$stmt->bindValue(':id',$musicianId);
$stmt->execute();
$instruments=$stmt->fetchAll(); //there can be multiple instruments

$conn=NULL;
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Display the details for a musician</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<?php
//simple validation to see if we found a musician
if($musician){
	echo "<h1>{$musician['first_name']} {$musician["last_name"]}</h1>";

  echo "<p>Instruments:</p>";
  echo "<ul>";
  foreach($instruments as $instrument){
    echo "<li>{$instrument["name"]}</li>";
  }
  echo "</ul>";
}
else
{
	echo "<p>Can't find the musician</p>";
}
?>
</body>
</html>
