<?php
//copyright 2009 MyriComp LLC
/*Description:  Processes the form submission for example2.php. Displays success
                if user has passed the peoplesign CAPTCHA.
                User response is checked using a single peoplesign client
		function call: getPeoplesignSessionStatus
*/

//This function is not called until the user passes peoplesign
function printSuccess() {
    print (
"<html><body style=\"width: 500px; margin-left: auto; margin-right:auto;\">"
."<p>You passed!</p>"
."<p>Thanks for using "
   ."<a href=\"http://www.peoplesign.com\" "
      ."style=\"position: relative; top: 6px;\" "
      ."alt=\"peoplesign--people sign in, spam stays out\">"
         ."<img src=\""
              ."http://peoplesign.com/content/peoplesign_medLogo.png\" "
	      ."border=0/>"
   ."</a>"
."</p>"
."<p>Click <a href=\"" .$_SERVER['HTTP_REFERER'] ."\">here</a> to try again."
."</p>"
."</body></html>");

}

session_start();

include("peoplesignClient.php");

/*WARNING!!! The following key (293b6433379cf498dfa3b4f7437e17ad) is a sample
  for testing purposes only.  Eventually it will stop working.
  You can get a personal key at
  http://peoplesign.com/main/registerToHostPeoplesign.html
  It's quick, easy and free*/
$peoplesignKey = "293b6433379cf498dfa3b4f7437e17ad";

/* ATTENTION... the location must match the location used when getting the 
                peoplesign html*/
$thisPeoplesignLocation = "example2";

$peoplesignResponse = $_POST['captcha_peoplesignCRS'];
$peoplesignSessionID = $_POST['challengeSessionID'];


$allowPass = processPeoplesignResponse($peoplesignSessionID,
				   $peoplesignResponse,
				   $thisPeoplesignLocation,
				   $peoplesignKey );

session_start();

if ($allowPass) {
   printSuccess();
   exit;
} else {
   header( 'Location: '.$_SERVER['HTTP_REFERER'] );
   exit;
}
?>


