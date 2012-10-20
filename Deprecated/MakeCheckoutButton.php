<?php	
	function MakeCheckoutButton(
		$merchant_id, $merchant_key, $currency="USD", $server_type="sandbox", $size="large") {
		
		//Init the URL's
		$server_url = "https://checkout.google.com/";
		if(strtolower($server_type) == "sandbox") {
			$server_url = "https://sandbox.google.com/checkout/";
		}
		$checkout_url = $server_url . "api/checkout/v2/checkout/Merchant/" . $merchant_id;
		
		//Fill in button size
		$width = "180";
		$height = "46";
		switch (strtolower($size)) {
			case "medium":
				$width = "168";
				$height = "44";
				break;
			case "small":
				$width = "160";
				$height = "43";
				break;
			default:				
				break;
		}
		
		//Encode the cart and signature
		$cart_XML = GetXML();
		$sign_XML = base64_encode(CalcHmacSha1($cart_XML, $merchant_key));
		$cart_XML = base64_encode($cart_XML);
		
		return 
			'<div style="width: ' . $width . 'px">'
				. '<div align="center">'
					. '<form method="post" action="' . $checkout_url . '>'
						. '<input type="hidden" name="cart" value="' . $cart_XML . '"/>' 
						. '<input type="hidden" name="signature" '
							. 'value="' . $sign_XML . '"/>'
						. '<input type="image" name="Checkout" alt="Checkout" '
							. 'src="' . $server_url .'buttons/checkout.gif?merchant_id=' 
								. $merchant_id . '&amp;w=' . $width . '&amp;h=' . $height 
								. '&amp;style=trans&amp;variant=text&amp;loc=en_US" style="height:' . $height 
								. 'px;width:' . $width . 'px"/>'
					. '</form>'
				. '</div>'
				. '<div align="center">' 
					. "<a href=\"javascript:void(window.open('http://checkout.google.com/seller/what_is_google_checkout.html','whatischeckout','scrollbars=0,resizable=1,directories=0,height=250,width=400'));\" onmouseover=\"return window.status = 'What is Google Checkout?'\" onmouseout=\"return window.status = ''\"><font size=\"-2\">What is Google Checkout?</font></a>"
				. '</div>'
			. '</div>';
    }
	
    /**
     * Calculates the cart's hmac-sha1 signature, this allows google to verify 
     * that the cart hasn't been tampered by a third-party.
     * 
     * {@link http://code.google.com/apis/checkout/developer/index.html#create_signature}
     * 
     * @param string $data the cart's xml
     * @return string the cart's signature (in binary format)
     */
    function CalcHmacSha1($data, $merchant_key) {
      $blocksize = 64;
      $hashfunc = 'sha1';
      if (strlen($merchant_key) > $blocksize) {
        $merchant_key = pack('H*', $hashfunc($merchant_key));
      }
      $merchant_key = str_pad($merchant_key, $blocksize, chr(0x00));
      $ipad = str_repeat(chr(0x36), $blocksize);
      $opad = str_repeat(chr(0x5c), $blocksize);
      $hmac = pack(
                    'H*', $hashfunc(
                            ($key^$opad).pack(
                                    'H*', $hashfunc(
                                            ($key^$ipad).$data
                                    )
                            )
                    )
                );
      return $hmac; 
    }
	
	
	
	function GetXML() {
		require_once(dirname(__FILE__).'/gc_xmlbuilder.php');
	
		$xml_data = new gc_XmlBuilder();
	
		$xml_data->Push('checkout-shopping-cart',
			array('xmlns' => $this->schema_url));
		$xml_data->Push('shopping-cart');
	
	
		//Add XML data for each of the items
		// Preston's note: We only have one item at a time.
		// Need a substitute $item variable.
		$xml_data->Push('items');
			$xml_data->Push('item');
			$xml_data->Element('item-name', $item->item_name);
			$xml_data->Element('item-description', $item->item_description);
			$xml_data->Element('unit-price', $item->unit_price,
				array('currency' => $this->currency));
			
			// This is the DONATION_KEY.
		
			$xml_data->Element('merchant-private-item-data', $item->merchant_private_item_data);
	
			// Potential for use in recurrence.
			if($item->subscription){
			$sub = $item->subscription;
			$xml_data->Push('subscription', array('type' => $sub->subscription_type,
							'period' => $sub->subscription_period,
							'start-date' => $sub->subscription_start_date,
							'no-charge-after' => $sub->subscription_no_charge_after));
			$xml_data->Push('payments');
			$xml_data->Push('subscription-payment', array('times' => $sub->subscription_payment_times));
			$xml_data->Element('maximum-charge', $sub->maximum_charge, array('currency' => $this->currency));
			$xml_data->Pop('subscription-payment');
			$xml_data->Pop('payments');
			if(!empty($sub->recurrent_item)){
				$recurrent_item = $sub->recurrent_item;
				//Google Handled Subscriptions
				if($sub->subscription_type == 'google'){
				$xml_data->Push('recurrent-item');
				$xml_data->Element('item-name', $recurrent_item->item_name);
				$xml_data->Element('item-description', $recurrent_item->item_description);
				$xml_data->Element('unit-price', $recurrent_item->unit_price,
					array('currency' => $this->currency));
				$xml_data->Element('quantity', $recurrent_item->quantity);
				if($recurrent_item->merchant_private_item_data != '') {
				//          echo get_class($item->merchant_private_item_data);
					if(is_a($recurrent_item->merchant_private_item_data, 
														'merchantprivate')) {
					$recurrent_item->merchant_private_item_data->AddMerchantPrivateToXML($xml_data);
					}
					else {
					$xml_data->Element('merchant-private-item-data', 
													$recurrent_item->merchant_private_item_data);
					}        
				}
				$xml_data->Pop('recurrent-item'); 
				}
			}
			$xml_data->pop('subscription');                
			}        
			$xml_data->Pop('item');
		}
		$xml_data->Pop('items');
	
		// This is the DONATION_KEY.
		$xml_data->Element('merchant-private-data', $this->merchant_private_data);
	
		// Close the cart.
		$xml_data->Pop('shopping-cart');
	
		// Push some closing stuff.
		$xml_data->Push('checkout-flow-support');
		$xml_data->Push('merchant-checkout-flow-support');
		// Removed edit cart.
		
		// THIS IS IMPORTANT.
		$xml_data->Element('continue-shopping-url', $this->continue_shopping_url);	
	
		// Close up.
		$xml_data->Pop('merchant-checkout-flow-support');
		$xml_data->Pop('checkout-flow-support');
		$xml_data->Pop('checkout-shopping-cart');
	
		return $xml_data->GetXML();  
    }
?>
