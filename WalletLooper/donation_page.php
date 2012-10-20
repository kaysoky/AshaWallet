<!-- 
	Team: O(n) && 0xFF
	24 Hours of Good - Seattle Hackathon
	10/20/2012
	
	Main donation page
	
	The basic form for users to fill out for donations.
-->

<html>
	<head>
		<TITLE>Asha for Education: Bringing hope through education</TITLE>
		
		<!-- function to validate forms -->
		<script type="text/javascript">
			function validateForm()
			{
				var validateThis = document.forms[0]["fname"].value;
				if (x==null || x=="")
				{
					alert("First name must be filled out");
					return false;
				}
                
                validateThis = document.forms[0]["lname"].value;
    			if (x==null || x=="")
				{
					alert("Last name must be filled out");
					return false;
				}
                
                validateThis = document.forms[0]["emailaddr"].value;
        		if (function (validateThis) { 
                        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\
                        ".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA
                        -Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        
                        return re.test(validateThis);
                    } )
				{
					alert("Must enter a valid email address.");
					return false;
				}
                
                validateThis = parseInt(document.forms[0]["donation"].value);
                if (validateThis > 1000000)
                {
                    alert("Please contact an Asha representative about donating such a generous amount.");
                    return false;
                } else if (validateThis < 1) {
                    alert("Please enter a valid donation amount.");
                    return false;
                }
                
                validateThis = parseInt(document.forms[0]["numRecur"].value);
                if (validateThis < 2)
                {
                    alert("Please enter a recurrence greater than one.");
                    return false;
                }
                
                return true;
                
			}
		</script>
	</head>

	<body>
		<p> By Google Wallet. 
			<br /> Note: Donations made through this form are tax-deductible (only) in the US under Section 501(c)(3) of the IRS Code. If you reside outside of the US and want to make a tax-deductible donation, please use <a href="https://www.ashanet.org/index.php?page=chapters">this link</a>
		</p>
			
		<form method="post" action="submit_donation.php" onsubmit="return validateForm()">
			First Name <br />
			<input name="fname" 
				value="" 
				maxlength="40" 
				title="Please enter your name exactly as it is on your credit card"/>
                
            <br />Last Name <br />
    		<input name="lname" 
				value="" 
				maxlength="40" 
				title="Please enter your name exactly as it is on your credit card"/>
			
			<br />Email<br />
			<input name="emailaddr" 
				maxlength="50" 
				size="30" 
				value="" 
				title="Please enter your email address"/>
			
			<!-- TODO: THIS CHAPTER CODE MUST BE CHANGED BY THE DATA AND WEB TEAMS.
			DONATION_KEY NEEDS TO BE IMPLEMENTED SOMEWHERE IN HERE SO WE CAN USE IT. -->

			<br />Chapter<br />
			<select name='chapter' title='Please select'>
				<option value=''>---Choose One---</option>
				<option value='1'>Atlantis</option>
				<option value='2'>Seatown</option>
				<option value='3'>Wyoming</option>
			</select>
			
			<!-- TODO: THIS PROJECT CODE MUST BE CHANGED BY THE DATA AND WEB TEAMS. -->
			<br />Project<br />
			<select name='project' title='Please select'>
				<option value=''>---Choose One---</option>
        		<option value='1'>firstworldproblems</option>
				<option value='2'>fifthworldproblemz</option>
				<option value='3'>gymtanlaundry</option>
			</select>

            <!-- TODO: NEEDS TO BE TIED TO PROJECT -->
            <input type="hidden" name="description" value="Lorem MF Ipsum" />                  

			<input type="hidden" name="currency" value="USD"/>
            
            <br />How much would you like to donate?<br />
            <input name="donation" 
                maxlength="6"
                size="10"
                value=""
                title="If donating more than ONE MILLION DOLLARS, please contact an ASHA representative." />

            <br />Would you like to make this a recurring donation?
            <input name='recurringCheck' type="checkbox" />
                
            <br />How frequently would you like to donate this amount?<br />
            <select name='subscription' title='Please select'>
    			<option value=''>---Choose One---</option>
    			<option value='DAILY'>Daily</option>
				<option value='WEEKLY'>Weekly</option>
				<option value='SEMI_MONTHLY'>Biweekly</option>
                <option value='MONTHLY'>Monthly</option>
                <option value='EVERY_TWO_MONTHS'>Bimonthly</option>
                <option value='QUARTERLY'>Quarterly</option>
                <option value='YEARLY'>Annually</option>	
			</select>
            
            <br />How many times would you like to have the payment recur?<br />
            <input name='numRecur'
                maxlength="3"
                size="5"
                value="" />
                

			<br />Comments
			<br /><textarea name="comments" wrap="hard" rows="3" cols="25"></textarea>
			<br /><br />
			
			<input type="checkbox" name="join_list" value="1" checked="true"/>
			Check here to find out more about joining an Asha chapter near you<br />
			
			<?php
				//Get the Merchant specific Google Checkout button:
				
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
