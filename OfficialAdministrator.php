<!doctype html>
<html>
<head></head>
<body>
<h1>CS174FinalProject</h1>.

<style>
body {
    background-color: pink;
}
form {
  text-align: center;
  margin-top : 50px;
}
h1 {
    text-align: center;
    color:black;
}
</style>
  <body style="text-align:center;">

	<table border="0" cellpadding="2" cellspacing="5" bgcolor="#00FF00" height="230" align="center" style="margin-top:30px;">
		<th colspan="2" align="center"><font size="5" color="black">Login Page for Administrator</font></th>
		<form method="POST" action="OfficialAdministrator.php" onSubmit="">
			<tr>
				<td><font size="5" color="black">Username</font></td>
				<td><input type="text" maxlength="15"></td>
			</tr>
			<tr>
				<td><font size="5" color="black">Password</font></td>
				<td><input type="text" maxlength="15"></td>
			</tr>
			<tr>
				<td colspan="5" align="center"><input type="submit"
					value="ADMIN LOGIN" style="width: 100px; height: 100px;"></td>	
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit"
					value="ADMIN SIGNUP"  style="width: 100px; height: 100px;"></td>			
			</tr>
		</form>
	</table>
	  </body>
	

 <?php 
 if (isset ( $_POST ['submit'] )) {
 	require_once 'Admin.php';
 }
	SESSION_START ();
	destroy_session_and_data();
	function destroy_session_and_data() {
		$_SESSION = array();
		setcookie(session_name(), '', time() - 2592000, '/');
		session_destroy();
	}
	require_once 'login.php';
	//create db connection
	$conn = new mysqli ( $hn, $un, $pw, $db );
	if ($conn->connect_error) {
		die ( $conn->connect_error );
	}
	//From lecture slides
	if (isset ( $_SERVER ['PHP_AUTH_USER'] ) && isset ( $_SERVER ['PHP_AUTH_PW'] )) {
		$user_tmp = mysql_entities_fix_string ( $conn, $_SERVER ['PHP_AUTH_USER'] );
		
		$pass_tmp = mysql_entities_fix_string ( $conn, $_SERVER ['PHP_AUTH_PW'] );
		
		find_user ( $conn, $user_tmp, $pass_tmp );
	} 
	else {
		header ( 'WWW-Authenticate: Basic realm="Restricted Section"' );
		header ( 'HTTP/1.0 401 Unauthorized' );
		die ( "Please enter your username and password" );
	}
	function mysql_entities_fix_string($connection, $string) {
		return htmlentities ( mysql_fix_string ( $connection, $string ) );
	}
	function mysql_fix_string($connection, $string) {
		if (get_magic_quotes_gpc ())
			$string = stripslashes ( $string );
		return $connection->real_escape_string ( $string );
	}
	function find_user($connection, $username, $password) {
		$query = "SELECT * FROM Users.users WHERE Username = '$username'";
		$result = $connection->query ( $query );
		if (! $result)
			die ( $connection->error );
		elseif ($result->num_rows) {
			$row = $result->fetch_array ( MYSQLI_NUM );
			$salt1 = "qm&h*";
			$salt2 = "pg!@";
			
			$result->close ();
			
			$token = hash ( 'ripemd128', "$salt1$password$salt2" );
			
			if ($token == $row [3]) {
				echo "$row[0] $row[1] : Hi $row[0],you are now logged in as '$row[2]'";
			} 
			else
				die ( "Invalid username/password combination" );
		}
		else die("Invalid username/password combination");
		
	}
	
	?>
	<br>
	<br>
	
	<form method="post" action="checkMalware.php"
		enctype="multipart/form-data">
		<font size="5" color="black">Choose a file:</font> <input type="file"
			name="uploaded_file" size="60"><br><br> <input type='submit'
			value="SUBMIT"  style="height: 100px; width: 100px;">
	</form>
 </body>
</html>
