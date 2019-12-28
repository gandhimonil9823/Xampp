<?php
//This first part is from hw 7
$firstname = $lastname = $username = $password=  $email = "";

if(isset($_POST['firstname']))
    $firstname = fix_string($_POST['firstname']);

if(isset($_POST['lastname']))
    $lastname = fix_string($_POST['lastname']);
if(isset($_POST['username']))
    $username = fix_string($_POST['username']);
if(isset($_POST['password']))
    $password = fix_string($_POST['password']);

if(isset($_POST['email']))
    $email = fix_string($_POST['email']);

$fail  = validate_firstname($firstname);
$fail .= validate_lastname($lastname);
$fail .= validate_username($username);
$fail .= validate_password($password);
$fail .= validate_email($email);

echo "<!DOCTYPE html>\n<html>
<head><title>Registration</title>";
if($fail == ""){
    require_once 'login.php';
    //creating a new connection to sql
    $connection = new mysqli($hn, $un, $password, $db);
    if ($connection->connect_error) die($connection->connect_error);
    //retrieved these salt values from slides
    $salt1 = "qm&h*";
    $salt2 = "pg!@";
    $token = hash('ripemd128', "$salt1$password$salt2");
    //add the user to db
    add_user($connection, $firstname, $lastname, $username, $token,$email);

}
//adding a user to the database
function add_user($connection, $forename, $surname, $username, $token,$mail)
{
	$query = "INSERT INTO Users.users VALUES('$forename', '$surname', '$username', '$token', '$mail')";
	$result = $connection->query($query);
	if (!$result) die($connection->error);

}

function validate_firstname($input){
	return ($input == "") ? "No First Name was entered.<br>" : "";
}

function validate_lastname($input){
	return ($input == "") ? "No Last Name was entered.<br>" : "";
}

function validate_username($input){
	if ($input == "") return "No Username was entered.<br>";
	else if (strlen($input) < 5)
		return "Usernames must be at least 5 characters.<br>";
		else if (preg_match("/[^a-zA-Z0-9_-]/", $input))
			return "Only uppercase, lowercase letters, numbers and - or _ are allowed in usernames.<br>";
			return "";
}

function validate_password($password){
	if ($password == '')
		return "No password was entered.<br>";
		else if (strlen($password) < 3){
			return "Passwords must be atleast 5 characters long.<br>";
		}
		else if(!preg_match("/[a-z]/", $password) ||
				!preg_match("/[A-Z]/", $password) ||
				!preg_match("/[0-9]/", $password)){
					return "Passwords require atleast one uppercase and lowercase letter, and a number.<br>";}
					return "";
}

function validate_email($input){
	if ($input == "") return "No email was entered.<br>";
	else if (!((strpos($input, ".") > 0) &&
			(strpos($input, "@") > 0)) ||
			preg_match("/[^a-zA-Z0-9.@_-]/", $input))
		return "The email address format is not valid.<br>";
		return "";
}

function fix_string($string){
	//Returns 0 if magic_quotes_gpc is off, 1 otherwise
    if(get_magic_quotes_gpc()) $string = stripslashes($string);
	return htmlentities ($string);
}
echo <<<_END


    <script src="validate_functions.js"></script>

    </head>

    <body>
<h1>CS 174Project</h1>.
<style>

body {
    background-color: blue;
}
h1 {
    text-align: center;
    color:red;
}

</style>
  <body style="text-align:center;">

        <table border="0" cellpadding="2" cellspacing="5" bgcolor="##ff00ff" height="230" align="center" style="margin-top:30px;">
            <th colspan="2" align="center"><font size="6" color="black">Register</font></th>
            <tr><td colspan="2"><font size="6" color="black"> These are the errors</font> <br> <font size="4" color="black">in the form:</font> <p><font color=green size=5><i>$fail</i></font></p></td></tr>
            <form method="post" action="Admin.php" onSubmit="return validate(this)">
                <tr><td><font size="5" color="black">First Name</font></td>
                    <td><input type="text" maxlength="30" name="firstname" value="$firstname"></td></tr>
                <tr><td><font size="4" color="black">Last Name</font></td>
                    <td><input type="text" maxlength="30" name="lastname" value="$lastname"></td></tr>
                <tr><td><font size="5" color="black">Username</font></td>
                    <td><input type="text" maxlength="16" name="username" value="$username"></td></tr>
                <tr><td><font size="4" color="black">Password</font></td>
                    <td><input type="text" maxlength="12" name="password" value="$password"></td></tr>
                <tr><td><font size="4" color="black">Email</font></td>
                    <td><input type="text" maxlength="64" name="email" value="$email"></td></tr>
                <tr><td colspan="2" align="center"><input type="submit"
                    value="Signup" style="width: 150px; height: 180px;"</td></tr>

            </form>
        </table>
	  </body>

    </body>
    </html>

_END;



?>
