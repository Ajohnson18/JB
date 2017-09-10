<?php

	include('classes/DB.php');
	include('classes/Post.php');
	include('classes/Comment.php');
	include('classes/Login.php');				
					
	if (isset($_POST['comment'])) {
		$comment = $_POST['commentbody'];
		if(strlen($comment) < 1 || strlen($comment) > 160) {
			echo '<script type=\'text/javascript\'>alert(\'Comment is too long or too short!\');</script>';
		} else {
			Comment::createComment($comment, $_GET['post_id'], Login::isLoggedIn());
			header("Location: feed.php?commentopen=true&post_id=".$_GET['post_id']."#".$_GET['post_id']."");
			exit();
		}
	}
					
	if(isset($_POST['delete-comment'])) {
		if(DB::query('SELECT comments.comment FROM comments WHERE comment=:comment', array(':comment'=>$_GET['post_id']))) {
			$comment = DB::query('SELECT comments.comment FROM comments WHERE comment=:comment', array(':comment'=>$_GET['post_id']))[0]['comment'];
			$postid = DB::query('SELECT post_id FROM comments WHERE comment=:comment', array(':comment'=>$comment))[0]['post_id'];
			DB::query('UPDATE posts SET comments=comments-1 WHERE id=:postid', array(':postid'=>$postid));
			DB::query('DELETE FROM comments WHERE comment=:comment AND user_id=:userid', array(':comment'=>$comment, ':userid'=>Login::isLoggedIn()));
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
    <section style="margin:0;margin-top:100px;text-align:center;font-family:quicksand;">
        <h1 style="padding: 0 30px; font-size:2em;"><b>What is everyone talking about...</b><br /><h2 style="font-size:1.8em; padding: 0 30px;">Follow People To See Their Posts!</h2></h1>
        <?php
			if(!Login::isLoggedIn()) {
       			echo '<h2 style="font-size:1.5em; padding: 0 30px;"><a href="login.php">Login</a> to see your feed...</h2>';
       		}
		?>
        </section>
        <br />
   		<br />
       
       	
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
    <script type="text/javascript">
		
		function scrollToAnchor(aid) {
			try {
				var aTag = $(aid);
				$('html,body').animate({scrollTop: aTag.offset().top}, 'slow');
			} catch (error) {
				console.log(error);
			}
			
		}
		
		var start = 5;
    	var working = false;
    	$(window).scroll(function() {
            if ($(this).scrollTop() + 1 >= $('body').height() - $(window).height()) {
                    if (working == false) {
                            working = true;
		
								$.ajax({

								type: "GET",
								url: "api/posts&start="+start,
								processData: false,
								contentType: "application/json",
								data: '',
								success: function(r) {
									var posts = JSON.parse(r)
									$.each(posts, function(index) {
										if(posts[index].PostImage != '') {
											$('.profile_post').html(
												$('.profile_post').html() + '<div id="'+posts[index].PostId+'" style="margin: 0 20px;"><p style="text-align:left;margin-left:9px;">'+posts[index].PostedOn.substr(0, 10)+' </p><img src="'+posts[index].PostImage+'" style="width:70%;box-shadow:2px 2px 2px;margin-bottom:33px;margin-left:-17px;"><img id="profileimgside" src="'+posts[index].ProfileImage+'" style="border-radius:100%;width:68px;float:left;margin:0;margin-left:-36px;margin-top:0;margin-bottom:0px;margin-right:0px;"><p style="font-size:1.5em;word-wrap: break-word;width: 90%;margin:0 auto;margin-bottom:0;"><a href="profile.php?username='+posts[index].PostedBy+'"><b>'+posts[index].PostedBy+'</b></a> - '+posts[index].PostBody+'</p><p style="font-size:20px;margin-top:72px;margin-bottom:-52px;margin-left:23px;"><div style="color: #FF4136;height: 15px;display:inline-block;" data-id="'+posts[index].PostId+'" value=""><img src="assets/img/like-button.png" height="15px" width="15px" class="int-button"> '+posts[index].Likes+' likes</div><div style="color: #FFDC00;height: 15px;display:inline-block; margin-left:20px;" data-postid="'+posts[index].PostId+'" value=""><img src="assets/img/comment-icon.png" height="15px" width="15px" class="int-button"> '+posts[index].Comments+' comments</div></p></div><?php if(!Login::isLoggedIn()) {echo '<div style="display: none;">'; } ?><div class="group" style="margin-top:-18px; margin-bottom: 0px;"><form action="feed.php?post_id='+posts[index].PostId+'" method="post" enctype="multipart/form-data" style="margin-top:50px;"><input type="text" name="commentbody" required><span class="highlight"></span><span class="bar"></span><label>Comment</label><input type="submit" name="comment" class="submitButton csubmit" value="Submit"></form></div><?php if(!Login::isLoggedIn()) { echo '</div><div style="display: block;">'; } else { echo '<div style="display: none;"><br /><br /><br />'; } ?><h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br /><?php echo '</div>'; ?><img id="wavesep" src="assets/img/wavesep.png" style="width:100%;min-width:0px;margin-top:4%;"></div>'
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

										} else {
											$('.profile_post').html(
												$('.profile_post').html() + '<div id="'+posts[index].PostId+'"><p style="text-align:left;margin-left:9px;">'+posts[index].PostedOn.substr(0, 10)+' </p><img id="profileimgside" src="'+posts[index].ProfileImage+'" style="border-radius:100%;width:68px;float:left;margin:0;margin-left:-36px;margin-top:0;margin-bottom:0px;margin-right:0px;"><p style="font-size:1.5em;word-wrap: break-word;width:90%;margin:0 auto;margin-bottom:0;"><a href="profile.php?username='+posts[index].PostedBy+'"><b>'+posts[index].PostedBy+'</b></a> - '+posts[index].PostBody+'</p><p style="font-size:20px;margin-top:72px;margin-bottom:-52px;margin-left:23px;"><div style="color: #FF4136;height: 15px;display:inline-block;" data-id="'+posts[index].PostId+'" value=""><img src="assets/img/like-button.png" height="15px" width="15px" class="int-button"> '+posts[index].Likes+' likes</div><div style="color: #FFDC00;height: 15px;display:inline-block; margin-left:20px;" data-postid="'+posts[index].PostId+'" value=""><img src="assets/img/comment-icon.png" height="15px" width="15px" class="int-button"> '+posts[index].Comments+' comments</div></p></div><?php if(!Login::isLoggedIn()) {echo '<div style="display: none;">'; } ?><div class="group" style="margin-top:-18px; margin-bottom: 0px;"><form action="feed.php?post_id='+posts[index].PostId+'" method="post" enctype="multipart/form-data" style="margin-top:50px;"><input type="text" name="commentbody" required><span class="highlight"></span><span class="bar"></span><label>Comment</label><input type="submit" name="comment" class="submitButton csubmit" value="Submit"></form></div><?php if(!Login::isLoggedIn()) { echo '</div><div style="display: block;">'; } else { echo '<div style="display: none;"><br /><br /><br />'; } ?><h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br /><?php echo '</div>'; ?><img id="wavesep" src="assets/img/wavesep.png" style="width:100%;min-width:0px;margin-top:4%;"></div>'
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
                        url: "api/posts&start=0",
                        processData: false,
                        contentType: "application/json",
                        data: '',
                        success: function(r) {
                            var posts = JSON.parse(r)
							$.each(posts, function(index) {
								if(posts[index].PostImage != '') {
									$('.profile_post').html(
										$('.profile_post').html() + '<div id="'+posts[index].PostId+'"><p style="text-align:left;margin-left:9px;">'+posts[index].PostedOn.substr(0, 10)+' </p><img src="'+posts[index].PostImage+'" style="width:70%;box-shadow:2px 2px 2px;margin-bottom:33px;margin-left:-17px;"><img id="profileimgside" src="'+posts[index].ProfileImage+'" style="border-radius:100%;width:68px;float:left;margin:0;margin-left:-36px;margin-top:0;margin-bottom:0px;margin-right:0px;"><p style="font-size:1.5em;width:90%;word-wrap: break-word;margin:0 auto;margin-bottom:0;"><a href="profile.php?username='+posts[index].PostedBy+'"><b>'+posts[index].PostedBy+'</b></a> - '+posts[index].PostBody+'</p><p style="font-size:20px;margin-top:72px;margin-bottom:-52px;margin-left:23px;"><div style="color: #FF4136;height: 15px;display:inline-block;" data-id="'+posts[index].PostId+'" value=""><img src="assets/img/like-button.png" height="15px" width="15px" class="int-button"> '+posts[index].Likes+' likes</div><div style="color: #FFDC00;height: 15px;display:inline-block; margin-left:20px;" data-postid="'+posts[index].PostId+'" value=""><img src="assets/img/comment-icon.png" height="15px" width="15px" class="int-button"> '+posts[index].Comments+' comments</div></p></div><?php if(!Login::isLoggedIn()) {echo '<div style="display: none;">'; } ?><div class="group" style="margin-top:-18px; margin-bottom: 0px;"><form action="feed.php?post_id='+posts[index].PostId+'" method="post" enctype="multipart/form-data" style="margin-top:50px;"><input type="text" name="commentbody" required><span class="highlight"></span><span class="bar"></span><label>Comment</label><input type="submit" name="comment" class="submitButton csubmit" value="Submit"></form></div><?php if(!Login::isLoggedIn()) { echo '</div><div style="display: block;">'; } else { echo '<div style="display: none;"><br /><br /><br />'; } ?><h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br /><?php echo '</div>'; ?><img id="wavesep" src="assets/img/wavesep.png" style="width:100%;min-width:0px;margin-top:4%;"></div>'
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
									
								} else {
									$('.profile_post').html(
										$('.profile_post').html() + '<div id="'+posts[index].PostId+'"><p style="text-align:left;margin-left:9px;">'+posts[index].PostedOn.substr(0, 10)+' </p><img src="'+posts[index].ProfileImage+'" id="profileimgside"  style="border-radius:100%;width:68px;float:left;margin:0;margin-left:-36px;margin-top:0;margin-bottom:0px;margin-right:0px;"><p style="font-size:1.5em;width:90%;word-wrap: break-word;margin:0 auto;margin-bottom:0;"><a href="profile.php?username='+posts[index].PostedBy+'"><b>'+posts[index].PostedBy+'</b></a> - '+posts[index].PostBody+'</p><p style="font-size:20px;margin-top:72px;margin-bottom:-52px;margin-left:23px;"><div style="color: #FF4136;height: 15px;display:inline-block;" data-id="'+posts[index].PostId+'" value=""><img src="assets/img/like-button.png" height="15px" width="15px" class="int-button"> '+posts[index].Likes+' likes</div><div style="color: #FFDC00;height: 15px;display:inline-block; margin-left:20px;" data-postid="'+posts[index].PostId+'" value=""><img src="assets/img/comment-icon.png" height="15px" width="15px" class="int-button"> '+posts[index].Comments+' comments</div></p></div><?php if(!Login::isLoggedIn()) {echo '<div style="display: none;">'; } ?><div class="group" style="margin-top:-18px; margin-bottom: 0px;"><form action="feed.php?post_id='+posts[index].PostId+'" method="post" enctype="multipart/form-data" style="margin-top:50px;"><input type="text" name="commentbody" required><span class="highlight"></span><span class="bar"></span><label>Comment</label><input type="submit" name="comment" class="submitButton csubmit" value="Submit"></form></div><?php if(!Login::isLoggedIn()) { echo '</div><div style="display: block;">'; } else { echo '<div style="display: none;"><br /><br /><br />'; } ?><h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br /><?php echo '</div>'; ?><img src="assets/img/wavesep.png" id="wavesep" style="width:100%;min-width:0px;margin-top:4%;"></div>'
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
			for (var i = 0;i < res.length; i++) {
				output += res[i].Comment;
				output += " ~ "
				output += res[i].CommentedBy;
				output += '<form action="feed.php?post_id='+res[i].Comment+'" method="post" enctype="multipart/form-data"><input type="submit" name="delete-comment" value="Delete" style="width: 100px; margin-top: -45px;border: none; float: right; font-size:15px; margin-right: -30px;"></form>';		
				output += "<hr />";
			}
			
			$('.modal-body').html(output)
		}
		
		
		
	</script>
    
</body>

</html>


<style>
       			
       		.profile_post {
			width: 600px;
			border-left: 5px solid lightgrey;
			
		}
		
		#wavesep {
			height:85px;
		}
       			
       		   @media only screen 
		      and (min-width: 320px) 
		      and (max-width: 736px) {
			
			.profile_post {
				width: 100vw;
				border-left: 0px solid lightgrey;
			}
			
			#profileimgside {
				display: none;
			}
			
			#wavesep {
				height:50px;
			}
			
		  }
			       			
       		</style>