<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=cit2202', 'cit2202', 'letmein');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
	echo "Oh no, there was a problem" . $exception->getMessage();
}

//select all the countries
$query = "SELECT id, name FROM countries";
$resultset = $conn->query($query);
$countries = $resultset->fetchAll();
$conn=NULL;

?>

<!DOCTYPE HTML>
<html>
<head>
<title>Add new city</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<h1>Add a new city</h1>
<form action="save.php" method="post">
<div>
<label for="name">Name of city:</label>
<input type="text" id="name" name="name">
</div>
<div>
<label for="latitude">Latitude:</label>
<input type="text" id="latitude" name="latitude">
</div>
<div>
<label for="longitude">Logitude:</label>
<input type="text" id="longitude" name="longitude">
</div>
<div>
<label for="country">Country:</label>
<!-- Output a dropdown menu so the user can select a single country -->
<select id="country" name="country">
<?php
foreach($countries as $country){
	echo "<option value='{$country["id"]}'>{$country["name"]}</option>";
}
?>
</select>
</div>
<input type="submit" name="submitBtn" value="Add City to the Database">
</form>

</body>
</html>
