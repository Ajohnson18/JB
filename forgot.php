<?php

	include('classes/DB.php');
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Contact-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/dh-card-image-left-dark.css">
    <link rel="stylesheet" href="assets/css/divider-text-middle.css">
    <link rel="stylesheet" href="assets/css/Features-Boxed.css">
    <link rel="stylesheet" href="assets/css/Footer-Basic.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Text-Input-1.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Text-Input.css">
    <link rel="stylesheet" href="assets/css/Header-Blue.css">
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
    <div class="login-clean" style="background-image:url(&quot;assets/img/stsinside.jpg&quot;);background-repeat:no-repeat;background-size:cover;background-position:center;">
        <form method="post" style="margin-top:39px;">
            <h2 class="sr-only">Login Form</h2>
            <div class="illustration"><i class="glyphicon glyphicon-question-sign"></i></div>
            <div class="error" style="text-align: center;"></div>
            <div class="form-group">
                <input class="form-control" type="email" id="email" name="email" placeholder="Email">
            </div>
            <div class="form-group"></div>
            <div class="form-group">
                <button id="send" class="btn btn-primary active btn-block" type="button" data-bs-hover-animate="shake">Send Information</button>
            </div>
            <a href="#" class="forgot"> </a>
        </form>
    </div>
    <div class="footer-basic" style="background-color:rgb(241,219,219);">
         <?php include('includes/footer.php'); ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!--<script src="assets/js/bs-animation.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
    <script type="text/javascript">
        $('#send').click(function() {
                $.ajax({
                        type: "POST",
                        url: "api/forgot",
                        processData: false,
                        contentType: "application/json",
                        data: '{ "email": "'+ $("#email").val() +'" }',
                        success: function(r) {
                                console.log(r)
								window.location.href="login.php";
                        },
                        error: function(r) {
                                setTimeout(function() {
                                $('[data-bs-hover-animate]').removeClass('animated ' + $('[data-bs-hover-animate]').attr('data-bs-hover-animate'));
                                }, 2000)
                                $('[data-bs-hover-animate]').addClass('animated ' + $('[data-bs-hover-animate]').attr('data-bs-hover-animate'))
								if(r.status == 402)
									$('.error').html('<p style="color: light-red; margin-top: -20px;">  Invalid Email!</p>')
                                console.log(r)
                        }
                });
        });
		
    </script>
</body>

</html>