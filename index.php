<?php

	include('classes/Login.php');
	include('classes/DB.php');

	if(DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>Login::isLoggedIn()))) {
		$username = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>Login::isLoggedIn()))[0]['username'];
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
    <link rel="stylesheet" href="assets/css/Features-Boxed.css">
    <link rel="stylesheet" href="assets/css/Footer-Basic.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div>
        <?php include('includes/nav.php'); ?>
    </div>
    <section style="height:100vh;background-image:url(&quot;assets/img/header_background.jpg&quot;);position:absolute;top:0;left:0;width:100%;background-size:cover;">
        <hr style="margin-top:29vh;margin-bottom:47px;width:80%;">
        <h1 class="text-nowrap" style="text-align:center;font-family:Quicksand, sans-serif;">JohnsonBoards </h1>
        <p id="smalltext" style="font-size:1.5em;text-align:center;font-family:Quicksand, sans-serif;padding: 0 20px;">A connection to the roots of local surf culture on the Jersey Shore.</p>
        <hr style="padding-top:0;margin-top:55px;width:80%;">
    </section>
    <div class="features-boxed" style="margin-top:100vh; text-align:center;">
        <div class="container" style="margin-top:100vh;">
            <div class="intro">
                <h2 class="text-center">Welcome. </h2>
                <p class="text-center" style="height:92px;">Here at JohnsonBoards we are dedicated to connecting and building up a surf community on the Jersey Shore. The only way to do this is through the help of the community. Check out the links down below to learn more.</p>
            </div>
            <div class="row features">
                <div class="col-md-4 col-sm-6 item">
                    <div class="box" style="min-height:350px;"><i class="glyphicon glyphicon-shopping-cart icon"></i>
                        <h3 class="name">Shops </h3>
                        <p class="description">Check out all the local surf shops and buisnesses in your area. </p><a href="shops.php?shop_id=0" class="learn-more">Learn more »</a></div>
                </div>
                <div class="col-md-4 col-sm-6 item">
                    <div class="box" style="min-height:350px;"><i class="glyphicon glyphicon-road icon"></i>
                        <h3 class="name">Spots </h3>
                        <p class="description">Explore the amazing surf spots New Jersey has to offer up and down it's coast.</p><a href="spots.php?spot_id=0" class="learn-more">Learn more »</a></div>
                </div>
                <div class="col-md-4 col-sm-6 item">
                    <div class="box" style="min-height:350px;"><i class="glyphicon glyphicon-search icon"></i>
                        <h3 class="name">Search </h3>
                        <p class="description">Search for users, surf spots, shops, and more.</p><a href="search.php" class="learn-more">Learn more »</a></div>
                </div>
                <div class="col-md-4 col-sm-6 item">
                    <div class="box" style="min-height:350px;"><i class="glyphicon glyphicon-list icon"></i>
                        <h3 class="name">Feed </h3>
                        <p class="description">See what your followers have been saying about the local surf.</p><a href="feed.php?commentopen=false&post_id=0" class="learn-more">Learn more »</a></div>
                </div>
                <div class="col-md-4 col-sm-6 item">
                    <div class="box" style="min-height:350px;"><i class="glyphicon glyphicon-earphone icon"></i>
                        <h3 class="name">Contact </h3>
                        <p class="description">Have an issue, testimonial, or question? Contact us.</p><a href="contact.php" class="learn-more">Learn more »</a></div>
                </div>
                <div class="col-md-4 col-sm-6 item">
                    <div class="box" style="min-height:350px;"><i class="fa fa-user icon"></i>
                        <h3 class="name">Your Profile</h3>
                        <p class="description">Edit your profile and tell the world about your surf adventures.</p><a href="profile.php?username=<?php echo $username; ?>&commentopen=false&post_id=0" class="learn-more">Learn more »</a></div>
                </div>
            </div>
        </div>
        <div id="wrapinsta">
        <img src="assets/img/shakaline.png" style="width:40%;margin-bottom:-70px;">

            <h3 style="
font-size: 300%;
color: darkslategrey;
font-family: quicksand;
border-bottom: 2px solid black;
width: 40%;
padding-bottom: 10px;
margin: 40px auto;text-align:center;
">Follow Us On Instagram!</h3>
<section id="instafeed">
	<script src="https://snapwidget.com/js/snapwidget.js"></script>
	<iframe src="https://snapwidget.com/embed/371049" class="snapwidget-widget" allowTransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%; "></iframe>	
	<br />
	<br />
	</div>
</section>
    </div>

    <div class="footer-basic" style="background-color:rgb(241,219,219);">
        <?php include('includes/footer.php'); ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
</body>

</html>


<style>

	.text-nowrap {
		font-size:5em;
	}

@media only screen 
  and (min-width: 320px) 
  and (max-width: 768px) {
	
	.text-nowrap {
		font-size:2.5em;
		color: white;
	}
	
	#smalltext {
		color: white;
	}
	
	#wrapinsta {
		display: none;
	}
	
}

</style>