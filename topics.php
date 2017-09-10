<?php
include('./classes/DB.php');
include('./classes/Post.php');
include('./classes/Image.php');
include('./classes/Login.php');
if (isset($_GET['topic'])) {
        if (DB::query("SELECT topics FROM posts WHERE FIND_IN_SET(:topic, topics)", array(':topic'=>$_GET['topic']))) {
                $posts = DB::query("SELECT * FROM posts WHERE FIND_IN_SET(:topic, topics) ORDER BY id DESC", array(':topic'=>$_GET['topic']));
                echo '<br /><br /><br /><br /><br /> <h1 style="text-align:center;font-family:quicksand;">Topics</h1>';
				foreach($posts as $post) {
					$username = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$post['user_id']))[0]['username'];
					$profileimg = DB::query('SELECT profileimg FROM users WHERE id=:userid', array(':userid'=>$post['user_id']))[0]['profileimg'];
					echo '<div class="porfile_post" style="width:600px;margin:0 auto;text-align:center;font-family:quicksand;padding:0;padding-left:0;border-left:5px solid lightgrey;">
					<div>
						<p style="text-align:left;margin-left:9px;">'.substr($post['posted_at'], 0, 10).' </p>';

					if($post['postimg'] != '')
						echo '<img src="'.$post['postimg'].'" style="width:70%;box-shadow:2px 2px 2px;margin-bottom:33px;margin-left:-17px;">';


					echo '<img src="'.$profileimg.'" style="border-radius:100%;width:68px;float:left;margin:0;margin-left:-36px;margin-top:0;margin-bottom:0px;margin-right:0px;">
						<p style="font-size:22px;width:477px;margin:0 auto;margin-bottom:0;">
						- '.Post::link_add($post['post_body']).'</p>
						<p style="font-size:20px;margin-top:72px;margin-bottom:-52px;margin-left:23px;">
						<form class="buttonp" method="post" action="profile.php?post_id='.$post['id'].'&username='.$username.'&commentopen=false" style="width:20px; display:inline-block;">
							<input  type="submit" name="like" style="display:inline-block; width:10px; border: none; background: url(assets/img/like-button.png); background-size:contain; background-repeat: no-repeat; height: 10px;" value="">
						</form>
						'.$post['likes'].' likes';
					echo '<input type="button" style="display:inline-block; width:20px; margin-left:20px; border: none; background: url(assets/img/comment-icon.png); background-size:contain; background-repeat: no-repeat; height: 15px;">
					</form>
					'.$post['comments'].' comments</p>';
					echo '</div><img src="assets/img/wavesep.png" style="width:600px;min-width:0px;height:85px;margin-top:4%;"></div>';
                }
        }
}
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
       <br />
       <br />
       <br />
       <br />
    <div class="footer-basic" style="background-color:rgb(241,219,219);">
        <?php include('includes/footer.php'); ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
</body>

</html>