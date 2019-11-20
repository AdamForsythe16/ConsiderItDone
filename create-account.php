<!-- <!DOCTYPE html> -->
<?php include('server.php');?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/main.css">
<title>Login</title>
</head>
<body>
	<script type="text/javascript">
	function yesnoCheck () {
		if (document.getElementById("yesCheck").selected) {
			document.getElementById("ifYes").style.display = "block";
		} else {
			document.getElementById("ifYes").style.display = "none";
		}
	}
</script>
		<div id="register">
				<div id="register_content">
						<div id="register_signin">
								<form action="create-account.php" id="registerform" method="post">
										<h1>ConsiderIt<span>Done</span>.</h1>
										<?php include("errors.php");?>
										<input type="text" name="firstname" placeholder="First Name" class="formTxtInput" value="<?php echo $firstname; ?>"/><br>
										<input type="text" name="surname" placeholder="Surname" class="formTxtInput" value="<?php echo $surname;?>"/><br>
										<input type="email" name="email" placeholder="Email" class="formTxtInput" value="<?php echo $email;?>"/><br>
										<input type="password" name="password_1" placeholder="Password" class="formTxtInput"/><br>
										<input type="password" name="password_2" placeholder="Confirm Password" class="formTxtInput"/><br>
										<select onchange="yesnoCheck()"name="status" class="formTxtInput">
												<option id="noCheck" value="Customer">Customer</option>
												<option id="yesCheck" value="Worker">Worker</option>
										</select>
										<input type="text" class="formTxtInput" id="ifYes" style="display: none;" name="occupation" placeholder="Occupation">
										<br>
										<input type="submit" class="formBtn" onclick="" name="register">
										<p id="accountCheck">Already have an account? <a href="login.php">Sign In</a></p>
								</form>
						</div>
				</div>
		</div>



</body>
</html>
