<?php // <--- do NOT put anything before this PHP tag
	include('functions.php');
	$samePageError = "";
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>Sign Up Page</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
</head>
<body class ="content">
	<?php include("navbar.php")?>
	<?php include("ErrorCookie.php")?>
	<?php if(isset($_COOKIE['User'])){

		setCookieMessage("You have already signed up.");
		$cookieMessage = getCookieMessage();
		redirect('Homepage.php');
	}
	?>

	<?php if(isset($_GET['Error'])) {

		$error = "Error: Field not complete.";
		
	}
	
	else {
	
		$error = "";
	}
	
	?>

	<form class = 'SignUp' method="post" action="AddNewCustomer.php">
	
	<fieldset>

	<legend><span> Registration </span></legend>

	<label for="username">Username</label><br>
	<input type="text" placeholder="Username" id="UserName" name="UserName"><br> 
    
	<label for="fname">First Name</label><br>
	<input type="text" placeholder="First name" id="FirstName" name="FirstName"><br> 

	<label for="lname">Last Name</label><br>
	<input type="text" placeholder="Last name" id="LastName" name="LastName"><br>

	<label for="Address">Address</label><br>
	<input type="text" placeholder="Address" id="Address" name="Address"><br> 

	<label for="City">City</label><br>
	<input type="text" placeholder="City" id="City" name="City"><br>
	
	<div class = "error"><?php echo $error ?></div>

	<input type="submit" value = "Register now!" class = "btn">

	</fieldset>

	</form>
	
	
</body>
</html>