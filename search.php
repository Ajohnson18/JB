<?php

include('classes/DB.php');
include('classes/Post.php');
include('classes/Login.php');

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JohnsonBoards</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Quicksand">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/divider-text-middle.css">
    <link rel="stylesheet" href="assets/css/Features-Boxed.css">
    <link rel="stylesheet" href="assets/css/Footer-Basic.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Text-Input-1.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Text-Input.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <link rel="stylesheet" href="assets/css/Lightbox-Gallery.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Map-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button1.css">
    <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <?php include('includes/nav.php'); ?>
    <div class="group">
		<form action="search.php" method="post">     
		<input type="text" name="searchbox">
		<span class="highlight"></span>
		<span class="bar"></span>
		<label>Search</label>
		<input type="submit" value="Go" name="search" class="submitButton">
	</div>
    <div style="font-size:22px;text-align:center;margin-top:-87px;font-family:quicksand;">
        <div style="width:200px;display:inline-block;">
            <p style="width:64px;display:inline-block;">Users </p>
            <input type="radio" name="search-users" style="width:20px;display:inline-block;">
        </div>
        <div style="width:200px;display:inline-block;">
            <p style="width:64px;display:inline-block;">Posts </p>
            <input type="radio" name="search-posts" style="width:20px;display:inline-block;">
        </div>
		</form>
        
        
        
        <h1 style="margin-bottom:24px;"><strong>Results</strong> </h1></div>
        
        <?php
				if (isset($_POST['searchbox'])) {
						$tosearch = explode(" ", $_POST['searchbox']);
						if (count($tosearch) == 1) {
								$tosearch = str_split($tosearch[0], 2);
						}
						
						//USERS
						if(isset($_POST['search-users'])) {
							$whereclause = "";
							$paramsarray = array(':username'=>'%'.$_POST['searchbox'].'%');
							for ($i = 0; $i < count($tosearch); $i++) {
									$whereclause .= " OR username LIKE :u$i ";
									$paramsarray[":u$i"] = $tosearch[$i];
							}
							$users = DB::query('SELECT users.username, users.profileimg FROM users WHERE users.username LIKE :username '.$whereclause.'', $paramsarray);
							foreach($users as $user) {	
								echo '<div class="usersearch" style="background: none; border: none; box-shadow: none;"><a href="profile.php?username='.$user['username'].'&commentopen=false&post_id=0"><img src="'.$user['profileimg'].'" style="width:300px;border-radius:100%;">
        						<h1 style="font-family:quicksand;">'.$user['username'].' </h1></a></div>';
							}
							
						} else if(isset($_POST['search-posts'])) {
							$whereclause = "";
						$paramsarray = array(':body'=>'%'.$_POST['searchbox'].'%');
						for ($i = 0; $i < count($tosearch); $i++) {
								if ($i % 2) {
								$whereclause .= " OR body LIKE :p$i ";
								$paramsarray[":p$i"] = $tosearch[$i];
								}
							}
							$posts = DB::query('SELECT * FROM posts WHERE posts.post_body LIKE :body ORDER BY id DESC '.$whereclause.'', $paramsarray);
							if(Login::isLoggedIn()) {
								$username = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['username'];
							}
							foreach($posts as $post) {
								$profileimg = DB::query('SELECT profileimg FROM users WHERE id=:id', array(':id'=>$post['user_id']))[0]['profileimg'];
								$postuser = DB::query('SELECT username FROM users WHERE id=:postid', array(':postid'=>$post['user_id']))[0]['username'];
								echo '<div class="porfile_post" style="width:600px;margin:0 auto;font-family:quicksand;padding:0;padding-left:0;border-left:5px solid lightgrey;">
								<div style="text-align: center;">
									<p style="text-align:left;margin-left:9px;">'.substr($post['posted_at'], 0, 10).' </p>';

								if($post['postimg'] != '')
									echo '<img src="'.$post['postimg'].'" style="width:70%;box-shadow:2px 2px 2px;margin-bottom:33px;margin-left:-17px;">';


								echo '<img src="'.$profileimg.'" style="border-radius:100%;width:68px;float:left;margin:0;margin-left:-36px;margin-top:0;margin-bottom:0px;margin-right:0px;">
									<p style="font-size:22px;width:477px;margin:0 auto;margin-bottom:0;"><a href="profile.php?username='.$postuser.'&commentopen=false&post_id=0"><strong>'.$postuser.'</strong></a>
									- '.$post['post_body'].'</p>
									<p style="font-size:20px;margin-top:12px;margin-bottom:-32px;margin-left:23px;"><i class="glyphicon glyphicon-heart" style="margin-top:14px;margin-bottom:1px;font-size:20px;margin-left:-22px;"></i> '.$post['likes'].' likes <i class="glyphicon glyphicon-eye-open" style="font-size:22px;margin-left:41px;"></i> '.$post['comments'].' comments</p>
								</div><img src="assets/img/wavesep.png" style="width:600px;min-width:0px;height:85px;margin-top:4%;"></div>';
							}
							
						} else {
							echo '<script type=\'text/javascript\'>alert(\'Please select what you would like to search!\');</script>';
						}
							 
				}
			?>
        
        
    
        
    <div class="footer-basic" style="background-color:rgb(241,219,219);">
        <?php include('includes/footer.php'); ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
</body>

</html>