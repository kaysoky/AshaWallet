<!DOCTYPE html>


<?php 
	function printAll() {
		foreach($_POST as $key => $value) {
			file_put_contents("data.txt",$key . "\t" . $value . "\n", FILE_APPEND);
		}
	}
?>
<html>
	<body>
		<h1> test </h1>
		<?php
			//printAll();
			$amount = (float) $_POST["order-summary_order-total"];
			$name = $_POST["order-summary_buyer-shipping-address_contact-name"];
			$email = $_POST["buyer-billing-address_email"];
			$status = $_POST["order-summary_financial-order-state"];
			$key = $_POST["order-summary_shopping-cart_items_item-1_item-description"];
			$project = $_POST["order-summary_shopping-cart_items_item-1_item-name"];
			$time = $_POST["order-summary_purchase-date"];
			$googleID = $_POST["google-order-number"];
			$data = array($amount, $name, $email, $_POST["order-summary_buyer-shipping-address_address1"],
						$_POST["order-summary_buyer-shipping-address_address2"],
						$_POST["order-summary_buyer-shipping-address_city"],
						$_POST["order-summary_buyer-shipping-address_region"],
						$_POST["order-summary_buyer-shipping-address_postal-code"], $status,
						$key, $project, $time, $googleID);
			
			
			foreach($data as $datum) {
				file_put_contents("data.txt", $datum . "; ", FILE_APPEND);
			}
			file_put_contents("data.txt", "\n", FILE_APPEND);
		?>
	</body>
</html>