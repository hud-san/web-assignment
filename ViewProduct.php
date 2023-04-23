<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="shopstyle.css" />
</head>

<body>
<?php // <--- do NOT put anything before this PHP tag
	include('functions.php');
    include('navbar.php');
	$cookieMessage = getCookieMessage();
?>    
</body>

<?php

    $error = "";
    $success = "";
    $ProductID = $_GET['ProductID'];

    $dbh = connectToDatabase();
			
		$statement = $dbh->prepare('SELECT Products.Price, Products.Description, Brands.BrandID FROM Products LEFT JOIN Brands
			ON Products.BrandID = Brands.BrandID
			WHERE Products.ProductID = ?
			;');
            
        $statement->bindValue(1,$ProductID);
        $statement->execute();
        while($row = $statement->fetch(PDO::FETCH_ASSOC))
		{
	    $flag = 1;
        $Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8'); 
		$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8');
        $BrandID = htmlspecialchars($row['BrandID'], ENT_QUOTES, 'UTF-8');
        }

        if(isset($_GET['Error'])&& $_GET['Error']=="1") {

            $error = "Error: This product is already added to your cart.";
            $success = "";
        }

        else if(isset($_GET['Error']) && $_GET['Error']=="0") {

            $error = "";
            $success= "Successfully added product to your cart.";
        }

        

    echo "<div class ='viewProductWrapper'>";
    echo "<div class = 'viewProductBox'>";
	echo "<img class = 'productImage' src = 'IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' / >";
    echo "<img class = 'brandImage' src = 'IFU_Assets/BrandPictures/$BrandID.jpg' alt ='' / >";
	echo "<p class = 'productPrice'> $$Price </h1>";
	echo "<p class='productDescription'>$Description</h2>";
    echo "<p class='productID'>Product ID: $ProductID</h1>";
    echo "<p class = 'error'>$error</p>";
    echo "<p class = 'success'>$success</p>";
    echo "<form method = 'POST'>";
    echo "<button name='BuyButton' formaction='AddToCart.php?ProductID=$ProductID'>Add To Cart</button>";
	echo "</form>";
    echo "</div>";
    echo "</div>";	

?>

</html>