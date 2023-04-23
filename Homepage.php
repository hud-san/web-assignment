<?php // <--- do NOT put anything before this PHP tag
	include('functions.php');
	$cookieMessage = getCookieMessage();
	$dbh = connectToDatabase();
	$samePageError = "";
?>

<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
</head>
<body class ="content">
	<?php include "navbar.php"?>
	<span class="errorCookie"><?php include('ErrorCookie.php')?></span>
	<span class = "cookie"><?php
		include('CookieMessage.php')
	?>
	</span>
	
	<div id = "popularProductsWrapper">
	
	<h1>See our most popular products!</h1>

	<div id = "popularProducts">

	

	<?php $statement = $dbh->prepare('SELECT Products.ProductID, Products.Price, Products.Description FROM Products LEFT JOIN OrderProducts 
			ON OrderProducts.ProductID = Products.ProductID
			GROUP BY Products.ProductID 
			ORDER BY COUNT(OrderProducts.OrderID) DESC
			LIMIT 5
	;');

	$statement->execute();
			
			while($row = $statement->fetch(PDO::FETCH_ASSOC))
			{
			$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8'); 
			$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8'); 
			$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8');

			echo "<div class = 'productBox'>";
			echo "<img src = 'IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' / >";
			echo "<p class = 'productPrice'> $$Price </h1>";
			echo "<p class='productDescription'>$Description</h2>";
			echo "<form action='ViewProduct.php?ProductID=$ProductID' method = 'POST'>";
			echo "<button class = 'productButton' type ='submit'>View Product</>";
			echo "</form>";

			echo "</div> \n";
			}
		

		
		$statement->execute();
		?>

	</div>

	</div>
	
</body>
</html>