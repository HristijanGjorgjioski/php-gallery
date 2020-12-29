<?php require_once("includes/header.php"); ?>

<?php  

	// if($session->is_signed_in()) {
	// 	redirect("index.php");
	// }

	if(isset($_POST['submit_reg'])) {
		$username = trim($_POST['username']); 
		$first_name = trim($_POST['first_name']);
		$last_name = trim($_POST['last_name']);
		$password = trim($_POST['password']); 

		$user_register = new User;
		$user_register->register_user($username, $last_name, $first_name, $password);
		if($user_register) {
			redirect("login.php");
		} else {
			$the_message = "Your password or username are incorrect";
		}


	}	else {
		$username = "";
		$first_name = "";
		$last_name = "";
		$password = "";
		$the_message = "";
	}


?>

<div class="col-md-4 col-md-offset-3">

	<h2 class="text-center white">Register</h2>

	<form id="login-id" action="" method="post">
		
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" name="username" value="<?php echo htmlentities($username); ?>" >
		</div>

		<div class="form-group">
			<label for="first_name">First Name</label>
			<input type="text" class="form-control" name="first_name" value="<?php echo htmlentities($first_name); ?>" >
		</div>

		<div class="form-group">
			<label for="last_name">Last Name</label>
			<input type="text" class="form-control" name="last_name" value="<?php echo htmlentities($last_name); ?>" >
		</div>

		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" class="form-control" name="password">
			
		</div>


		<div class="form-group">
			<input type="submit" name="submit_reg" value="Submit" class="btn btn-primary">
		</div>

		<a href="login.php">Already have an account? Login here</a>

	</form>


</div>

