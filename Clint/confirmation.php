<!DOCTYPE html>

<html>
<body>
<h1> test </h1>
<?php
	foreach($_POST as $key => $data) { 
		put_file_contents("data.txt",$key . $data);
	} ?>
</body>
</html>