<?php
    session_start();
    $firstname = "";
    $surname = "";
    $email = "";
    $errors = array();
    //connect to the database
    $db = mysqli_connect('scm.ulster.ac.uk', 'B00684789', 'jGE0zebT')
          or die("Error " . mysqli_error($db));

          mysqli_select_db($db, 'b00684789') or die('db will not open');

    //if the register button is clicked
    if (isset($_POST['register'])) {
        $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
        $surname = mysqli_real_escape_string($db, $_POST['surname']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
        $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
        $status = mysqli_real_escape_string($db, $_POST['status']);
        $occupation = mysqli_real_escape_string($db, $_POST['occupation']);

        //ensure that form fields are filled out properly
        if(empty($firstname)) {
          array_push($errors, "First name is required");
        }
        if(empty($surname)) {
          array_push($errors, "Surname is required");
        }
        if(empty($email)) {
          array_push($errors, "Email is required");
        }
        if(empty($password_1)) {
          array_push($errors, "Password is required");
        }
        if($password_1 != $password_2) {
          array_push($errors, "Passwords do not match");
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          array_push($errors, "Invalid email format");
        }

        $sql = "SELECT * FROM users_cid WHERE email='$email'";
        $result = mysqli_query($db, $sql);
        if(mysqli_num_rows($result) > 0) {
          array_push($errors, "User with that email already exists");
        } else {
        }

        //if there are no errors, save user to database
        if(count($errors) == 0) {
          $password = md5($password_1); //encrypt password before storing it in database
          $sql = "INSERT INTO users_cid (firstname, surname, password, email, status, occupation)
                  VALUES ('$firstname', '$surname', '$password', '$email', '$status', '$occupation')";
          if($db->query($sql) === TRUE) {
          } else {
            console.log($db->error);
            echo "Error: " . $sql . "<br>" . $db->error;
          }
          mysqli_query($db, $sql);
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "You are now logged in";
            header('location: login.php'); //redirect to home page

      }
    }

    //log user in from login page
    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        //ensure that form fields are filled out properly
        if(empty($email)) {
          array_push($errors, "Email is required");
        }
        if(empty($password)) {
          array_push($errors, "Password is required");
        }

        if (count($errors) == 0 ) {
            $password = md5($password); //encrypt password before comparing with that from database
            $query = "SELECT * FROM users_cid WHERE email='$email' AND password='$password'";
            $result = mysqli_query($db, $query);
            if (mysqli_num_rows($result) == 1) {
                //log user in
                $_SESSION['email'] = $email;
                $_SESSION['success'] = "You are now logged in";
                header('location: feed.php'); //redirect to home page
            } else {
                array_push($errors, "Wrong email/password combination");
            }
        }
    }

    //logout
    if(isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['email']);
        header('location: login.php');
    }

    //edit Profile
    if (isset($_POST['changeprofile'])) {
      $status = mysqli_real_escape_string($db, $_POST['status']);
      $occupation = mysqli_real_escape_string($db, $_POST['occupation']);
      $email = $_SESSION['email'];

      $sql = "UPDATE users_cid
              SET status='$status', occupation='$occupation' WHERE email='$email'";
      if($db->query($sql) === TRUE) {
      } else {
        echo "Error: " . $sql . "<br>" . $db->error;
      }
      mysqli_query($db, $sql);
      header("location: profile.php?email=$email");
    }

    //change profile picture
    if (isset($_POST['changeprofilepicture'])) {
      $email = $_SESSION['email'];

      $image = base64_encode(file_get_contents($_FILES['file']['tmp_name']));
      $options = array('http'=>array(
          'method'=>"POST",
          'header'=>"Authorization: Bearer 830e9c5bf83c29f27b7a2b9c1665d8beeb94db35\n".
          "Content-Type: application/x-www-form-urlencoded",
          'content'=>$image
      ));
      $context = stream_context_create($options);
      $imgurURL = "https://api.imgur.com/3/image";

      if($_FILES['file']['size'] > 10240000) {
          die('Image too big, must be 10MB or less');
      }

      $response = file_get_contents($imgurURL, false, $context);
      $response = json_decode($response);
      $profile_image = $response->data->link;

      $sql = "UPDATE users_cid
              SET profile_image='$profile_image' WHERE email='$email'";
      if($db->query($sql) === TRUE) {
      } else {
        echo "Error: " . $sql . "<br>" . $db->error;
      }
      mysqli_query($db, $sql);
      header("location: profile.php?email=$email");
    }

    //posting job advert
    function getAds($db) {
    if(isset($_POST['post'])) {
        $posttitle = mysqli_real_escape_string($db, $_POST['title']);
        $postlocation = mysqli_real_escape_string($db, $_POST['location']);
        $postdescription = mysqli_real_escape_string($db, $_POST['description']);
        $email = $_SESSION['email'];

        if(strlen($postdescription) > 280 || strlen($postdescription) < 1) {
            die('Incorrect length');
        }

        $select = "SELECT * FROM users_cid WHERE email='$email'";
        $result = $db->query($select);
        while($row = $result->fetch_assoc()) {
          $firstname = $row['firstname'];
          $surname = $row['surname'];
        }

        $sql = "INSERT INTO posts (user_email, firstname, surname, title, location, Job_Description, posted_at, likes)
                      VALUES ('$email', '$firstname', '$surname', '$posttitle', '$postlocation', '$postdescription', NOW(), 0)";
                      if($db->query($sql) === TRUE) {
                      } else {
                        echo "Error: " . $sql . "<br>" . $db->error;
                      }


        }
      }
        function displayAds($db) {
              $sql = "SELECT * FROM posts, users_cid WHERE posts.user_email=users.email ORDER BY posted_at";
              $result = $db->query($sql);
              while($row = $result->fetch_assoc()) {
                echo "<div class='comment-box'><p>";
                if($row['profile_image'] == "") {
                  echo "<img width='40' height='40' src='images/default.png'>";
                } else {
                    echo "<img width='40' height='40' src='".$row['profile_image']."'>";
                }
                echo " ". $row['firstname'] . " " . $row['surname'] . "<br><br>";
                echo $row['title'] . " | Location: " . $row['location'] . "<br><br>";
                echo nl2br($row['Job_Description']) . "<br><br>";
                echo $row['posted_at'] . " | " . $row['likes'] . " likes<br><br>";
                echo "<a href='post-ad.php?id=".$row['id']."'>Reply</a>";
                echo "</p></div>";
              }
        }

        function displayUserPosts($db) {
              $sql = "SELECT * FROM posts WHERE email='$email' ORDER BY posted_at";
              $result = $db->query($sql);
              while($row = $result->fetch_assoc()) {
                echo "<div class='comment-box'><p>";
                echo $row['firstname'] . " " . $row['surname'] . "<br><br>";
                echo $row['title'] . " | Location: " . $row['location'] . "<br><br>";
                echo nl2br($row['Job_Description']) . "<br><br>";
                  echo $row['posted_at'] . " | " . $row['likes'] . " likes";
                echo "</p></div>";
              }
        }

        //posting comment
        function setComments($db) {
             if (isset($_POST['commentSubmit'])) {
                    $message = $_POST['message'];
                    $email = $_SESSION['email'];
                    $id = $_GET['id'];
                    $sqlid = "SELECT * FROM posts WHERE id='$id'";

                      $result = $db->query($sqlid);

                          while($row = $result->fetch_assoc()) {
                              $postid = $row['id'];
                          }

                    $select = "SELECT * FROM users WHERE email='$email'";
                    $firstresult = $db->query($select);
                    while($row = $firstresult->fetch_assoc()) {
                      $firstname = $row['firstname'];
                      $surname = $row['surname'];
                    }

                    $sql = "INSERT INTO comments (pid, user_email, firstname, surname, date, message)
                            VALUES ('$postid', '$email', '$firstname', '$surname', NOW(), '$message')";
                    $result = $db->query($sql);
             }
        }

        function getComments($db) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM comments, users_cid WHERE users.email=comments.user_email AND pid='$id' ORDER BY date DESC LIMIT 5";
            $result = $db->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<div id='userReview'>";
                    echo "<div id='innerReview'>";
                    if($row['profile_image'] == "") {
                      echo "<img src='images/default.png'>";
                    } else {
                        echo "<img src='".$row['profile_image']."'>";
                    }
                    echo "<div id='reviewerInformation'>";
                    echo "<h2>". $row['firstname'] . " " . $row['surname'] . "</h2>";
                    echo "<p class='reviewerOccupation'>".$row['date'] . "</p>";
                    echo "</div>";
                    echo "<div id='reviewBody'>";
                    echo "<p>".nl2br($row['message']."</p>");
                    echo "</div>";
                    if($row['user_email'] == $_SESSION['email']) {
                    echo "<form method='post' data-confirm='Are you sure you want to delete this commment?' action='feed.php?id=".$id."'><button type='submit' onclick='confirmDelete()' id='deleteComment' name='deleteComment'>Delete</button></form>";
                  }
                    echo "</p></div></div>";
                    ?>
                    <script>
                        function confirmDelete() {
                            confirm("Are you sure you want to delete comment?");
                        }
                    </script>
                    <?php

                    $cid = $row['cid'];

                    if(isset($_POST['deleteComment'])) {
                        $sqldelete = "DELETE FROM comments WHERE cid='$cid'";
                        if(mysqli_query($db, $sqldelete)) {
                            echo "Comment deleted";
                            header("location: feed.php?id=$id");
                        } else {
                            echo "Error deleting comment: " . $db->error;
                        }
                    }
                }
        }

        //Search Posts
if(isset($_POST['search'])){

            $searchq = $_POST ['search'];
            $searchq = preg_replace("#[^0-9a-z]#i","",$searchq);
            $query = "SELECT * FROM posts WHERE title LIKE '%$searchq%' OR location LIKE '%$searchq%' OR Job_Description LIKE '%$searchq%'";
            $result = mysqli_query($db, $query);
            $output = "";

        if($result->num_rows > 0){
            while($row = $result->fetch_array()){
                $fname = $row['firstname'];
                $sname = $row['surname'];
                $title = $row['title'];
                $location = $row['location'];
                $Job_Description = $row['Job_Description'];
                $posted_at = $row['posted_at'];
                $likes = $row['likes'];

              $output .=  "<div id='userReview'><div id='innerReview'><p>". $row['firstname']. " ". $row['surname'] . "<br><br>Title: ".$row['title']." | Location: " . $row['location'] . "<br><br>" . $row['Job_Description'] . "<br><br>" . $row['posted_at'] . " | " . $row['likes'] . " Likes</p></div></div>";
            }
        } else {
            $output = "There are no results for your search request!";
        }
     }

//Edit post
if(isset($_POST['editpost'])) {
    $id = $_GET['id'];
    $title = mysqli_real_escape_string($db, $_POST['title']);
    $location = mysqli_real_escape_string($db, $_POST['location']);
    $description = mysqli_real_escape_string($db, $_POST['description']);

    $sql = "UPDATE posts
            SET title='$title', location='$location', Job_Description='$description'
            WHERE id='$id'";
    if($db->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $db->error;
    }
    mysqli_query($db, $sql);

  }


//posting review
        function setReviews($db) {
             if (isset($_POST['reviewSubmit'])) {
                    $rating = $_POST['rating'];
                    $message = $_POST['message'];
                    $useremail = $_SESSION['email'];
                    $recieveremail = $_GET['email'];

                    $select = "SELECT * FROM users WHERE email='$useremail'";
                    $firstresult = $db->query($select);
                    while($row = $firstresult->fetch_assoc()) {
                      $firstname = $row['firstname'];
                      $surname = $row['surname'];
                    }

                    $sql = "INSERT INTO reviews (sender_Email, senders_fname, senders_sname, reciever_Email, rating, comment_review)
                            VALUES ('$useremail', '$firstname', '$surname', '$recieveremail', '$rating', '$message')";
                    $result = $db->query($sql);
             }
        }

//Get reviews
    function getReviews($db) {
            $email = $_GET['email'];
            $sql = "SELECT * FROM reviews, users_cid WHERE users.email=reviews.sender_Email AND reciever_Email='$email' LIMIT 5";
            $result = $db->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<div id='userReview'>";
                    echo "<div id='innerReview'>";
                    if($row['profile_image'] == "") {
                      echo "<img src='images/default.png'>";
                    } else {
                        echo "<img src='".$row['profile_image']."'>";
                    }
                    echo "<div id='reviewerInformation'>";
                    echo "<h2>". $row['firstname'] . " " . $row['surname'] . "</h2>";
                    echo "</div>";
                    echo "<div id='reviewBody'>";
                    echo "<p>".nl2br($row['rating']."</p>");
                    echo "<p>".nl2br($row['comment_review']."</p>");
                    echo "</div>";
                    if($row['sender_Email'] == $_SESSION['email']) {
                    echo "<form method='post' data-confirm='Are you sure you want to delete this review?' action='profile.php?email=".$email."'><button type='submit' onclick='confirmDelete()' id='deleteComment' name='deleteReview'>Delete</button></form>";
                  }
                    echo "</p></div></div>";
                    ?>
                    <script>
                        function confirmDelete() {
                            confirm("Are you sure you want to delete comment?");
                        }
                    </script>
                    <?php
                    $id = $row['id'];
                    if(isset($_POST['deleteReview'])) {
                        $sqldelete = "DELETE FROM reviews WHERE id='$id'";
                        if(mysqli_query($db, $sqldelete)) {
                            echo "Comment deleted";
                        } else {
                            echo "Error deleting comment: " . $db->error;
                        }
                    }
                }
        }

//Follow User
    function follow($db) {
        if(isset($_POST['followUser'])) {
            $followeremail = $_SESSION['email'];
            $useremail = $_GET['email'];

            $sql = "INSERT INTO followers (user_email, follower_email)
                    VALUES ('$useremail', '$followeremail')";
            if($db->query($sql) === TRUE) {
              } else {
                echo "Error: " . $sql . "<br>" . $db->error;
              }
            header("location: profile.php?email=$useremail");
        }
    }

//Unfollow user
    function unfollow($db) {
        if(isset($_POST['unfollowUser'])) {
            $followeremail = $_SESSION['email'];
            $useremail = $_GET['email'];

                $sql = "DELETE FROM followers WHERE user_email='$useremail' AND follower_email='$followeremail'";
            if($db->query($sql) === TRUE) {
              } else {
                echo "Error: " . $sql . "<br>" . $db->error;
              }
            header("location: profile.php?email=$useremail");
        }
    }


 ?>
