<?php include('server.php');
    //if user is not logged in, they cannot access this page
    IF(empty($_SESSION['email'])) {
        header('location: login.php');

    }
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/main.css">
<title> ConsiderItDone - Profile </title>
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

					<div id="profile_content">
							<div id="profile_header">
								<?php if (isset($_GET["email"])) {

					        $email = $_GET['email'];
					        $select = "SELECT * FROM users WHERE email='$email'";

					          $result = $db->query($select);

					          while($row = $result->fetch_assoc()) {
											if($row['profile_image'] == "") {
													echo '<div class="profileimg"><img src="img/default.png"></div>';
											} else {
													echo '<div class="profileimg"><img src="'.$row['profile_image'].'"></div>';
											}
											echo '<h2>'.$row['firstname'].' '.$row['surname'].'</h2>';
											echo '<h3>'.$row['email'].'</h3>';
											echo '<h4>Followers</h4>';
											echo '<h4>Following</h4>';
										}
									}?>
							</div>
							<div id="portfolio">
									<h2>Portfolio</h2>
									<div id="portfolio_Images">
										<div id="imgContainer">
											<img src="img/default.png">
										</div>
										<div id="imgContainer">
											<img src="img/default.png">
										</div>
										<div id="imgContainer">
											<img src="img/default.png">
										</div>
										<div id="imgContainer">
											<img src="img/default.png">
										</div>
										<div id="imgContainer">
											<img src="img/default.png">
										</div>
										<div id="imgContainer">
											<img src="img/default.png">
										</div>
									</div>
							</div>

						</div>
						<div id="profile_Reviews">
                            	<div id="userReview">
										<div id="innerReview">
                            <h2 class="postComments" style="color: black;">Post Review</h2>
                            <form method='POST'action='".setComments($db)."'>
                                <label> <p style="display: inline;">Rating </p></label>
                                  <select name="" class="formTxtInput" style="width: 10%;">
                                 <option value="one" name="1">1</option>
                                 <option value="two" name="2">2</option>
                                 <option value="three" name="3">3</option>
                                 <option value="four" name="4">4</option>
                                 <option value="five" name="5">5</option>
                                 </select>
                                <br>
                            <textarea name='message'></textarea><br>
                                <br>
                            <button name='reviewSubmit' id='commentSubmit' type='submit'>Send</button>
                            </form>
                                    </div>
                            </div>
								<div id="userReview">
										<div id="innerReview">
												<img src="img/default.png">
												<div id="reviewerInformation">
														<h2>John Smith</h2>
														<p id="reviewerOccupation">Occupation</p>
														<p id="reviewerOccupation">Date Posted</p>
                                                   
											     </div>
                                                            
											<div id="reviewBody">
													<h3>Rating: 4/5</h3>
													<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>
											</div>
										</div>
								</div>
								<div id="userReview">
										<div id="innerReview">
												<img src="img/default.png">
												<div id="reviewerInformation">
														<h2>John Smith</h2>
														<p id="reviewerOccupation">Occupation</p>
														<p id="reviewerOccupation">Date Posted</p>
											</div>
											<div id="reviewBody">
													<h3>Rating: 4/5</h3>
													<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>
											</div>
										</div>
								</div>
							</div>

					</div>


</body>
</html>
