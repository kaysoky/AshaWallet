AshaWallet
==========

Team: O(n) && 0xFF 
10/20/2012

Google Wallet integration - Asha for Education

The files are contained in the following folders:
* /Super Simple
	* Contains a single HTML file with all the functionality a donate button needs.
	* Nothing on the server's side.
* /WalletLooper
	* donation_page.php = Initializes a format-less page with all the required forms (and some validation) to make a donation 
	* submit_donation.php = Checks information from donation_page.php and redirects user towards Google Wallet (if successful).  Can be used to save some data into the DB. 
	* notification.php = Responds to a notification from Google Wallet each time a transaction is made.
	(Just prints the data to a file, but it can be used to save some data in the DB.)
	(Other files are dependencies)