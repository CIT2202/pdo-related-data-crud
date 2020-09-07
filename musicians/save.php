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
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];

$instruments=[];
if(isset($_POST['instruments'])){
	$instruments=$_POST['instruments'];
}

//SQL INSERT for adding a new row
//Use a prepared statement to bind the values from the form
$query="INSERT INTO musicians (id, first_name, last_name) VALUES (NULL, :first_name, :last_name)";
$stmt=$conn->prepare($query);
$stmt->bindValue(':first_name', $first_name);
$stmt->bindValue(':last_name', $last_name);
$stmt->execute();

//now we need the id of the musician we have just inserted
$newMusicianId = $conn->lastInsertId(); //have a look at https://www.php.net/manual/en/pdo.lastinsertid.php

//instruments is an array of instrument ids
//for each instrument id insert a row into the musician_instrument junction table
foreach($instruments as $instrumentId){
	$query="INSERT INTO instrument_musician (instrument_id, musician_id) VALUES (:instrumentId, :musicianId)";
	$stmt=$conn->prepare($query);
	$stmt->bindValue(':musicianId', $newMusicianId);
	$stmt->bindValue(':instrumentId', $instrumentId);
	$stmt->execute();
}

$conn=NULL;
?>


<!DOCTYPE HTML>
<html>
<head>
<title>Save musicians</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<?php
echo "<p>Added the details for {$first_name} {$last_name}</p>";
?>
</body>
</html>
