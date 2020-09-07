# Working with related data
The previous examples we looked at using PDO used single database tables. The following considers how we can work with multiple tables that are related to each other.

## Reading Data
Previously we looked at creating a *details.php* page that used the id number of a record to query a database table and show information for that record. Consider the musicians and instruments example we looked at previously.

| id | first_name | last_name |
|----|------------|-----------|
| 1  | Jane       | Compton   |
| 2  | Jane       | Atherton  |
| 3  | Kate       | Hutton    |
| 4  | Sunil      | Laxman    |

| id | name      |
|----|-----------|
| 1  | Banjo     |
| 2  | Saxophone |
| 3  | Guitar    |
| 4  | Drums     |

| instrument_id  | musician_id |
|----------------|-------------|
| 4              | 1           |
| 2              | 3           |
| 4              | 4           |
| 1              | 1           |
| 3              | 1           |
| 3              | 2           |
| 2              | 4           |

When we view the details for a musician it would be nice to show the instruments that musician plays. Have a look at the following PHP code that does this.

```php
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
  //display the musicians full name
	echo "<h1>{$musician['first_name']} {$musician["last_name"]}</h1>";

  //display the instruments the musician plays
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
```

In this example we run two separate SQL queries. One to get data from the musicians table, and a second query that uses a join to get data from the instruments table.

There isn't really anything in this code haven't seen before, but it brings together many of the ideas we have looked at in recent weeks e.g. joins, using PDO.

## Creating Data

### One-to-many relationships

Thinking about the cities example. When we add a new city to the database we need to specify the country that city is located in.

One way to do this is to create a dropdown list of countries on the create page. The user can then select a country from this list.

```php
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
$query = "SELECT * FROM countries";
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
<!-- Output a dropdown menu so the user can select a country for the city -->
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
```

Note that we get a list of all the countries from the database.
When the form is created we use this list of countries to dynamically generate a dropdown menu.

### Many-to-many relationships
Many-to-many relationships e.g. musicians and instruments are a bit different. The user can select multiple instruments for a musician. One way of doing this is to use a series of checkboxes.

```php
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
$query = "SELECT * FROM instruments";
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
```

We need to get hold of all the instruments from the database so we do this at the top of the page.
Using a foreach loop we generate a checkbox for each instrument. the user can then select the instruments they want to associate with the new musician.
