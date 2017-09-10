<?php

	include('classes/Login.php');
	include('classes/DB.php');
	include('classes/Post.php');

	$comments = DB::query('SELECT shop_comment.*, users.username, users.profileimg FROM shop_comment, users WHERE shop_id = :shopid AND shop_comment.user_id = users.id ORDER BY shop_comment.id DESC', array(':shopid'=>$_GET['shop_id']));	

	if(isset($_POST['comment'])) {
		$user_id = Login::isLoggedIn();
		$shop_id = $_GET['shop_id'];
		$comment = $_POST['wcomment'];
		
		if(strlen($comment) <= 240 || strlen($comment) > 0) {
			$topics = Post::getTopics($comment);
			DB::query('INSERT INTO shop_comment VALUES (\'\', :userid, :comment, NOW(), :shopid, :topics)', array(':userid'=>$user_id, ':comment'=>$comment, ':shopid'=>$shop_id, ':topics'=>$topics));
			header("Location: shops.php?shop_id=".$shop_id."#comment");
		} else {
			echo '<script type="text/javascript">alert(\'Comment length invalid!\')</script>';
		}	
	}

	if(isset($_POST['delete'])) {
		$comment_id = $_GET['comment_id'];
		$id = $_GET['shop_id'];
		DB::query('DELETE FROM shop_comment WHERE id=:commentid', array(':commentid'=>$comment_id));
		header("Location: shops.php?shop_id=".$id."#comment");
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
    <link rel="stylesheet" href="assets/css/Google-Style-Text-Input.css">
    <link rel="stylesheet" href="assets/css/Lightbox-Gallery.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Map-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="assets/css/main.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
</head>

<body>
    <?php include('includes/nav.php'); ?>
    <div class="photo-gallery" style="margin-top:23px;">
        <div class="container">
            <div class="intro">
                <h2 class="text-center" style="margin-bottom:12px;margin-top:65px;">Local Shops</h2>
                <p class="text-center">All of the local buisnesses along the Jersey Shore that help keep a healthy and incredible surf culture. Click on the images down below to learn more about these shops!</p>
            </div>
            <div class="row photos">
                <div class="col-lg-3 col-md-4 col-sm-6 item">
                    <a data-lightbox="photos" href="shops.php?shop_id=1#gordons"><img class="img-responsive" src="assets/img/gordons.jpg" style="height:195.234px;width:293.238px;" id="pgordons"></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 item">
                    <a data-lightbox="photos" href="shops.php?shop_id=2#brave"><img class="img-responsive" src="assets/img/brave.jpg" style="height:195.234px;width:293.238px;" id="pbrave"></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 item">
                    <a data-lightbox="photos" href="shops.php?shop_id=3#bare"><img class="img-responsive" src="assets/img/barewires.jpg" style="height:195.234px;width:293.238px;" id="pbare"></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 item">
                    <a data-lightbox="photos" href="shops.php?shop_id=4#eastern"><img class="img-responsive" src="assets/img/easternlines.jpg" style="height:195.234px;width:293.238px;" id="peastern"></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 item">
                    <a data-lightbox="photos" href="shops.php?shop_id=5#green"><img class="img-responsive" src="assets/img/green.jpg" style="height:195.234px;width:293.238px;" id="pgreen"></a>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 item">
                    <a data-lightbox="photos" href="shops.php?shop_id=6#inlet"><img class="img-responsive" src="assets/img/inletoutlet.jpg" style="height:195.234px;width:293.238px;" id="pinlet"></a>
                </div>
            </div>
        </div>
    </div>
    <section style="text-align:center;font-family:'Quicksand';">
        <div style="width:100%;padding-top:51px;padding-bottom:52px;background-position:top;">
            <hr>
            
           <!-- ==================================================--> 
            <?php if($_GET['shop_id']==1) {
			echo '
			<div style="margin-top:0;margin-bottom:1px;padding-bottom:15px;padding-right:0;"  id="gordons"><img src="assets/img/gordons.jpg" style="border-radius:100%;margin-top:59px;" id="shopimg">
                <h1 style="margin-bottom:30px;margin-top:11px;padding-top:20px;font-size:280%;">Gordon\'s Surf Shop</h1>
                <p style="margin-top:30px;font-size:150%;margin-bottom:30px;" id="shopinfo">
				
				
				
				
				
				
				
				Website: <a href="http://www.gordonssurfshop.com/" style="word-break: break-all;">http://www.gordonssurfshop.com/</a><br /><br />
				Gordon’s is a family run surf shop in Point Pleasant Beach, NJ. Stop by for surfboards, surf
				apparel and accessories. Gordon’s also does surf board repairs and takes used surfboards and
				wetsuits on consignment.<br />
				Gordon’s is open year-round and is located on Arnold Avenue in downtown Point Pleasant
				Beach.<br /><br />

				Locations:<br />
				GORDON’S SURF SHOP<br />
				609 Arnold Avenue<br />
				Point Pleasant Beach, NJ 08742<br />
				732.475.7984<br /><br />
				HOURS:<br />
				Monday – Saturday: 10a – 8p<br />
				Sunday: 10a – 6p<br />
	
				
				
				
				
				
				
				
				
				</p>
                <iframe allowfullscreen="" frameborder="0" width="100%" height="450" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDVItupCtwXslXSY1STHc-E-l_lcTq-QYE&amp;q=Gordon\'s+Surf+Shop&amp;zoom=15"></iframe>
                <h1 style="border-bottom:2px solid black;width:254px;padding-bottom:13px;margin:0 auto;margin-top:25px;margin-bottom:17px;">Comments </h1>
				';
				if(Login::isLoggedIn()) {
					echo '<div class="group">      
					<form action="shops.php?shop_id=1" method="post" id="comment" enctype="multipart/form-data">
						<input type="text" id="body" name="wcomment" required>
						<span class="highlight"></span>
						<span class="bar"></span>
						<label>Comment</label>
							<input type="submit" value="Submit" id="comment" name="comment" class="submitButton">
						</form>
					</div>';
				} else {
					echo '<h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br />';
				}
	
				echo '<div style="max-height:1000px;overflow-y:scroll;margin-bottom:59px;">';
					
				foreach($comments as $comment) {
					echo ' <div class="comment" style="width:80%;">';
					if($comment['user_id'] == Login::isLoggedIn()){
						echo '<form action="shops.php?comment_id='.$comment['id'].'&shop_id=1" method="post"><input type="submit" name="delete" value="Delete" style="width: 100px; border: none; float: right;"></form>';
					}
					echo '<img src="'.$comment['profileimg'].'" style="width:7%;border-radius:100%;float:left;margin-right:10px;margin-top:6px;" id="cprof">';
					echo '<h1 style="font-size:1.2em;float:left;width:75%;margin-top:11px; text-align: left;" id="'.$comment['id'].'"><strong>' .$comment['username']. '</strong> - ' .Post::link_add($comment['comment']). '</h1></div>';
				}
						
                echo '</div>
            </div>'; } ?>
            
            <!-- ==================================================--> 
            
            <?php if($_GET['shop_id']==2) {
			echo '
            <div style="margin-top:0;margin-bottom:1px;padding-bottom:15px;padding-right:0;" id="brave"><img src="assets/img/brave.jpg" style="border-radius:100%;margin-top:59px;" id="shopimg">
                <h1 style="margin-bottom:30px;margin-top:11px;padding-top:20px;font-size:280%;">Brave New World</h1>
                <p style="margin-top:30px;font-size:150%;margin-bottom:30px;" id="shopinfo">
				
				
				
				
							Website: <a href="https://bravesurf.com/">https://bravesurf.com/</a><br /><br />
				Brave New World began in 1974 as a local beach shop in Point Pleasant, NJ. Their employees
				are knowledgeable about local surf conditions and the best equipment for the Jersey Shore
				surfer.<br />
				They presently have 3 retail locations that carry a huge inventory of surfboards, skateboards,
				snowboards and skis, wetsuits and UV protective clothing, surf and snow gear and accessories,
				men’s, ladies’ and kids’ clothing, winter outerwear, footwear, sunglasses and watches, even fun
				accessories for home and car.<br />
<br />
				Don’t Miss:<br />
				Surfboard and Wetsuit Swap over Memorial Day weekend. Great time to pick up a good quality
				used board or wetsuit for the summer.<br />
				Discounts: Subscribe for emails on their website for discounts and information about specials
				surfing events.<br />
<br />
				Locations:<br />
				Brave New World (Point Pleasant Beach)<br />
				1208 Richmond Ave.<br />
				Point Pleasant Beach, NJ 08742<br />
				732-899- 8220<br />
				HOURS:<br />
				Monday-Friday 9:00am to 9:00pm<br />
				Saturday 9:00am to 8:00pm<br />
				Sunday 10:00am to 6:00pm<br />
<br />
				Brave New World (Little Silver)<br />
				61 Oceanport Ave.<br />
				Little Silver, NJ 07739<br />
				732-842- 6767<br />
				HOURS:<br />
				Monday-Friday 10:00am to 8:00pm<br />
				Saturday 10:00am to 6:00pm<br />
				Sunday 11:00am to 5:00pm<br />
<br />

				Brave New World (Toms River)<br />
				1272 Hooper Ave.<br />
				Toms River, NJ 08753<br />
				732-505- 3600<br />
				HOURS:<br />
				Monday-Friday 10:00am to 8:00pm<br />
				Saturday 10:00am to 6:00pm<br />
				Sunday 11:00am to 5:00pm<br />
				
				
				
				
				</p>
                <iframe allowfullscreen="" frameborder="0" width="100%" height="450" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDVItupCtwXslXSY1STHc-E-l_lcTq-QYE&amp;q=Brave+New+World&amp;zoom=15"></iframe>
                <h1 style="border-bottom:2px solid black;width:254px;padding-bottom:13px;margin:0 auto;margin-top:25px;margin-bottom:17px;">Comments </h1>
				';
				if(Login::isLoggedIn()) {
					echo '<div class="group">      
					<form action="shops.php?shop_id=2" method="post" enctype="multipart/form-data">
						<input type="text" id="body" name="wcomment" required>
						<span class="highlight"></span>
						<span class="bar"></span>
						<label>Comment</label>
							<input type="submit" value="Submit" id="comment" name="comment" class="submitButton">
						</form>
					</div>';
				} else {
					echo '<h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br />';
				}
	
				echo '<div style="max-height:1000px;overflow-y:scroll;margin-bottom:59px;">';
					
				foreach($comments as $comment) {
					echo ' <div class="comment" style="width:80%;">';
					if($comment['user_id'] == Login::isLoggedIn()){
						echo '<form action="shops.php?comment_id='.$comment['id'].'&shop_id=2" method="post"><input type="submit" name="delete" value="Delete" style="width: 100px; border: none; float: right;"></form>';
					}
					echo '<img src="'.$comment['profileimg'].'" style="width:7%;border-radius:100%;float:left;margin-right:10px;margin-top:6px;" id="cprof">';
					echo '<h1 style="font-size:1.3em;float:left;width:75%;margin-top:11px; text-align: left;"><strong>' .$comment['username']. '</strong> - ' .Post::link_add($comment['comment']). '</h1></div>';
				}
						
                echo '</div>
            </div>'; } ?>
            
            <!-- ==================================================--> 
            
            <?php if($_GET['shop_id']==3) {
			echo '
            <div style="margin-top:0;margin-bottom:1px;padding-bottom:15px;padding-right:0;" id="bare"><img src="assets/img/barewires.jpg" style="border-radius:100%;margin-top:59px;" id="shopimg">
                <h1 style="margin-bottom:30px;margin-top:11px;padding-top:20px;font-size:280%;">Bare Wires Surf Shop</h1>
                <p style="margin-top:30px;font-size:150%;margin-bottom:30px;" id="shopinfo">
				
				
				
				Website: <a href="https://www.barewiresurfshop.com">https://www.barewiresurfshop.com/</a><br /><br />
				Bare Wires is a surf and skate shop located in Spring Lake, NJ. They are open year-round and stock
				surfboards for all levels, wetsuits, accessories and more.<br /><br />
				Locations:<br />
				Bare Wires Surf Shop<br />
				1307 3rd Ave<br />
				Spring Lake, NJ 07762<br />
				732-359- 7780<br /><br />
				Hours<br />
				Monday - Saturday 10 A.M. to 6 P.M.<br />
				Sunday 10 A.M. to 5 P.M.<br />
				
				
				
				</p>
                <iframe allowfullscreen="" frameborder="0" width="100%" height="450" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDVItupCtwXslXSY1STHc-E-l_lcTq-QYE&amp;q=Bare+Wires+Surf+Shop&amp;zoom=15"></iframe>
                <h1 style="border-bottom:2px solid black;width:254px;padding-bottom:13px;margin:0 auto;margin-top:25px;margin-bottom:17px;">Comments </h1>
				';
				if(Login::isLoggedIn()) {
					echo '<div class="group">      
					<form action="shops.php?shop_id=3" method="post" enctype="multipart/form-data">
						<input type="text" id="body" name="wcomment" required>
						<span class="highlight"></span>
						<span class="bar"></span>
						<label>Comment</label>
							<input type="submit" value="Submit" id="comment" name="comment" class="submitButton">
						</form>
					</div>';
				} else {
					echo '<h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br />';
				}
	
				echo '<div style="max-height:1000px;overflow-y:scroll;margin-bottom:59px;">';
					
				foreach($comments as $comment) {
					echo ' <div class="comment" style="width:80%;">';
					if($comment['user_id'] == Login::isLoggedIn()){
						echo '<form action="shops.php?comment_id='.$comment['id'].'&shop_id=3" method="post"><input type="submit" name="delete" value="Delete" style="width: 100px; border: none; float: right;"></form>';
					}
					echo '<img src="'.$comment['profileimg'].'" style="width:7%;border-radius:100%;float:left;margin-right:10px;margin-top:6px;" id="cprof">';
					echo '<h1 style="font-size:1.3em;float:left;width:75%;margin-top:11px; text-align: left;"><strong>' .$comment['username']. '</strong> - ' .Post::link_add($comment['comment']). '</h1></div>';
				}
						
                echo '</div>
            </div>'; } ?>
            <!-- ==================================================--> 
            
            <?php if($_GET['shop_id']==4) {
			echo '
            <div style="margin-top:0;margin-bottom:1px;padding-bottom:15px;padding-right:0;" id="eastern"><img src="assets/img/easternlines.jpg" style="border-radius:100%;margin-top:59px;" id="shopimg">
                <h1 style="margin-bottom:30px;margin-top:11px;padding-top:20px;font-size:280%;">Eastern Lines</h1>
                <p style="margin-top:30px;font-size:150%;margin-bottom:30px;" id="shopinfo">
				
				
				
				
				Website: <a href="http://easternlines.com/">http://easternlines.com/</a><br /><br />
			Everything you need to surf the Jersey Shore including lessons. This store is right across the
			street from the ocean in Belmar, NJ.<br /><br />

			Location:<br />
			Eastern Lines<br />
			1605 Ocean Ave<br />
			Belmar, NJ<br />
			732-681- 6405<br /><br />

			HOURS:<br />
			Monday 10-8pm<br />
			Tuesday 10-8pm<br />
			Wednesday 10-8pm<br />
			Thursday 10-8pm<br />
			Friday 10-8pm<br />
			Saturday 9-9pm<br />
			Sunday 9-7pm<br />
				
				
				
				
				
				</p>
                <iframe allowfullscreen="" frameborder="0" width="100%" height="450" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDVItupCtwXslXSY1STHc-E-l_lcTq-QYE&amp;q=Eastern+Lines&amp;zoom=15"></iframe>
                <h1 style="border-bottom:2px solid black;width:254px;padding-bottom:13px;margin:0 auto;margin-top:25px;margin-bottom:17px;">Comments </h1>
				';
				if(Login::isLoggedIn()) {
					echo '<div class="group">      
					<form action="shops.php?shop_id=4" method="post" enctype="multipart/form-data">
						<input type="text" id="body" name="wcomment" required>
						<span class="highlight"></span>
						<span class="bar"></span>
						<label>Comment</label>
							<input type="submit" value="Submit" id="comment" name="comment" class="submitButton">
						</form>
					</div>';
				} else {
					echo '<h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br />';
				}
	
				echo '<div style="max-height:1000px;overflow-y:scroll;margin-bottom:59px;">';
					
				foreach($comments as $comment) {
					echo ' <div class="comment" style="width:80%;">';
					if($comment['user_id'] == Login::isLoggedIn()){
						echo '<form action="shops.php?comment_id='.$comment['id'].'&shop_id=4" method="post"><input type="submit" name="delete" value="Delete" style="width: 100px; border: none; float: right;"></form>';
					}
					echo '<img src="'.$comment['profileimg'].'" style="width:7%;border-radius:100%;float:left;margin-right:10px;margin-top:6px;" id="cprof">';
					echo '<h1 style="font-size:1.3em;float:left;width:75%;margin-top:11px; text-align: left;"><strong>' .$comment['username']. '</strong> - ' .Post::link_add($comment['comment']). '</h1></div>';
				}
						
                echo '</div>
            </div>'; } ?>
            
            <!-- ==================================================--> 
            
            <?php if($_GET['shop_id']==5) {
			echo '
            <div style="margin-top:0;margin-bottom:1px;padding-bottom:15px;padding-right:0;" id="green"><img src="assets/img/green.jpg" style="border-radius:100%;margin-top:59px;" id="shopimg">
                <h1 style="margin-bottom:30px;margin-top:11px;padding-top:20px;font-size:280%;">Green Light Surf Supply</h1>
                <p style="margin-top:30px;font-size:150%;margin-bottom:30px;" id="shopinfo">
				
				
				
				Website: <a href="https://greenlightsurfsupply.com/">https://greenlightsurfsupply.com/</a><br /><br />
				Greenlight Surf Supply is a division of Greenlight Surf Co. Greenlight caters to the average surfer
				who wants to expand his/her surfing experience by shaping their own surfboards at home.
				Complete kits include all the materials, tools, instruction, and online support to guide anyone to
				building a great looking and fun to ride surfboard the very first time.
				Greenlight stocks 1500+ surfboard blanks available at any time. Greenlight employees are very
				knowledgeable and helpful. The Greenlight website is packed with great information for anyone
				building a surfboard.<br /><br />

				Don’t Miss:<br />
				Best way to contact and order from Greenlight is thru the website. You can avoid shipping by
				picking up supplies at their location in Manasquan.<br /><br />

				Locations:<br />
				Greenlight Surf Supply<br />
				187 Parker Ave (Rt. 71)<br />
				Manasquan, NJ 08736<br /><br />

				Hours:<br />
				Sunday: Closed<br />
				Monday - Friday: 12-6<br />
				Saturday: 12-5<br />
				
				
				
				
				
				</p>
                <iframe allowfullscreen="" frameborder="0" width="100%" height="450" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDVItupCtwXslXSY1STHc-E-l_lcTq-QYE&amp;q=Greenlight+surf+supply&amp;zoom=15"></iframe>
                <h1 style="border-bottom:2px solid black;width:254px;padding-bottom:13px;margin:0 auto;margin-top:25px;margin-bottom:17px;">Comments </h1>
				';
				if(Login::isLoggedIn()) {
					echo '<div class="group">      
					<form action="shop.phps?shop_id=5" method="post" enctype="multipart/form-data">
						<input type="text" id="body" name="wcomment" required>
						<span class="highlight"></span>
						<span class="bar"></span>
						<label>Comment</label>
							<input type="submit" value="Submit" id="comment" name="comment" class="submitButton">
						</form>
					</div>';
				} else {
					echo '<h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br />';
				}
	
				echo '<div style="max-height:1000px;overflow-y:scroll;margin-bottom:59px;">';
					
				foreach($comments as $comment) {
					echo ' <div class="comment" style="width:80%;">';
					if($comment['user_id'] == Login::isLoggedIn()){
						echo '<form action="shops.php?comment_id='.$comment['id'].'&shop_id=5" method="post"><input type="submit" name="delete" value="Delete" style="width: 100px; border: none; float: right;"></form>';
					}
					echo '<img src="'.$comment['profileimg'].'" style="width:7%;border-radius:100%;float:left;margin-right:10px;margin-top:6px;" id="cprof">';
					echo '<h1 style="font-size:1.3em;float:left;width:75%;margin-top:11px; text-align: left;"><strong>' .$comment['username']. '</strong> - ' .Post::link_add($comment['comment']). '</h1></div>';
				}
						
                echo '</div>
            </div>'; } ?>
            
            <!-- ==================================================--> 
            
            <?php if($_GET['shop_id']==6) {
			echo '
            <div style="margin-top:0;margin-bottom:1px;padding-bottom:15px;padding-right:0;" id="inlet"><img src="assets/img/inletoutlet.jpg" style="border-radius:100%;margin-top:59px;" id="shopimg">
                <h1 style="margin-bottom:30px;margin-top:11px;padding-top:20px;font-size:280%;">Inlet Outlet</h1>
                <p style="margin-top:30px;font-size:150%;margin-bottom:30px;" id="shopinfo">
				
				
				
				Website: <a href="https://www.inletoutletsurfshop.com/">https://www.inletoutletsurfshop.com/</a><br /><br />
				Inlet Outlet is Manasquan’s original surf shop. It was opened in 1973 and the owner is a local
				surfer and photographer. In addition to a wide variety of clothing brands, Inlet Outlet sells new
				and used surfboards. They also do board repairs and can put you in touch with an instructor for
				a quick lesson.<br /><br />

				Locations:<br />
				146 Main Street<br />
				Manasquan, NJ 08736<br />
				732-223- 5842<br /><br />
				HOURS:<br />
				Monday-Saturday 10:00am to 6:00pm<br />
				Sunday 10:00am to 5:00pm<br />
				
				
				
				</p>
                <iframe allowfullscreen="" frameborder="0" width="100%" height="450" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDVItupCtwXslXSY1STHc-E-l_lcTq-QYE&amp;q=Inlet+Outlet&amp;zoom=15"></iframe>
                <h1 style="border-bottom:2px solid black;width:254px;padding-bottom:13px;margin:0 auto;margin-top:25px;margin-bottom:17px;">Comments </h1>
				';
				if(Login::isLoggedIn()) {
					echo '<div class="group">      
					<form action="shops.php?shop_id=6" method="post" enctype="multipart/form-data">
						<input type="text" id="body" name="wcomment" required>
						<span class="highlight"></span>
						<span class="bar"></span>
						<label>Comment</label>
							<input type="submit" value="Submit" id="comment" name="comment" class="submitButton">
						</form>
					</div>';
				} else {
					echo '<h4 style="margin: 30px 0 30px 0;">Uh Oh! You need to login to comment! Click <a href="login.php">HERE</a> to login!<br /><br />';
				}
	
				echo '<div style="max-height:1000px;overflow-y:scroll;margin-bottom:59px;">';
					
				foreach($comments as $comment) {
					echo ' <div class="comment" style="width:80%;">';
					if($comment['user_id'] == Login::isLoggedIn()){
						echo '<form action="shops.php?comment_id='.$comment['id'].'&shop_id=6" method="post"><input type="submit" name="delete" value="Delete" style="width: 100px; border: none; float: right;"></form>';
					}
					echo '<img src="'.$comment['profileimg'].'" style="width:7%;border-radius:100%;float:left;margin-right:10px;margin-top:6px;" id="cprof">';
					echo '<h1 style="font-size:1.3em;float:left;width:75%;margin-top:11px; text-align: left;"><strong>' .$comment['username']. '</strong> - ' .Post::link_add($comment['comment']). '</h1></div>';
				}
						
                echo '</div>
            </div>'; } ?>
            
            <!-- ==================================================--> 
            
        </div>
    </section>
    <div class="footer-basic" style="background-color:rgb(241,219,219);">
        <?php include('includes/footer.php'); ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
    <script type="text/javascript">
	
		function scrollToAnchor(aid) {
			var aTag = $(aid);
			$('html,body').animate({scrollTop: aTag.offset().top}, 'slow');
		}
		
		$(document).ready(function() {
			scrollToAnchor(location.hash);
		});
		
		$("#comment").click(function() {
			
			$.ajax({
                        type: "POST",
                        url: "api/shop-comment",
                        processData: false,
                        contentType: "application/json",
                        data: '{ "body": "'+ $("#body").val() +'", "user_id": "'+ <?php echo Login::isLoggedIn(); ?> +'", "shop_id": "'+ <?php echo $_GET['shop_id']; ?> +'" }',
                        success: function(r) {
							$(this).html();
                            console.log(r)
                        },
                        error: function(r) {
                                setTimeout(function() {
                                $('[data-bs-hover-animate]').removeClass('animated ' + $('[data-bs-hover-animate]').attr('data-bs-hover-animate'));
                                }, 2000)
                                $('[data-bs-hover-animate]').addClass('animated ' + $('[data-bs-hover-animate]').attr('data-bs-hover-animate'))
                                console.log(r)
                        }
                });
			
		});
	
	</script>
</body>

</html>

<style>

	#shopinfo {
		padding: 0 50px;
	}
	
	#shopimg {
		width:25vw;
		height:25vw;
	}
	
	@media only screen 
		 and (min-width: 320px) 
		 and (max-width: 768px) {
		
		#shopinfo {
			padding: 0 10px;
		}
		
		#shopimg {
		width:75vw;
		height:75vw;
		}
		
		#cprof {
			display: none;
		}
		
	}

</style>