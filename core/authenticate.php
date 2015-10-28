<?php

include_once('configuration.php');

//GET INFO FROM APP
$ISWHERE_LOGIN_USERNAME = $_GET['username'];
$ISWHERE_LOGIN_PASSWORD = $_GET['password'];

//CREATE API KEY... MAYBE
$API = $ISWHERE_LOGIN_USERNAME . $ISWHERE_LOGIN_PASSWORD;
$ISWHERE_API_KEY = hash('md5', $API);

//HASH PASSWORD
$ISWHERE_LOGIN_PASSWORD_HASHED = md5($ISWHERE_LOGIN_PASSWORD);

//CHECK USER
$result = mysqli_query($CON,"SELECT * FROM `users` WHERE `Username`='$ISWHERE_LOGIN_USERNAME' AND `Password`='$ISWHERE_LOGIN_PASSWORD_HASHED'");
$valid_user = mysqli_num_rows($result);

//GET USER DATA
while($row = mysqli_fetch_array($result)) {
    $ISWHERE_API_USERNAME = $row['Username'];
    $ISWHERE_API_FULLNAME = $row['Fullname'];
}

//DISPLAY DATA IN APP READABLE FORMAT
if($valid_user == 1){
    echo "true, " . $ISWHERE_API_KEY . ", " . $ISWHERE_API_USERNAME . ", " . $ISWHERE_API_FULLNAME . ", " . $ISWHERE_LOGIN_PASSWORD_HASHED . "<br>";
}else{
    echo "false";
}
?>