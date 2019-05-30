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

$NJITurl = 'https://aevitepr2.njit.edu/myhousing/login.cfm';
$DBurl = 'https://<back-end-url>/login.php';

//---DONT CHANGE ANYTHING BELOW-----

//Sanitize User Input & Return Result to Var;
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$userID = clean_input($POSTuserID);
$userPass = clean_input($POSTuserPass);

$njitstatus = 0;
$dbstatus = 0;

/* Check if NJIT login was successfull */
$NJITsuccesscode = "Please select a MyHousing System to sign into";


/* Check if DB login was successful (NOT NEEDED ANYMORE)
$DBsuccesscode = "Success";
*/

/*------------------------------NJIT---------------------------------*/
$NJITpageresult = false;

$logininfoNJIT = array(
    //first parameter => second parameter
    //e.g. user => pass
    "ucid" => $userID,
    "pass" => $userPass
);


//Initizalize cURL session with Target URL
$ch = curl_init($NJITurl);
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
curl_close($ch);


if($NJITpageresult !== false)
{
    if(strpos($NJITpageresult, $NJITsuccesscode) == TRUE)
    {
        $njitstatus = 1;
    }
    else
        {
        $njitstatus = 0;
    }
}
else
{
    $njitstatus = "Failed to get response from NJIT server";
}

/*--------------------------------------------------------------------*/


/*-----------------CS490 Database-------------------------------------*/

$DBpageresult = false;

$logininfoDB = array(
    //first parameter => second parameter
    //e.g. user => pass
    "username" => $userID,
    "password" => $userPass
);

$ch2 = curl_init($DBurl);
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
curl_close($ch2);

if(DBpageresult !== false)
{
    /*
    if(strpos($DBpageresult, $DBsuccesscode) == TRUE)
    {
        $dbstatus = 1;
    }
    else 
    {
        $dbstatus = 0;
    }
    */
  $dbresponse = json_decode($DBpageresult,true);
	if($dbresponse['return_code'] == 1)
	{
     $dbstatus = 1;
	}
	else
	{
		$dbstatus = 0;
	}
}
else
{
    $dbstatus = "Failed to get response from DB server";
}

//$dbresponse = json_decode($DBpageresult,true)

/*--------------------------------------------------------------------*/

$jsonResults = array('NJIT' => $njitstatus, 'DB' => $dbstatus);

$echoJSON = json_encode($jsonResults);

echo $echoJSON;

?>

