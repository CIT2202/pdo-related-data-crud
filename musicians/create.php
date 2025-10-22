<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=cit2202', 'cit2202', 'letmein');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
	echo "Oh no, there was a problem" . $exception->getMessage();
}

//select all the genres
$query = "SELECT id, name FROM instruments";
$resultset = $conn->query($query);
$instruments = $resultset->fetchAll();
$conn=NULL;

?>

<!DOCTYPE HTML>
<html>
<head>
<title>Add new musician</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<h1>Add a new musician</h1>
<form action="save.php" method="post">
<div>
<label for="first_name">First name:</label>
<input type="text" id="first_name" name="first_name">
</div>
<div>
<label for="last_name">Last name:</label>
<input type="text" id="last_name" name="last_name">
</div>
<div>
<!-- Output a checkbox, one for each instrument -->
<fieldset>
<legend>Select the musician's instruments</legend>
<?php
foreach($instruments as $instrument){
	//e.g. "<label for='Banjo'><input type='checkbox' name='instruments[]' value='1' id='Banjo'>Banjo</label>";
	// The instruments[] means pass the selected items as an array
	echo "<label for='{$instrument["name"]}'><input type='checkbox' name='instruments[]' value='{$instrument["id"]}' id='{$instrument["name"]}'>{$instrument["name"]}</label>";
}
?>
</fieldset>
</div>

<input type="submit" name="submitBtn" value="Add Musician">
</form>

</body>
</html>
