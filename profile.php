<!DOCTYPE html>
<html style="font-family:quicksand;padding:0;">

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
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="assets/css/main.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
</head>
	

	<?php

	include('classes/DB.php');
	include('classes/Login.php');
	include('classes/Post.php');
	include('classes/Image.php');
	include('classes/Comment.php');
		
		$isFollowing = false;
		$profileimg = DB::query('SELECT profileimg FROM users WHERE username=:userid', array(':userid'=>$_GET['username']))[0]['profileimg'];
		$bannerimg = DB::query('SELECT bannerimg FROM users WHERE username=:userid', array(':userid'=>$_GET['username']))[0]['bannerimg'];
		if (isset($_GET['username'])) {
				if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
					$userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
					$followerid = Login::isLoggedIn();

					if (isset($_POST['post'])) {
						 if ($_FILES['postimg']['size'] == 0) {
							Post::createPost($_POST['postbody'], Login::isLoggedIn(), $userid);
						 } else {
							$postid = Post::createImgPost($_POST['postbody'], Login::isLoggedIn(), $userid);
							Image::uploadImage('postimg', "UPDATE posts SET postimg=:postimg WHERE id=:postid", array(':postid'=>$postid));
					   }
					}
					
					if (isset($_POST['follow'])) {
							if ($userid != $followerid) {
									if (!DB::query('SELECT * FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
											DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
									} else {
									}
									$isFollowing = True;
							}
					}
					
					if (isset($_POST['unfollow'])) {
							if ($userid != $followerid) {
									if (DB::query('SELECT * FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
											DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
									}
									$isFollowing = False;
							}
					}
					
					if (isset($_POST['comment'])) {
						$comment = $_POST['commentbody'];
						if(strlen($comment) < 1 || strlen($comment) > 160) {
							echo '<script type=\'text/javascript\'>alert(\'Comment is too long or too short!\');</script>';
						} else {
							Comment::createComment($comment, $_GET['post_id'], $followerid);
						}
					}
					
					if(isset($_POST['delete-comment'])) {
						if(DB::query('SELECT comments.post_id FROM comments WHERE id=:postid', array(':postid'=>$_GET['id']))) {
							$postid = DB::query('SELECT comments.post_id FROM comments WHERE id=:postid', array(':postid'=>$_GET['id']))[0]['id'];
							DB::query('UPDATE posts SET comments=comments-1 WHERE id=:postid', array(':postid'=>$_GET['post_id']));
							DB::query('DELETE FROM comments WHERE id=:postid AND user_id=:userid', array(':postid'=>$_GET['id'], ':userid'=>Login::isLoggedIn()));
							header("Location: profile.php?username=".$_GET['username']."&post_id=".$_GET['post_id']."");
							exit();
						}
					}						
				}
		}
?>




<body style="padding:0;">
    <?php include('includes/nav.php'); ?>
    <?php
		$notifications = DB::query('SELECT notifications FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['notifications'];
		if($notifications != 0) {
			echo '<div id="notificationbanner">
					<p style="text-align: center; color: white;">You Have ' .$notifications. ' Notification(s)! Click <a href="notify.php" style="color: lightgrey;" >HERE </a>To See Them!</p>
				  </div>';
		}
	?>
    <section style="text-align:center;padding:0;"><img src="<?php echo $bannerimg; ?>" style="margin-top:70px;width:100%;max-width:1500px;max-height:500px;">
        <div style="margin-top:-73px;position:absolute;width:100%;">
            <ul id="profilenums" style="list-style:none;margin:0;margin-bottom:0;height:73px;width:100%;margin-right:0;margin-left:0;background-color:rgba(0,0,0,0.7);margin-top:0px;">
                <li><img src="<?php echo $profileimg; ?>" id="profimge"></li>
                <li style="margin-top:0;">
                <?php
					if(DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
						$pageid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
						$postsnum = DB::query('SELECT * FROM posts WHERE user_id=:userid', array(':userid'=>$pageid));
						if($postsnum != null) {
							echo count($postsnum) . " Posts"; 
						} else {
							echo "0 Posts";
						}
						
				}
				?>
                 </li>
                <li>
                <?php
					if(DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
						$pageid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
						$follownum = DB::query('SELECT * FROM followers WHERE user_id=:userid', array(':userid'=>$pageid)); 
						if($follownum != null) {
							echo count($follownum);
							echo " Followers";
						} else {
							echo "0 Followers";
						}
						
					}
				?>
                </li>
                <li>
                <?php
					if(DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
						$pageid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
						$followingnum = DB::query('SELECT * FROM followers WHERE follower_id=:userid', array(':userid'=>$pageid)); 
						if($followingnum != null) {
							echo count($followingnum);
							echo " Following";
						} else {
							echo "0 Following";
						}
						
					}
				?>
                </li>
            </ul>
        </div>
        <div id="bcircle" style="margin: 0 auto;margin-top: 20px; border-radius: 100%; width: 45vw; height: 45vw; background: #F5F5F5; "><img src="<?php echo $profileimg; ?>" style="margin-top: 2.5vw; border-radius: 100%; width: 40vw; height: 40vw;"></div>
        <h1 style="margin-top:33px;text-align:center;width:100%;margin-left:0;margin-bottom:28px;font-family:quicksand;">
        <?php
			$first = DB::query('SELECT first_name FROM users WHERE username=:userid', array(':userid'=>$_GET['username']))[0]['first_name'];
			$last = DB::query('SELECT last_name FROM users WHERE username=:userid', array(':userid'=>$_GET['username']))[0]['last_name'];
			if($first != '' && $last != '') {
				echo ($first . " " . $last);
			} else {
				echo $_GET['username']; 
			}
			
			$id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
			if(Login::isLoggedIn() == $id) {
				echo '<br /><a href="my-account.php" style="margin-left:20px; transition:0.3s; text-decoration: none;">Edit Profile</a>';
			} else {
				echo '<form action="profile.php?username='.$_GET['username'].'&commentopen=false&post_id=0" method="post">';
				if(!DB::query('SELECT * FROM followers WHERE follower_id=:follower AND user_id=:userid', array(':follower'=>Login::isLoggedIn(), ':userid'=>$pageid))) {
					echo '<button class="btn btn-default" type="submit" style="margin-top:-4px;width:142px;min-width:0;height:38px;font-family:quicksand;transition:0.3s;margin-right:0;padding:0;padding-left:0;font-size:22px;padding-bottom:0;padding-right:0;padding-top:0;" name="follow">Follow </button>';
				} else {
					echo '<button class="btn btn-default" type="submit" style="margin-top:-4px;width:142px;min-width:0;height:38px;font-family:quicksand;transition:0.3s;margin-right:0;padding:0;padding-left:0;font-size:22px;padding-bottom:0;padding-right:0;padding-top:0;" name="unfollow">Unfollow </button>';
				}
				echo '</form>';
				
			}
			
		?> 
        </h1>
        <div style="text-align:center;">
            <p style="font-size:20px;text-align:center;color:rgb(123,123,123);"><i class="glyphicon glyphicon-home" style="width:21px;"></i>
            <?php
				$town = DB::query('SELECT town FROM users WHERE username=:userid', array(':userid'=>$_GET['username']))[0]['town'];
				if($town != '') {
					echo $town;
				} else {
					echo "Unknown";
				}
			?>
	     <br />
             <i class="glyphicon glyphicon-heart" style=""></i>
             <?php
				$break = DB::query('SELECT fbreak FROM users WHERE username=:userid', array(':userid'=>$_GET['username']))[0]['fbreak'];
				if($break != '') {
					echo $break;
				} else {
					echo "Unknown";
				}
			?>
             </p>
            <p style="margin:0 auto;margin-top:23px;font-size:19px;font-family:quicksand;color:rgb(125,117,117);" id="descrip">
            - <?php
				$desc = DB::query('SELECT description FROM users WHERE username=:userid', array(':userid'=>$_GET['username']))[0]['description'];
				if($desc != '') {
					echo $desc;
				} else {
					echo "";
				}
			?></p>
        </div>
    </section>
    <section style="text-align:center;margin-top:28px;margin-bottom:46px;">
    <?php
	$pageid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
	if(Login::isLoggedIn() == $pageid) {
		echo '<div class="group"> 
    	<form action="profile.php?username=' .$_GET['username']. '&post_id=0" id="poster" method="post" enctype="multipart/form-data">     
			<input type="text" name="postbody">
			<span class="highlight"></span>
			<span class="bar"></span>
			<label>Post something...</label>
			<input type="submit" name="post" class="submitButton">
			<input type="file" name="postimg" style="width:600px;margin:0 auto;border-bottom:none;min-width:0px;font-size:12px;margin-bottom:48px;">
		</form>	
	</div>';
	} ?>
           
        <div class="profile_post" style="margin:0 auto;font-family:quicksand;padding:0;padding-left:0;text-align:center;">
       		
		</div>
      
      <div class="modal fade" role="dialog" tabindex="-1" style="padding-top:100px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Comments</h4></div>
                <div class="modal-body" style="max-height=400px; overflow-y: automatic;">
                </div>
                
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
            
            
    </section>
    <div class="footer-basic" style="background-color:rgb(241,219,219);">
        <?php include('includes/footer.php'); ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
    <script type="text/javascript">
		
		var start = 5;
    	var working = false;
    	$(window).scroll(function() {
            if ($(this).scrollTop() + 1 >= $('body').height() - $(window).height()) {
                    if (working == false) {
                        working = true;
						
						
						$.ajax({
							
                        type: "GET",
                        url: "api/profileposts?username=<?php echo $_GET['username']; ?>&start="+start,
                        processData: false,
                        contentType: "application/json",
                        data: '',
                        success: function(r) {
                            var posts = JSON.parse(r)
							$.each(posts, function(index) {
								if(posts[index].PostImage != '') {
									$('.profile_post').html(
										$('.profile_post').html() + '<div id="'+posts[index].PostId+'" style="width: 100%;"><p style="text-align:left;margin-left:9px;">'+posts[index].PostedOn.substr(0, 10)+' </p><p delete-id="'+posts[index].PostId+'" id="delete-post" style="float: right; margin-top: -30px;"><a>Delete</a></p><img src="'+posts[index].PostImage+'" style="width:70%;box-shadow:2px 2px 2px;margin-bottom:33px;margin-left:-17px;"><img src="'+posts[index].ProfileImage+'" id="sideprof" style="border-radius:100%;width:68px;float:left;margin:0;margin-left:-36px;margin-top:0;margin-bottom:0px;margin-right:0px;"><p style="font-size:22px;word-wrap: break-word;width:90%;margin:0 auto;margin-bottom:0;">- '+posts[index].PostBody+'</p><p style="font-size:20px;margin-top:72px;margin-bottom:-52px;margin-left:23px;"><div style="color: #FF4136;height: 15px;display:inline-block;" data-id="'+posts[index].PostId+'" value=""><img src="assets/img/like-button.png" height="15px" width="15px" class="int-button"> '+posts[index].Likes+' likes</div><div style="color: #FFDC00;height: 15px;display:inline-block; margin-left:20px;" data-postid="'+posts[index].PostId+'" value=""><img src="assets/img/comment-icon.png" height="15px" width="15px" class="int-button"> '+posts[index].Comments+' comments</div></p></div><?php if(!Login::isLoggedIn()) {echo '<div style="display: none;">'; } ?><div class="group" style="margin-top:-18px; margin-bottom: 0px;"><form action="profile.php?post_id='+posts[index].PostId+'&username='+posts[index].PostedBy+'#'+posts[index].PostId+'" method="post" enctype="multipart/form-data" style="margin-top:50px;"><input type="text" name="commentbody" required><span class="highlight"></span><span class="bar"></span><label>Comment</label><input type="submit" name="comment" class="submitButton csubmit" value="Submit"></form></div><?php if(!Login::isLoggedIn()) { echo '</div><div style="display: block;">'; } else { echo '<div style="display: none;"><br /><br /><br />'; } ?><h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br /><?php echo '</div>'; ?><img src="assets/img/wavesep.png" style="width:100%;min-width:0px;height:85px;margin-top:4%;"></div>'
									)
									
									$('[data-postid]').click(function() {
										var buttonid = $(this).attr('data-postid');
										$.ajax({
											type: "GET",
											url: "api/comments?post_id=" + $(this).attr('data-postid'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {
												var res = JSON.parse(r)
												showCommentsModal(res);	
												console.log(r);
											},
											error: function(r) {
												console.log(r);
												if(r.status == 401) {
													alert('Sorry but you must be the user who posted this!');
												}
											}
										})
									})
									
									$('[data-id]').click(function() {
										var buttonid = $(this).attr('data-id');
										$.ajax({
											type: "POST",
											url: "api/likes?id=" + $(this).attr('data-id'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {
												var res = JSON.parse(r)
												$("[data-id='"+buttonid+"']").html(' <img src="assets/img/like-button.png" height="15px" width="15px"> '+res.Likes+' likes</div>')
												console.log(r);
											},
											error: function(r) {
												console.log(r);
											}
										})
									})
									
									$('[delete-id]').click(function() {
										$.ajax({
											type: "POST",
											url: "api/delete-post?postid="+$(this).attr('delete-id'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {
												location.reload();
												console.log(r);
											},
											error: function(r) {
												console.log(r);
												if(r.status == 401) {
													alert('Sorry but you must be the user who posted this!');
												}
											}
										})
									});
									
								} else {
									$('.profile_post').html(
										$('.profile_post').html() + '<div id="'+posts[index].PostId+'" style="width: 100%;"><p style="text-align:left;margin-left:9px;">'+posts[index].PostedOn.substr(0, 10)+' </p><p delete-id="'+posts[index].PostId+'" id="delete-post" style="float: right; margin-top: -30px;"><a>Delete</a></p><img src="'+posts[index].ProfileImage+'" id="sideprof" style="border-radius:100%;width:68px;float:left;margin:0;margin-left:-36px;margin-top:0;margin-bottom:0px;margin-right:0px;"><p style="font-size:22px;word-wrap: break-word;width:90%;margin:0 auto;margin-bottom:0;">- '+posts[index].PostBody+'</p><p style="font-size:20px;margin-top:72px;margin-bottom:-52px;margin-left:23px;"><div style="color: #FF4136;height: 15px;display:inline-block;" data-id="'+posts[index].PostId+'" value=""><img src="assets/img/like-button.png" height="15px" width="15px" class="int-button"> '+posts[index].Likes+' likes</div><div style="color: #FFDC00;height: 15px;display:inline-block; margin-left:20px;" data-postid="'+posts[index].PostId+'" value=""><img src="assets/img/comment-icon.png" height="15px" width="15px" class="int-button"> '+posts[index].Comments+' comments</div></p></div><?php if(!Login::isLoggedIn()) {echo '<div style="display: none;">'; } ?><div class="group" style="margin-top:-18px; margin-bottom: 0px;"><form action="profile.php?post_id='+posts[index].PostId+'&username='+posts[index].PostedBy+'#'+posts[index].PostId+'" method="post" enctype="multipart/form-data" style="margin-top:50px;"><input type="text" name="commentbody" required><span class="highlight"></span><span class="bar"></span><label>Comment</label><input type="submit" name="comment" class="submitButton csubmit" value="Submit"></form></div><?php if(!Login::isLoggedIn()) { echo '</div><div style="display: block;">'; } else { echo '<div style="display: none;"><br /><br /><br />'; } ?><h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br /><?php echo '</div>'; ?><img src="assets/img/wavesep.png" style="width:100%;min-width:0px;height:85px;margin-top:4%;"></div>'
									)
									
									$('[data-postid]').click(function() {
										var buttonid = $(this).attr('data-postid');
										$.ajax({
											type: "GET",
											url: "api/comments?post_id=" + $(this).attr('data-postid'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {
												var res = JSON.parse(r)
												showCommentsModal(res);	
												console.log(r);
											},
											error: function(r) {
												console.log(r);
											}
										})
									})
									
									$('[data-id]').click(function() {
										var buttonid = $(this).attr('data-id');
										$.ajax({
											type: "POST",
											url: "api/likes?id=" + $(this).attr('data-id'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {
												var res = JSON.parse(r)
												$("[data-id='"+buttonid+"']").html(' <img src="assets/img/like-button.png" height="15px" width="15px"> '+res.Likes+' likes</div>')
												console.log(r);
											},
											error: function(r) {
												console.log(r);
											}
										})
									})
									
									$('[delete-id]').click(function() {
										$.ajax({
											type: "POST",
											url: "api/delete-post?postid="+$(this).attr('delete-id'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {	
												location.reload();
												console.log(r);
											},
											error: function(r) {
												console.log(r);
												if(r.status == 401) {
													alert('Sorry but you must be the user who posted this!');
												}
											}
										})
									});
								}
							})
							
							scrollToAnchor(location.hash);
							
							start+=5;
                            working = false;
							
                        },
                        error: function(r) {
                               console.log(r)
                        }
                	});
				}
			}
		});
		
		$(document).ready(function() {
				$.ajax({
                        type: "GET",
                        url: "api/profileposts?username=<?php echo $_GET['username']; ?>&start=0",
                        processData: false,
                        contentType: "application/json",
                        data: '',
                        success: function(r) {
                            var posts = JSON.parse(r)
							$.each(posts, function(index) {
								if(posts[index].PostImage != '') {
									$('.profile_post').html(
										$('.profile_post').html() + '<div id="'+posts[index].PostId+'" style="width: 100%;"><p style="text-align:left;margin-left:9px;">'+posts[index].PostedOn.substr(0, 10)+' </p><p delete-id="'+posts[index].PostId+'" id="delete-post" style="float: right; margin-top: -30px;"><a>Delete</a></p><img src="'+posts[index].PostImage+'" style="width:70%;box-shadow:2px 2px 2px;margin-bottom:33px;margin-left:-17px;"><img src="'+posts[index].ProfileImage+'" id="sideprof" style="border-radius:100%;width:68px;float:left;margin:0;margin-left:-36px;margin-top:0;margin-bottom:0px;margin-right:0px;"><p style="font-size:22px;word-wrap: break-word;width:90%;margin:0 auto;margin-bottom:0;">- '+posts[index].PostBody+'</p><p style="font-size:20px;margin-top:72px;margin-bottom:-52px;margin-left:23px;"><div style="color: #FF4136;height: 15px;display:inline-block;" data-id="'+posts[index].PostId+'" value=""><img src="assets/img/like-button.png" height="15px" width="15px" class="int-button"> '+posts[index].Likes+' likes</div><div style="color: #FFDC00;height: 15px;display:inline-block; margin-left:20px;" data-postid="'+posts[index].PostId+'" value=""><img src="assets/img/comment-icon.png" height="15px" width="15px" class="int-button"> '+posts[index].Comments+' comments</div></p></div><?php if(!Login::isLoggedIn()) {echo '<div style="display: none;">'; } ?><div class="group" style="margin-top:-18px; margin-bottom: 0px;"><form action="profile.php?post_id='+posts[index].PostId+'&username='+posts[index].PostedBy+'#'+posts[index].PostId+'" method="post" enctype="multipart/form-data" style="margin-top:50px;"><input type="text" name="commentbody" required><span class="highlight"></span><span class="bar"></span><label>Comment</label><input type="submit" name="comment" class="submitButton csubmit" value="Submit"></form></div><?php if(!Login::isLoggedIn()) { echo '</div><div style="display: block;">'; } else { echo '<div style="display: none;"><br /><br /><br />'; } ?><h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br /><?php echo '</div>'; ?><img src="assets/img/wavesep.png" style="width:100%;min-width:0px;height:85px;margin-top:4%;"></div>'
									)
									
									$('[data-postid]').click(function() {
										var buttonid = $(this).attr('data-postid');
										$.ajax({
											type: "GET",
											url: "api/comments?post_id=" + $(this).attr('data-postid'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {
												var res = JSON.parse(r)
												showCommentsModal(res);	
												console.log(r);
											},
											error: function(r) {
												console.log(r);
											}
										})
									})
									
									$('[data-id]').click(function() {
										var buttonid = $(this).attr('data-id');
										$.ajax({
											type: "POST",
											url: "api/likes?id=" + $(this).attr('data-id'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {
												var res = JSON.parse(r)
												$("[data-id='"+buttonid+"']").html(' <img src="assets/img/like-button.png" height="15px" width="15px"> '+res.Likes+' likes</div>')
												console.log(r);
											},
											error: function(r) {
												console.log(r);
											}
										})
									})
									
									$('[delete-id]').click(function() {
										$.ajax({
											type: "POST",
											url: "api/delete-post?postid="+$(this).attr('delete-id'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {		
												location.reload();
												console.log(r);
											},
											error: function(r) {
												console.log(r);
												if(r.status == 401) {
													alert('Sorry but you must be the user who posted this!');
												}
											}
										})
									});
									
								} else {
									$('.profile_post').html(
										$('.profile_post').html() + '<div id="'+posts[index].PostId+'" style="width: 100%;"><p style="text-align:left;margin-left:9px;">'+posts[index].PostedOn.substr(0, 10)+' </p><p delete-id="'+posts[index].PostId+'" id="delete-post" style="float: right; margin-top: -30px;"><a>Delete</a></p><img src="'+posts[index].ProfileImage+'" id="sideprof" style="border-radius:100%;width:68px;float:left;margin:0;margin-left:-36px;margin-top:0;margin-bottom:0px;margin-right:0px;"><p style="font-size:22px;word-wrap: break-word;width:90%;margin:0 auto;margin-bottom:0;">- '+posts[index].PostBody+'</p><p style="font-size:20px;margin-top:72px;margin-bottom:-52px;margin-left:23px;"><div style="color: #FF4136;height: 15px;display:inline-block;" data-id="'+posts[index].PostId+'" value=""><img src="assets/img/like-button.png" height="15px" width="15px" class="int-button"> '+posts[index].Likes+' likes</div><div style="color: #FFDC00;height: 15px;display:inline-block; margin-left:20px;" data-postid="'+posts[index].PostId+'" value=""><img src="assets/img/comment-icon.png" height="15px" width="15px" class="int-button"> '+posts[index].Comments+' comments</div></p></div><?php if(!Login::isLoggedIn()) {echo '<div style="display: none;">'; } ?><div class="group" style="margin-top:-18px; margin-bottom: 0px;"><form action="profile.php?post_id='+posts[index].PostId+'&username='+posts[index].PostedBy+'#'+posts[index].PostId+'" method="post" enctype="multipart/form-data" style="margin-top:50px;"><input type="text" name="commentbody" required><span class="highlight"></span><span class="bar"></span><label>Comment</label><input type="submit" name="comment" class="submitButton csubmit" value="Submit"></form></div><?php if(!Login::isLoggedIn()) { echo '</div><div style="display: block;">'; } else { echo '<div style="display: none;"><br /><br /><br />'; } ?><h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br /><?php echo '</div>'; ?><img src="assets/img/wavesep.png" style="width:100%;min-width:0px;height:85px;margin-top:4%;"></div>'
									)
									
									$('[data-postid]').click(function() {
										var buttonid = $(this).attr('data-postid');
										$.ajax({
											type: "GET",
											url: "api/comments?post_id=" + $(this).attr('data-postid'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {
												var res = JSON.parse(r)
												showCommentsModal(res);	
												console.log(r);
											},
											error: function(r) {
												console.log(r);
											}
										})
									})
									
									$('[data-id]').click(function() {
										var buttonid = $(this).attr('data-id');
										$.ajax({
											type: "POST",
											url: "api/likes?id=" + $(this).attr('data-id'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {
												var res = JSON.parse(r)
												$("[data-id='"+buttonid+"']").html(' <img src="assets/img/like-button.png" height="15px" width="15px"> '+res.Likes+' likes</div>')
												console.log(r);
											},
											error: function(r) {
												console.log(r);
											}
										})
									})
									
									$('[delete-id]').click(function() {
										$.ajax({
											type: "POST",
											url: "api/delete-post?postid="+$(this).attr('delete-id'),
											processData: false,
											contentType: "application/json",
											data: '',
											success: function(r) {		
												location.reload();
												console.log(r);
											},
											error: function(r) {
												console.log(r);
												if(r.status == 401) {
													alert('Sorry but you must be the user who posted this!');
												}
											}
										})
									});
									
									
								}
							})
							
							scrollToAnchor(location.hash);
							
                        },
                        error: function(r) {
                               console.log(r)
                        }
                });	  
		});
		
		function showCommentsModal(res) {
			$('.modal').modal('show')
			var output ="";
			var userid = <?php echo Login::isLoggedIn() ?>;
			for (var i = 0;i < res.length; i++) {
				output += res[i].Comment;
				output += " ~ "
				output += res[i].CommentedBy;
				if(res[i].CommentedById == userid) {
					output += '<form action="profile.php?id='+res[i].CommentId+'&username='+res[i].CommentedBy+'&post_id='+res[i].CommentPostId+'#'+res[i].CommentPostId+'" method="post" enctype="multipart/form-data"><input type="submit" name="delete-comment" value="Delete" style="width: 100px; margin-top: -30px;border: none; float: right; font-size:15px;"></form>';
				}
				output += "<hr />";
			}
			
			$('.modal-body').html(output)
		}
		
		function scrollToAnchor(aid) {
			try {
				var aTag = $(aid);
				$('html,body').animate({scrollTop: aTag.offset().top}, 'slow');
			} catch(error) {
				console.log(error);
			}
			
		}
		
		
		
	</script>
</body>

</html>

<style>

	#profimge {
		margin-top:0;
		width:84%;
		border-radius:100%;
		margin-left:-106px;
	}
	
	.glyphicon glyphicon-heart {
		margin-left: 55px;
	}
	
	.profile_post {
		width:600px;
		border-left:5px solid lightgrey;
	}
	
	#descrip {
		width:443px;
	}
	
	#bcircle {
		display: none;	
	}

@media only screen 
  and (min-width: 320px) 
  and (max-width: 768px) {

	
	#profilenums {
		display: none;
	}
	
	.profile_post {
		width:100%;
		border-left:0px solid lightgrey;
	}
	
	#sideprof {
		display: none;
	}
	
	#descrip {
		width:90%;
	}
	
	#bcircle {
		display: block;	
	}


}



</style>