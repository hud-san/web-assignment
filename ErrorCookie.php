<?php
if(isset($_COOKIE['ShoppingCart'])) {

$itemCount = sizeof(explode(",", $_COOKIE['ShoppingCart']));

}

else {

$itemCount = "no";

}

?>

<div id="errorCookieBar">
<p id="errorMessage"><?php echo getCookieMessage()?> <?php echo $samePageError?></p>
</div>
