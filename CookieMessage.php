<?php
if(isset($_COOKIE['User']))
{

    $userCookie = $_COOKIE['User'];
}

else {


        $userCookie = "<a href='SignUp.php'>Click here to sign up!</a>";

}

if(isset($_COOKIE['ShoppingCart'])) {

    $itemCount = sizeof(explode(",", $_COOKIE['ShoppingCart']));

}

else {

    $itemCount = "no";

}

?>
<input id="cookieButton" name="cookieButton" type="checkbox" />
<div id="cookieBar">
	<p>Welcome! <?php echo $userCookie?>. <tab> You have <?php echo $itemCount?> item/s in your cart. <?php echo "<a href='ViewCart.php'>Click here to view your cart</a>"?><label id="cookieLabel" for="cookieButton">Hide</label></p>
</div>