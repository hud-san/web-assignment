<!DOCTYPE HTML>
<html>
<head>
	<title>Order List</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
	<meta charset="UTF-8" /> 
</head>
<body>

<?php include('navbar.php')?>

<?php
	
	include('functions.php');
	$dbh = connectToDatabase();

	
	$statement = $dbh->prepare('
		SELECT * FROM Orders 
        LEFT JOIN OrderProducts on Orders.OrderID = OrderProducts.OrderID
        ORDER BY Orders.OrderID DESC
	
	');

	$statement->execute();
	
	while($row1 = $statement->fetch(PDO::FETCH_ASSOC))
	{
		

        $OrderID = makeOutputSafe($row1['OrderID']); 
        $statement3 = $dbh->prepare('
		SELECT *,DATETIME(Orders.TimeStamp,"unixepoch") as OrderTime 
		FROM Orders 
		INNER JOIN Customers ON Customers.CustomerID = Orders.CustomerID 
		WHERE OrderID = ? ; 
	');

	$statement3->bindValue(1,$OrderID);
	$statement3->execute();
	
	if($row3 = $statement3->fetch(PDO::FETCH_ASSOC))
	{
	
		$FirstName = makeOutputSafe($row3['FirstName']); 
		$LastName = makeOutputSafe($row3['LastName']); 
		$OrderID = makeOutputSafe($row3['OrderID']); 
		$UserName = makeOutputSafe($row3['UserName']);
		$Address = makeOutputSafe($row3['Address']);
		$City = makeOutputSafe($row3['City']);
		$Timestamp = makeOutputSafe($row3['OrderTime']);
		 
		
		echo "<a class = 'orderID' href='ViewOrderDetails.php?OrderID=$OrderID'><h2>Order $OrderID</h2></a>";
		
	
		echo "<table>";
		echo "<tr><th>Customer UserName:</th><td>$UserName</td></tr>";
		echo "<tr><th>Customer Name:</th><td>$FirstName $LastName </td></tr>";
		echo "<tr><th>Customer Address:</th><td>$Address, $City </td></tr>";
		echo "<tr><th>Order Submission Date:</th><td>$Timestamp </td></tr>";

		
		echo "</table>";
		
		$OrderID = makeOutputSafe($row1['OrderID']);
        $ProductID = makeOutputsafe($row1['ProductID']);
		 
	
		

		$statement2 = $dbh->prepare('SELECT Products.ProductID, Products.Description, Products.Price, Brands.BrandID,OrderProducts.Quantity FROM OrderProducts
		LEFT JOIN Products ON OrderProducts.ProductID = Products.productid
		LEFT JOIN Brands ON Products.brandid = Brands.BrandID
		WHERE orderid = ?
		GROUP BY orderid,Products.productid	
		
		');

		$statement2->bindValue(1,$OrderID);
		$statement2->execute();
        $TotalCost = 0;
    }
		echo "<h2>Order Details:</h2>";
		
		
		while($row2 = $statement2->fetch(PDO::FETCH_ASSOC))
		{
			
			$ProductID = makeOutputSafe($row2['ProductID']); 
			$Description = makeOutputSafe($row2['Description']); 
			$BrandID = makeOutputSafe($row2['BrandID']);
			$Price = makeOutputSafe($row2['Price']);
            $Quantity = makeOutputSafe($row2['Quantity']);
            $TotalCost += intval($Quantity)*intval($Price);
				echo "<div class ='viewOrderWrapper'>";
    			echo "<div class = 'viewOrderBox'>";
				echo "<a class = 'imgLink' href='ViewProduct.php?ProductID=$ProductID'><img src = 'IFU_Assets/ProductPictures/$ProductID.jpg'></a>";
				echo "<p class = 'orderProductDescription'> $Description </p>";
    			echo "</div>";
                echo "<p class = 'Quantity'> Item Quantity: $Quantity </p>";
                echo "<img class = 'brandImage' src = 'IFU_Assets/BrandPictures/$BrandID.jpg' alt ='' / >";
    			echo "</div>";	
        }

        echo "<p class = 'Quantity'> Total Cost: $$TotalCost </p>";
		
		//TODO display the $totalPrice .
	}
	
?>
</body>
</html>
