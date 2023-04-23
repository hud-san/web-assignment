<?php // <--- do NOT put anything before this PHP tag

include('functions.php');

// get the cookieMessage, this must be done before any HTML is sent to the browser.
$cookieMessage = getCookieMessage();

if(!isset($_COOKIE['ShoppingCart'])) {

	$samePageError = "You have no items in your cart.";
}

else {

	$samePageError="";
}

?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>Shopping Cart</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
</head>
<body>
	<?php include('navbar.php')?>
	<?php
	if(isset($_COOKIE['ShoppingCart'])) {

	$itemCount = sizeof(explode(",", $_COOKIE['ShoppingCart']));

	}

	else {

	$itemCount = 0;

	}

	?>

	<?php
	// does the user have items in the shopping cart?
	if(isset($_COOKIE['ShoppingCart']) && $_COOKIE['ShoppingCart'] != '')
	{
		// the shopping cart cookie contains a list of productIDs separated by commas
		// we need to split this string into an array by exploding it.
		$productID_list = explode(",", $_COOKIE['ShoppingCart']);
		
		// remove any duplicate items from the cart. although this should never happen we 
		// must make absolutely sure because if we don't we might get a primary key violation.
		$fullCart = explode(",",$_COOKIE['ShoppingCart']);
		$productID_list = array_unique($productID_list);
		
		$dbh = connectToDatabase();

		// create a SQL statement to select the product and brand info about a given ProductID
		// this SQL statement will be very similar to the one in ViewProduct.php
		$statement = $dbh->prepare('SELECT Products.ProductID,Products.Price,Products.Description,Brands.BrandName, Brands.Website, Brands.BrandID FROM Products LEFT JOIN Brands
		ON Products.BrandID = Brands.BrandID
		WHERE Products.ProductID = ?
		;');

		$totalPrice = 0;
		$ID = 0;
		// loop over the productIDs that were in the shopping cart.
		foreach($productID_list as $productID)
		{
			// great thing about prepared statements is that we can use them multiple times.
			// bind the first question mark to the productID in the shopping cart.
			$statement->bindValue(1,$productID);
			$statement->execute();
			
			$count = count(array_keys($fullCart,$productID));
			// did we find a match?
			if($row = $statement->fetch(PDO::FETCH_ASSOC))
			{	
				$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8'); 
				$BrandName = htmlspecialchars($row['BrandName'], ENT_QUOTES, 'UTF-8');
				$BrandID = htmlspecialchars($row['BrandID'], ENT_QUOTES, 'UTF-8');  
				$Website = htmlspecialchars($row['Website'], ENT_QUOTES, 'UTF-8');
				$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8');
				$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8');
				echo "<div class ='viewCartWrapper'>";
    			echo "<div class = 'viewCartBox'>";
				echo "<img class = 'productImage' src = 'IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' / >";
				echo "<img class = 'brandImage' src = 'IFU_Assets/BrandPictures/$BrandID.jpg' alt ='' / >";
				echo "<p class = 'productDescription'> $Description </p>";
				echo "<p class = 'quantity'>Item Quantity: $count</p>";
				echo "<a href='".$Website."'>Visit their website here!</a>";
    			echo "</div>";
    			echo "</div>";				
				//TODO Output information about the product. including pictures, description, brand etc.				
				//TODO add the price of this item to the $totalPrice
			}

			
			$totalPrice += intval($Price)*$count;
		}

		
		
		// if we have any error messages echo them now. TODO style this message so that it is noticeable.
		echo "$cookieMessage";
		
		// you are allowed to stop and start the PHP tags so you don't need to use lots of echo statements.
		
	}
	else
	{
		include('ErrorCookie.php');
		$totalPrice = 0;
		// if we have any error messages echo them now. TODO style this message so that it is noticeable.
	}
	?>

	<div class = "cartHeader">
	
	<form action = 'EmptyCart.php' method = 'POST'>
			<input type = 'submit' name = 'EmptyCart' value = 'Empty Shopping Cart' id = 'EmptyCart' />
			</form>

	<p class = "cartInfo"> You have <?php echo $itemCount ?> item/s in your cart for a total of $<?php echo $totalPrice ?> </p>

	<form action = "ProcessOrder.php" method = "POST" >

	<input type="text" name="UserName" placeholder="Enter your username for your order.">
	<input type="submit" value = "Submit order">

	</form>
	
	</div>

	
</body>
</html>
