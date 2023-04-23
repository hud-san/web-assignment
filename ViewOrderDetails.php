<!DOCTYPE HTML>
<html>
<head>
	<title>TODO put title here</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
	<meta charset="UTF-8" /> 
</head>
<body>

<?php include('navbar.php')?>

<?php

// did the user provided an OrderID via the URL?
if(isset($_GET['OrderID']))
{
	$UnsafeOrderID = $_GET['OrderID'];
	
	include('functions.php');
	$dbh = connectToDatabase();
	
	// select the order details and customer details. (you need to use an INNER JOIN)
	// but only show the row WHERE the OrderID is equal to $UnsafeOrderID.
	$statement = $dbh->prepare('
		SELECT *,DATETIME(Orders.TimeStamp,"unixepoch") as OrderTime 
		FROM Orders 
		INNER JOIN Customers ON Customers.CustomerID = Orders.CustomerID 
		WHERE OrderID = ? ; 
	');
	$statement->bindValue(1,$UnsafeOrderID);
	$statement->execute();
	
	// did we get any results?
	if($row1 = $statement->fetch(PDO::FETCH_ASSOC))
	{
		// Output the Order Details.
		$FirstName = makeOutputSafe($row1['FirstName']); 
		$LastName = makeOutputSafe($row1['LastName']); 
		$OrderID = makeOutputSafe($row1['OrderID']); 
		$UserName = makeOutputSafe($row1['UserName']);
		$Address = makeOutputSafe($row1['Address']);
		$City = makeOutputSafe($row1['City']);
		$Timestamp = makeOutputSafe($row1['OrderTime']);
		 
		
		// display the OrderID
		echo "<h2>OrderID: $OrderID</h2>";
		
		// its up to you how the data is displayed on the page. I have used a table as an example.
		// the first two are done for you.
		echo "<table>";
		echo "<tr><th>Customer UserName:</th><td>$UserName</td></tr>";
		echo "<tr><th>Customer Name:</th><td>$FirstName $LastName </td></tr>";
		echo "<tr><th>Customer Address:</th><td>$Address, $City </td></tr>";
		echo "<tr><th>Order Submission Date:</th><td>$Timestamp </td></tr>";
		//TODO show the date and time of the order.
		
		echo "</table>";
		
		$statement = $dbh->prepare('SELECT Products.ProductID,Products.Price,Products.Description,Brands.BrandName, Brands.Website FROM Products LEFT JOIN Brands
		ON Products.BrandID = Brands.BrandID
		WHERE Products.ProductID = ?
		;');

		// TODO: select all the products that are in this order (you need to use INNER JOIN)
		// this will involve three tables: OrderProducts, Products and Brands.
		$statement2 = $dbh->prepare('SELECT Products.ProductID, Products.Description, Products.Price, Brands.BrandID,OrderProducts.Quantity FROM OrderProducts
		LEFT JOIN Products ON OrderProducts.ProductID = Products.productid
		LEFT JOIN Brands ON Products.brandid = Brands.BrandID
		WHERE orderid = ?
		GROUP BY orderid,Products.productid	
		
		');
		$statement2->bindValue(1,$UnsafeOrderID);
		$statement2->execute();
		
		$totalPrice = 0;
		echo "<h2>Order Details:</h2>";
		
		// loop over the products in this order. 
		while($row2 = $statement2->fetch(PDO::FETCH_ASSOC))
		{
			//NOTE: pay close attention to the variable names.
			$ProductID = makeOutputSafe($row2['ProductID']); 
			$Description = makeOutputSafe($row2['Description']); 
			$BrandID = makeOutputSafe($row2['BrandID']);
			$Price = makeOutputSafe($row2['Price']);
			$count = makeOutputSafe($row2['Quantity']);
				echo "<div class ='viewOrderWrapper'>";
    			echo "<div class = 'viewOrderBox'>";
				echo "<a class = 'imgLink' href='ViewProduct.php?ProductID=$ProductID'><img src = 'IFU_Assets/ProductPictures/$ProductID.jpg'></a>";
				echo "<img src = 'IFU_Assets/BrandPictures/$BrandID.jpg'></a>";
				echo "<p class = 'orderProductDescription'> $Description </p>";
				echo "<p class = 'quantity'>Item Quantity: $count</p>";
    			echo "</div>";
    			echo "</div>";	

			// TODO show the Products Description, Brand, Price, Picture of the Product and a picture of the Brand.
			// TODO The product Picture must also be a link to ViewProduct.php.
			
			// TODO add the price to the $totalPrice variable.
		}		
		
		//TODO display the $totalPrice .
	}
	else 
	{
		echo "System Error: OrderID not found";
	}
}
else
{
	echo "System Error: OrderID was not provided";
}
?>
</body>
</html>
