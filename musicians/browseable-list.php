<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=cit2202', 'cit2202', 'letmein');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
	echo "Oh no, there was a problem" . $exception->getMessage();
}

//select all the musicians
$query = "SELECT * FROM musicians";
$resultset = $conn->query($query);
$musicians = $resultset->fetchAll();
$conn=NULL;

?>
<!DOCTYPE HTML>
<html>
<head>
<title>List the musicians</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
	<ul>
<?php

//loop over the array of musicians
foreach ($musicians as $musician) {
    echo "<li>";
    echo "<a href='details.php?id={$musician["id"]}'>{$musician["first_name"]} {$musician["last_name"]}</a>";
    echo "</li>";
}

?>
</ul>
</body>
</html>
