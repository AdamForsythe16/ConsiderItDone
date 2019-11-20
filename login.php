<?php include('server.php');?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/main.css">
<title>ConsiderItDone</title>
</head>

<body>
		<div id="register">
				<div id="register_content">
						<div id="register_signin">
								<form action="" id="signin" name="signin" method="post">
									<h1>ConsiderIt<span>Done</span>.</h1>
									<?php include('errors.php');?>
										<input type="text" name="email" placeholder="Email" class="formTxtInput"/><br>
										<input type="password" name="password" placeholder="Password" class="formTxtInput"/><br>
										<button type="submit" onclick="" name="login" class="formBtn">Log In</button><br>
										<p id="accountCheck">Not a member yet? <a href="create-account.php">Register Here</a></p>
								</form>
						</div>
				</div>
		</div>
</body>
</html>
