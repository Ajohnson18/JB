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
    <?php include('includes/nav.php'); ?>
    <div class="login-clean" style="background-image:url(&quot;assets/img/easterninside.jpg&quot;);background-repeat:no-repeat;background-size:cover;background-position:center;">
        <form method="post" style="margin-top:50px;">
            <h2 class="sr-only">Login Form</h2>
            <div class="illustration"><i class="icon ion-locked"></i></div>
            <div class="error" style="text-align: center;"></div>
            <div class="form-group">
                <input class="form-control" type="text" id="username" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <input class="form-control" type="email" id="email" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" id="password" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" name="create-account" id="create" type="button" data-bs-hover-animate="shake">Create Account</button>
            </div><a href="login.php" class="forgot">Already have an account? Click here!</a></form>
    </div>
    <div class="footer-basic" style="background-color:rgb(241,219,219);">
        <?php include('includes/footer.php'); ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!--<script src="assets/js/bs-animation.js"></script>-->
    <script type="text/javascript">
	
		$("#create").click(function() {
			
			$.ajax({
                        type: "POST",
                        url: "api/users",
                        processData: false,
                        contentType: "application/json",
                        data: '{ "username": "'+ $("#username").val() +'", "email": "'+ $("#email").val() +'", "password": "'+ $("#password").val() +'" }',
                        success: function(r) {
                                console.log(r)
								window.location.href="login.php";
                        },
                        error: function(r) {
							
                                setTimeout(function() {
                                $('[data-bs-hover-animate]').removeClass('animated ' + $('[data-bs-hover-animate]').attr('data-bs-hover-animate'));
                                }, 2000)
                                $('[data-bs-hover-animate]').addClass('animated ' + $('[data-bs-hover-animate]').attr('data-bs-hover-animate'))
								
								if(r.status == 409)
									$('.error').html('<p style="color: light-red; margin-top: -20px;">  Username In Use!</p>')
								if(r.status == 408)
									$('.error').html('<p style="color: light-red; margin-top: -20px;">  Invalid Username Length!</p>')
								if(r.status == 407)
									$('.error').html('<p style="color: light-red; margin-top: -20px;">  Invalid Username!</p>')
								if(r.status == 406)
									$('.error').html('<p style="color: light-red; margin-top: -20px;">  Invalid Password!</p>')
								if(r.status == 405)
									$('.error').html('<p style="color: light-red; margin-top: -20px;">  Invalid Email!</p>')
								if(r.status == 404)
									$('.error').html('<p style="color: light-red; margin-top: -20px;">  Email In Use!</p>')	
									
                                console.log(r)
                        }
                });
			
		});
	
	</script>
    
</body>

</html>