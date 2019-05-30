<?php
header('Content-Type: application/json;charset=utf-8');
$name = $_POST['username'];
$pass = $_POST['password'];
//connects to database
$con = new mysqli('<sql database url>', '<user>','<pass>', '<db name>');
if ($conn->connect_error)
{  
  $err = "Failed to connect to MySQL: " . $conn->connect_error;
  $rc = array('return_code'=>-1, 'error_message'=>$err);
}
//query for ucid pswd match
$sql = "SELECT * FROM Test WHERE ucid = '" . $name . "' AND password = '" . hash('sha256', $pass) . "'";
$result = mysqli_query($con, $sql);
if(mysqli_num_rows($result) > 0)
{
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $rc = array('return_code'=>1);
  //if($row['admin']==1)
    //echo(json_encode("This is a professor"));
  //else 
    //echo(json_encode("Student"));
} 
else 
{
  $rc = array('return_code'=>0);
}
  echo(json_encode($rc));
?>
