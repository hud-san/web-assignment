
<?php // <--- do NOT put anything before this PHP tag

include('functions.php');

// did they click the empty cart button?
if(isset($_POST['EmptyCart']))
{
	deleteCookie('ShoppingCart');
	setCookieMessage("Your cart has been emptied.");
	redirect("ViewCart.php");
}
else
{
	echo "System Error: button not pressed, make sure the submit button is named EmptyCart and the method is post";
}
?>