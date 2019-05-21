<?php

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

$logininfo = array(
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
curl_setopt($ch, CURLOPT_POSTFIELDS, $logininfo);

//Save our session (a.k.a make a cookie) in a text file
curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");

//Dont just echo the results, let us save the results
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Execute the results (with the given options) and save it in a variable
$pageresult = curl_exec($ch);


/* Check if NJIT login was successfull */
$successcode = "Please select a MyHousing System to sign into";
$failcode = "Please login using your UCID and password to access the online room selection system";

if(strpos($pageresult, $successcode) == TRUE)
{
    echo "Success!";
}
elseif (strpos($pageresult, $failcode) == TRUE)
{
    echo "Failed!";
}
else
{
    //In case anything happens
    echo "<center><h1>What Happened?</h1></center>" . "\n";
    echo $pageresult;
}

?>