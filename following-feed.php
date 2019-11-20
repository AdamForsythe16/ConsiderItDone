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
						<input type="text" id="searchFormTxt" name="search" placeholder="Search...">
						<input type="submit" value="Search" id="searchFormSubmit">
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
									<h2> Post Job Advert </h2>
									<form id="postAdForm" action='<?php getAds($db) ?>' method="post">
											<input type="text" name="title" placeholder="Title" class="formInput"><br>
											<input type="text" name="location" placeholder="Location" class="formInput"><br>
											<textarea name="description" class="formInput" placeholder="Description"></textarea><br>
											<input type="submit" name="post" value="Post" id="submitAd">
									</form>
							</div>
						</div>
						<div id="profile_Reviews">
							<?php
								if(isset($_GET['id'])) {
									$id = $_GET['id'];
									$sqlposts = "SELECT * FROM posts, users WHERE posts.user_email=users.email AND id='$id' ORDER BY posted_at DESC LIMIT 5";
									$result1 = $db->query($sqlposts);
									if ($result1->num_rows > 0) {
											while($row = $result1->fetch_assoc()) {
													echo '<div id="userReview">';
													echo '<div id="innerReview">';
													if($row['profile_image'] == "") {
	                            echo '<a href="profile.php?email='.$row['user_email'].'"><img src="img/default.png"></a>';
	                        } else {
	                            echo '<a href="profile.php?email='.$row['user_email'].'"><img src="'.$row['profile_image'].'"></a>';
	                        }
													echo '<div id="reviewerInformation">';
													echo "<a href='profile.php'><h2>".$row['firstname']." ".$row['surname']."</h2></a>";
													if($row['status'] == "Worker") {
	                            echo '<p class="reviewerOccupation">'.$row['occupation'].'</p>';
	                        }
													echo '<p id="reviewerOccupation">'.$row['posted_at'].'</p>';
													echo '</div>';
													echo '<div id="reviewBody">';
													echo '<h3>Title: '.$row['title'].' | Location: '.$row['location'].'</h3>';
													echo '<p>'.$row['Job_Description'].'</p>';
													echo '</div>';
													echo '</div>';
												  echo '</div>';
											}

											echo '<h2 class="postComments">Post Comment</h2>';
											echo "<form method='POST'action='".setComments($db)."'>";
											echo "<textarea name='message'></textarea><br>";
											echo "<button name='commentSubmit' id='commentSubmit' type='submit'>Comment</button>";
											echo "</form>";
											echo getComments($db);


										}
									} else {
                    $email = $_SESSION['email'];
										$sqlposts = "SELECT * FROM posts, users, followers WHERE posts.user_email=users.email AND users.email=followers.follower_email AND follower_email='$email' ORDER BY posted_at DESC LIMIT 5";
										$result1 = $db->query($sqlposts);
										if ($result1->num_rows > 0) {
												while($row = $result1->fetch_assoc()) {
														echo '<div id="userReview">';
														echo '<div id="innerReview">';
														if($row['profile_image'] == "") {
																echo '<img src="img/default.png">';
														} else {
																echo '<img src="'.$row['profile_image'].'">';
														}
														echo '<div id="reviewerInformation">';
														echo '<h2>'.$row['firstname'].' '.$row['surname'].'</h2>';
														if($row['status'] == "Worker") {
																echo '<p class="reviewerOccupation">'.$row['occupation'].'</p>';
														}
														echo '<p id="reviewerOccupation">'.$row['posted_at'].'</p>';
														echo '</div>';
														echo '<div id="reviewBody">';
														echo '<h3>Title: '.$row['title'].' | Location: '.$row['location'].'</h3>';
														echo '<p>'.$row['Job_Description'].'</p>';
														echo '<div id="jobAdBottom">';
														echo '<a href="feed.php?id='.$row['id'].'"<h3>Reply</h3></a>';

														echo '</div>';
														echo '</div>';
														echo '</div>';
														echo '</div>';

												}
										}
									}
									?>
							</div>

					</div>


</body>
</html>
