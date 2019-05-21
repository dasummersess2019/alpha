<?php
//To correctly output JSON info
header('Content-Type: application/json;charset=utf-8');

/* For Manual Testing
$userID = "";
$userPass = "";
*/

/*For Live service - AFS: */
$POSTuserID = $_POST['userID'];
$POSTuserPass = $_POST['userPass'];

//---DONT CHANGE ANYTHING BELOW-----

//Sanitize User Input In Case of Malicious Attack & Return Result to Var;
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$userID = clean_input($POSTuserID);
$userPass = clean_input($POSTuserPass);

/*------------------------------NJIT---------------------------------*/
$logininfoNJIT = array(
    //first parameter => second parameter
    //e.g. user => pass
    "ucid" => $userID,
    "pass" => $userPass
);


//Initizalize cURL session with Target URL
$ch = curl_init("https://aevitepr2.njit.edu/myhousing/login.cfm");
//Set FOLLOWLOCATION to TRUE so we can allow redirects & get result
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//Submit the request as a POST request
curl_setopt($ch, CURLOPT_POST, true);
//Use the following information (user & pass)
curl_setopt($ch, CURLOPT_POSTFIELDS, $logininfoNJIT);
//Dont just echo the results, let us save the results
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//Execute the results (with the given options) and save it in a variable
$NJITpageresult = curl_exec($ch);
/*--------------------------------------------------------------------*/


/*-----------------CS490 Database-------------------------------------*/

/*
$logininfoDB = array(
    //first parameter => second parameter
    //e.g. user => pass
    "ucid" => $userID,
    "pass" => $userPass
);

$ch2 = curl_init("<DB URL HERE>");
//Set FOLLOWLOCATION to TRUE so we can allow redirects & get result
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
//Submit the request as a POST request
curl_setopt($ch2, CURLOPT_POST, true);
//Use the following information (user & pass)
curl_setopt($ch2, CURLOPT_POSTFIELDS, $logininfoDB);
//Dont just echo the results, let us save the results
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
//Execute the results (with the given options) and save it in a variable
$DBpageresult = curl_exec($ch2);
*/

/*--------------------------------------------------------------------*/


/* Check if NJIT login was successfull */
$NJITsuccesscode = "Please select a MyHousing System to sign into";
/* Check if the database was successful */
$DBsuccesscode = "";

$njitstatus = 0;
$dbstatus = 0;

if(strpos($NJITpageresult, $NJITsuccesscode) == TRUE)
{
    $njitstatus = 1;
}

/*
if(strpos($DBpageresult, $DBsuccesscode) == TRUE)
{
    $dbstatus = 1;
}
*/

$jsonResults = array('NJIT' => $njitstatus, 'DB' => $dbstatus);
//Add DB to json later

$echoJSON = json_encode($jsonResults);

echo $echoJSON;

?>

