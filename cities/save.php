<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=cit2202', 'cit2202', 'letmein');
     $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
	echo "Oh no, there was a problem" . $exception->getMessage();
}


//This is a simple example we would normally do some form validation here

//basic form processing
$name=$_POST['name'];
$latitude=$_POST['latitude'];
$longitude=$_POST['longitude'];
$countryId=$_POST['country'];

//SQL INSERT for adding a new row
//Use a prepared statement to bind the values from the form
$query="INSERT INTO cities (id, name, latitude, longitude, country_id) VALUES (NULL, :name, :latitude, :longitude, :countryId)";
$stmt=$conn->prepare($query);
$stmt->bindValue(':name', $name);
$stmt->bindValue(':latitude', $latitude);
$stmt->bindValue(':longitude', $longitude);
$stmt->bindValue(':countryId', $countryId);
$stmt->execute();

$conn=NULL;
?>


<!DOCTYPE HTML>
<html>
<head>
<title>Save films</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<?php
echo "<p>Added the details for {$name}</p>";
?>
</body>
</html>
