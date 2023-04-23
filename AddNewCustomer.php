<?php // <--- do NOT put anything before this PHP tag

// this php file will have no HTML

include('functions.php'); //Changed condition to use empty, as isset returns true  for empty string, user cannot have an empty string for any field.

if(empty($_POST['UserName']))
{
	redirect("SignUp.php?Error=1");
}
elseif(empty($_POST['FirstName']))
{
	redirect("SignUp.php?Error=1");
}
elseif(empty($_POST['LastName']))
{
	redirect("SignUp.php?Error=1");
}
elseif(empty($_POST['Address']))
{
	redirect("SignUp.php?Error=1");
}
elseif(empty($_POST['City']))
{
	redirect("SignUp.php?Error=1");
}
else
{
	$dbh = connectToDatabase();
	
	//TODO trim all 5 inputs, to make sure they have no extra spaces.
	$UserName = trim($_POST['UserName']);
	$FirstName = trim($_POST['FirstName']);
	$LastName = trim($_POST['LastName']);
	$Address = trim($_POST['Address']);
	$City= trim($_POST['City']);


	// lets check to see if the user name is taken, COLLATE NOCASE tells SQLite to do a case insensitive match.
	$statement = $dbh->prepare('SELECT * FROM Customers WHERE UserName = ? COLLATE NOCASE; ');
	$statement->bindValue(1,  $UserName  );
	$statement->execute();
		
	// we found a match, so inform the user that they cant use the user-name.
	if($row2 = $statement->fetch(PDO::FETCH_ASSOC))
	{
		setCookieMessage("The UserName: '$UserName' is already taken.");
		redirect("SignUp.php");
	}
	else
	{		
		// add the new customer to the customers table.
		// TODO insert the new customer and their details into the Customers table.
		// NOTE: you must NOT provide the customerID, the database will generate one for you.
		$statement2 = $dbh->prepare('INSERT INTO 
		Customers (UserName, FirstName, LastName, Address, City) VALUES (?, ?, ?, ?, ?);');
			
		
		// TODO: bind the 5 variables to the question marks. the first one is done for you.
		$statement2->bindValue(1, $UserName );
		$statement2->bindValue(2, $FirstName);
		$statement2->bindValue(3, $LastName );
		$statement2->bindValue(4, $Address);
		$statement2->bindValue(5, $City);

		
		
		$statement2->execute();
		
		setcookie('User',$UserName);

		setCookieMessage("Welcome $FirstName, you can now buy some products!");
		redirect("Homepage.php");		
	}
}
