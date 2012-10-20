<!-- 
	Team: O(n) && 0xFF
	24 Hours of Good - Seattle Hackathon
	10/20/2012
	
	Google Notification and Data Collection.

	This is a .php file that notifies google that the transaction
	has taken place and that we acknowledge it. It also collects
	the data to keep in database. Have this file be the checkout
	api's callback url and give it the Notification Serial Number.
-->
<?php 
	#The id and key given by google for the merchant account
	$merchant_id  = "930430079376986";
	$merchant_key = "z8D0h_z5GtVGTBNMORVlVQ";
	
	#Use several files from the Google API
	require_once(dirname(__file__).'/googleresponse.php');
	require_once(dirname(__file__).'/googlenotificationhistoryrequest.php');
	
	#Sendback and acknowledge the donation for google
	$gResponse = new googleresponse($merchant_id, $merchant_key);
	$gResponse->SendAck($_POST["serial-number"], false);
	
	#Get an XML file of the data from this donation
	$gRequest = new GoogleNotificationHistoryRequest($merchant_id, $merchant_key);
	$raw_xml_array = 
		$gRequest->SendNotificationHistoryRequest($sn = $_POST["serial-number"]);
	
	#Print out the XML file (which is stored as an array) to a text
	#file. Could be changed to SQL.
	foreach($raw_xml_array as $Idunno) {
		file_put_contents("data.txt",$Idunno . "\n", FILE_APPEND);
	}
?>