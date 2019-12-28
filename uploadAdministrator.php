
<?php
if (isset ( $_POST ['submit'] )) {
	$fileName = $_POST ['malware_name'];
}
require_once 'login.php';
$conn = new mysqli ( $hn, $un, $pw, $db );
if ($conn->connect_error) {
	die ( $conn->connect_error );
}

if (isset ( $_POST ['malware_name'] )) {
	$m_name = $_POST ['malware_name'];
	
	$sizeFile = $_FILES ['file_uploaded'] ['size'];
	$file_name = $_FILES ['file_uploaded'] ['name'];
	
	$db_upload = bytes_file2 ( $file_name, $sizeFile );
	
	add_user ( $conn, $m_name, $db_upload );
	print ("File Successfully Added") ;
}
function add_user($connection, $malware_name, $upload) {
	$query = "INSERT INTO Users.malware VALUES('$malware_name', '$upload')";
	$result = $connection->query ( $query );
	if (! $result)
		die ( $connection->error );
}
function bytes_file2($file, $size) {
	$signature = "";
	if ($handle = fopen ( $file, "r" )) {
		$contents_inside = fread ( $handle, $size );
		
		for($j = 0; $j < $size; $j ++) {
			
			$asciiChar = $contents_inside [$j];
			$base = ord ( $asciiChar );
			$hexa = base_convert ( $base, 10, 16 );
			$signature .= $hexa;
		}
		fclose ( $handle );
		return $signature;
	} else
		die ( "Lack of permission to open it" );
}
?>


<html>
<body>
<h1>WELCOME</h1>.
<style>

body {
    background-color: lightgreen;
}
form {
  text-align: center;
  margin-top : 100px;
}
h1 {
    text-align: center;
    color:green;
}
</style>
<div class="form">

	<form method="post" action="UploadAdmin.php"
		enctype="multipart/form-data" align = "center">
			<font size="5" color="black">Upload malware: </font><input type="file" name="file_uploaded"
			size="60"> 	<font size="5" color="black"> Name the Malware: </font><input type="text" name="malware_name"><br><br> <br>
		<input type='submit' value="UPLOAD" style="width: 100px; height: 100px;">
	</form>
	</div>
	
	<br>
<div class="form1">

	<form method="post" action="administratorChecksMalware.php"
		enctype="multipart/form-data"  align = "center">
		<font size="5" color="green">Checking for Malware: </font><input type="file"
			name="uploaded_file" size="60"><br><br><br> <input type='submit'
			value="SUBMIT" style="width: 100px; height: 100px;">
	</form>
		</div>
	
</body>
</html>



