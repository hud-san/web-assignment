<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>Products</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
</head>
<body>
	<?php include('navbar.php') ?>
	<?php 

		include('functions.php');
		
		if(isset($_GET['search']))
		{
			$searchString = $_GET['search'];
		}

		else
		{
			$searchString = "";
		}
		
		
		
		
	
		if(isset($_GET['page']))
		{
			$currentPage = intval($_GET['page']);
		}
		else
		{
			$currentPage = 0;
		}

		$safeSearchString = htmlspecialchars($searchString, ENT_QUOTES,"UTF-8"); 
		$SqlSearchString = "%$safeSearchString%";
		$nextPage =  $currentPage + 1; 

		echo "<div class = pagenav>";
		echo "<form>";
		echo "<input name = 'page' type = 'text' value = '$currentPage' />";
		echo "<button type = 'submit'>Go to page</>";
		echo "</form>";
		echo "</div>";

		echo "<div class = navbutton>";
		echo "<a href = 'ProductList.php?page=$nextPage&search=$safeSearchString'>Next Page</a>";
		$previousPage =  $currentPage - 1;
		if ($previousPage >= 0)
		{
			echo "<a href = 'ProductList.php?page=$previousPage&search=$safeSearchString'>Previous Page</a>";
		}
		echo "</div>";

		echo "<div class = 'productSearchBox'>";
		echo "<form>";
		echo "<input name = 'search' class='productSearch' placeholder='Search for a product!' type = 'text' value = '$safeSearchString' />";
		echo "</form>";
		echo "</div>";


		?>

		<div class = "wrapper">
		<?php
		$dbh = connectToDatabase();
			
		$statement = $dbh->prepare('SELECT Products.ProductID, Products.Price, Products.Description FROM Products LEFT JOIN OrderProducts 
			ON OrderProducts.ProductID = Products.ProductID
			WHERE Products.Description LIKE ? 
			GROUP BY Products.ProductID 
			ORDER BY COUNT(OrderProducts.OrderID) DESC
			LIMIT 10 
			OFFSET ? * 10
			;');  
		
		$statement->bindValue(1,$SqlSearchString); 
		
		$statement->bindValue(2,$currentPage);
		
		$statement->execute();

		while($row = $statement->fetch(PDO::FETCH_ASSOC))
		{
			$flag = 1;
			$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8'); 
			$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8'); 
			$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8');
			$array = explode(" ",$Description);

			
			echo "<div class = 'productBox'>";
			echo "<img src = 'IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' / >";
			echo "<p class = 'productPrice'> $$Price </h1>";
			echo "<p class='productDescription'>$Description</h2>";
			echo "<form action='ViewProduct.php?ProductID=$ProductID' method = 'POST'>";
			echo "<button class = 'productButton' type ='submit'>View Product</>";
			echo "</form>";
			
			echo "</div> \n";			
		}
	?>
	</div>
	<?php include('CookieMessage.php')?>

</body>
</html>