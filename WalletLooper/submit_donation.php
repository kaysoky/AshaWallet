<?php
/*****************************************************************************/
	//Fetch arguments needed for Google Wallet
	//Also perform validation
	
	//These values should match that of the Google Wallet Merchant account
	$merchant_id = "930430079376986";
	$merchant_key = "z8D0h_z5GtVGTBNMORVlVQ";
	
	//Google Wallet supports "USD"
	$currency = $_POST['currency'];
	
	//The item name will be the title of the project (or Asha by default)
	$project = $_POST['project'];
	$item_name = (strlen($project) > 0 && preg_match("/\w+/", $project) ? $project : "Asha for Education");
	
	//The item description will be the description of the project
	$description = $_POST['description'];
	$item_description = (strlen($description) > 0 && preg_match("/\w+/", $description) ? $description : "A charitable cause");
	
	//The unit price will the the integer amount donated
	$donation = $_POST['donation'];
	$subscription = $_POST['subscription'];
	if (strlen($donation) <= 0 || preg_match("/.*\D+.*/", $donation)) {
		die("Invalid amount of donation");
	}
	$unit_price = $donation;
	
	//Not sure if this is important
	//$donor_address = "";
	
	//To be generated on server side
	$merchant_private_data = "1234"; //Unique donor ID goes here
	$continue_shopping_url = "http://www.ashanet.org/thank_you.php?"; //Arbitrary amounts of URL queries can be appended to this URL
	
	//------Structure for a recurring donation
	$subscription = array(
		"type" => "google",
		"period" => $_POST["subscription"],
		"payment_times" => $_POST["numRecur"],
		"maximum_charge" => $unit_price,
		"recurrent_item_name" => $item_name,
		"recurrent_item_description" => $item_description,
		"recurrent_unit_price" => $unit_price,
		"recurrent_quantity" => "1"
	);
	
	//-----Unused POST arguments
	//$requiredCardname = $_POST['requiredCardname'];
	//$requiredEmail = $_POST['requiredEmail'];
	//$display_chapter = $_POST['display_chapter'];
	//$chapter = $_POST['chapter'];
	//$requiredCurrency = $_POST['requiredCurrency'];
	//$join_list = $_POST['join_list'];
	//$Checkout = $_POST['Checkout'];
	
	
/*****************************************************************************/
	//Write an XML blob to send to Google Wallet
	//Refer to https://developers.google.com/checkout/developer/Google_Checkout_XML_API_Tag_Reference
	//	for the XML formatting
	require_once(dirname(__FILE__).'/xml-processing/gc_xmlbuilder.php');
	$xml_data = new gc_XmlBuilder();
	$xml_data->Push('checkout-shopping-cart', array('xmlns' => "http://checkout.google.com/schema/2"));
		$xml_data->Push('shopping-cart');
			$xml_data->Push('items');
				$xml_data->Push('item');
					$xml_data->Element('item-name', $item_name);
					$xml_data->Element('item-description', $item_description);
					$xml_data->Element('quantity', "1");
					$xml_data->Push('digital-content');
						$xml_data->Element('description', "In your heart");
					$xml_data->Pop('digital-content');

					// Potential for use in recurrence.
					if ($_POST["recurringCheck"]) {
						$xml_data->Element('unit-price', "0", array('currency' => $currency));
						$xml_data->Push('subscription', array('type' => $subscription["type"],
										'period' => $subscription["period"],
										'start-date' => $subscription["start_date"],
										'no-charge-after' => $subscription["no_charge_after"]));
							$xml_data->Push('payments');
								$xml_data->Push('subscription-payment', array('times' => $subscription["payment_times"]));
									$xml_data->Element('maximum-charge', $subscription["maximum_charge"], array('currency' => $currency));
								$xml_data->Pop('subscription-payment');
							$xml_data->Pop('payments');
							$xml_data->Push('recurrent-item');
								$xml_data->Element('item-name', $subscription["recurrent_item_name"]);
								$xml_data->Element('item-description', $subscription["recurrent_item_description"]);
								$xml_data->Element('unit-price', $subscription["recurrent_unit_price"], array('currency' => $currency));
								$xml_data->Element('quantity', $subscription["recurrent_quantity"]);
							$xml_data->Pop('recurrent-item');
						$xml_data->pop('subscription');                
					} else {
						$xml_data->Element('unit-price', $unit_price, array('currency' => $currency));
					}
				$xml_data->Pop('item');
			$xml_data->Pop('items');
			$xml_data->Element('merchant-private-data', $merchant_private_data);
		$xml_data->Pop('shopping-cart');
		$xml_data->Push('checkout-flow-support');
			$xml_data->Push('merchant-checkout-flow-support');
				$xml_data->Element('continue-shopping-url', $continue_shopping_url);	
			$xml_data->Pop('merchant-checkout-flow-support');
		$xml_data->Pop('checkout-flow-support');
	$xml_data->Pop('checkout-shopping-cart');
	
/*****************************************************************************/
	//Make and send the Google Request
	$certificate_path = ""; // set your SSL CA cert path
	$proxy=array();
	require_once(dirname(__FILE__).'/googlerequest.php');
	$GRequest = new GoogleRequest($merchant_id, 
				  $merchant_key, 
				  $server_url=="https://checkout.google.com/" ? "Production" : "sandbox",
				  $currency);
	$GRequest->SetProxy($proxy);
	$GRequest->SetCertificatePath($certPath);
	
	//Send the Google Request, which will redirect the page if successful
	list($status, $error) = $GRequest->SendServer2ServerCart($xml_data->GetXML());
	
	//If something goes wrong, then the page won't redirect and will hit this error section:
	echo "An error had ocurred: <br />HTTP Status: " . $status. ":";
	echo "<br />Error message:<br />";
	echo $error;
?>