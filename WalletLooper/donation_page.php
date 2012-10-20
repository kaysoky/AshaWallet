<html>
	<head>
		<TITLE>Asha for Education: Bringing hope through education</TITLE>
	</head>

	<body>
		<p> By Google Wallet. 
			<br /> Note: Donations made through this form are tax-deductible (only) in the US under Section 501(c)(3) of the IRS Code. If you reside outside of the US and want to make a tax-deductible donation, please use <a href="https://www.ashanet.org/index.php?page=chapters">this link</a>
		</p>
			
		<form method="post" action="submit_donation.php">
			Name <br />
			<input name="requiredCardname" 
				value="" 
				maxlength="40" 
				title="Please enter your name exactly as it is on your credit card"/>
			
			<br />Email
			<br /><input name="requiredEmail" 
				maxlength="50" 
				size="30" 
				value="" 
				title="Please enter your email address"/>
			
			<br /><input type="hidden" name="display_chapter" value="1"/>
			
			<!-- THIS CHAPTER CODE MUST BE CHANGED BY THE DATA AND WEB TEAMS.
			DONATION_KEY NEEDS TO BE IMPLEMENTED SOMEWHERE IN HERE SO WE CAN USE IT. -->

			Chapter
			<br /><select name='chapter' title='Please select'>
				<option value=''>---Choose One---</option>
				<option value='1'>Atlantis</option>
				<option value='2'>Seatown</option>
				<option value='3'>Wyoming</option>
			</select>
			
			<!-- THIS PROJECT CODE MUST BE CHANGED BY THE DATA AND WEB TEAMS. -->
			<br />Project<br />
			<select name='project' title='Please select'>
				<option value=''>---Choose One---</option>
				<option value='1'>#firstworldproblems</option>
				<option value='2'>#fifthworldproblemz</option>
				<option value='3'>#gymtanlaundry</option>	
			</select>

			<input type="hidden" name="requiredCurrency" value="1"/>
			<input type="hidden" name="currency" value="USD"/>

			<br />Comments
			<br /><textarea name="comments" wrap="hard" rows="3" cols="25"></textarea>
			<br /><br />Note: Paypal deducts around 3% of the amount listed above towards transaction fees.
			Asha for Education has a zero overhead policy on donations, i.e., 100% of all donations get spent on educational projects in India. Further, all of our administrative expenses are kept to a bare minimum, thanks to support from our volunteers. In addition to volunteers' contributions, our administrative expenses (including the 3% transaction fees mentioned above) get covered by our investment income and sales of merchandise. 
			<br /><br />
			
			<input type="checkbox" name="join_list" value="1" checked="true"/>
			Check here to find out more about joining an Asha chapter near you<br />
			
			<?php					
				//Init the URL's
				$server_type = "sandbox";
				$server_url = "https://checkout.google.com/";
				if(strtolower($server_type) == "sandbox") {
					$server_url = "https://sandbox.google.com/checkout/";
				}
				
				//Fill in button size
				$size = "large";
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
				
				//Print the submit button
				echo '<input type="image" name="Checkout" alt="Checkout" '
					. 'src="' . $server_url .'buttons/checkout.gif?merchant_id=' 
						. $merchant_id . '&amp;w=' . $width . '&amp;h=' . $height 
						. '&amp;style=trans&amp;variant=text&amp;loc=en_US" style="height:' . $height 
						. 'px;width:' . $width . 'px"/>';
			?>
		</form>
	</body>
</html>
