<?php include('server.php');
    //if user is not logged in, they cannot access this page
    IF(empty($_SESSION['email'])) {
        header('location: login.php');

    }
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/main.css">
<title>Your Feed</title>
</head>
<body>
		<script type="text/javascript">
				function dropDown() {
						document.getElementById("myDropdown").classList.toggle("show");
				}

				window.onclick = function(event) {
						if(!event.target.matches('.dropbtn')) {
								var dropdowns = document.getElementsByClassName("dropdown-content");
								var i;
								for(i = 0; i<dropdowns.length; i++) {
										var  openDropdown = dropdowns[i];
										if(openDropdown.classList.contains('show')) {
												openDropdown.classList.remove('show');
										}
								}
						}
				}
		</script>
		<div id="profile">
			<header>
				<div id="innerContent">
				<div id="logo">
						<a href="feed.php"><h1>ConsiderIt<span id="spanLogo">Done</span>.</h1></a>
				</div>
				<div id="search">
				<form id="searchForm" action="search-results.php" method="post">
						<input type="text" name="search" placeholder="Search..." id="searchFormTxt">
						<input type="submit" value="search" id="searchFormSubmit">
				</form>
				</div>
				<div class="nav">
						<ul>
							<li><a href="feed.php">Job Feed</a></li>
							<!--<li><a href="public.php">Public</a></li>
							<li><a href="contact.php">Contact</a></li>-->
						</ul>
					</div>
					<div id="account">
							<?php if (isset($_SESSION["email"])) {
			          $emailsession = $_SESSION['email'];
							}?>
							<img src="img/account.png" onclick="dropDown()" class="dropbtn">
							<div id="myDropdown" class="dropdown-content">
									<?php echo '<a href="profile.php?email='.$emailsession.'">Profile</a>';?>
									<a href="edit-profile.php">Edit Profile</a>
									<a href="edit-profilepicture.php">Change Profile Picture</a>
									<a href="login.php">Logout</a>
							</div>
					</div>
				</div>
				</header>

					<div id="feed_left">
							<div id="postJobAd">
									<h2>Edit Profile</h2>
									<form id="postAdForm" action='edit-profilepicture.php' method="post" enctype="multipart/form-data">
                    <input type="file" name="file" class="formTxtInput"><br>
											<input type="submit" name="changeprofilepicture" value="Confirm" id="submitAd">
									</form>
							</div>
						</div>

					</div>


</body>
</html>
