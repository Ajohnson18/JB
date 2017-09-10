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
    <div class="login-clean" style="background-image:url(&quot;assets/img/inletinside.jpg&quot;);background-repeat:no-repeat;background-size:cover;background-position:center;">
        <form method="post" style="margin-top:50px;">
            <h2 class="sr-only">Change-Password Form</h2>
            <div class="illustration"><i class="icon ion-locked"></i></div>
            <div class="error" style="text-align: center;"></div>
            <div class="form-group">
                <input class="form-control" type="text" id="oldpassword" name="oldpassword" placeholder="Current Password">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" id="password" name="password" placeholder="New-Password">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" id="passwordr" name="passwordr" placeholder="Repeat-New-Password">
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" name="change-password" id="change" type="button" data-bs-hover-animate="shake">Change Password</button>
            </div></form>
    </div>
    <div class="footer-basic" style="background-color:rgb(241,219,219);">
        <?php include('includes/footer.php'); ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!--<script src="assets/js/bs-animation.js"></script>-->
    <script type="text/javascript">
	
		$("#change").click(function() {
			
			$.ajax({
                        type: "POST",
                        url: "api/change",
                        processData: false,
                        contentType: "application/json",
                        data: '{ "oldpassword": "'+ $("#oldpassword").val() +'", "newpassword": "'+ $("#password").val() +'", "repeatpassword": "'+ $("#passwordr").val() +'" }',
                        success: function(r) {
                                console.log(r)
								$('.error').html('<p style="color: light-red; margin-top: -20px;">  Password Changed Successfully!</p>')
								setTimeout(function() { window.location.href="index.php"; }, 2000);
                        },
                        error: function(r) {
							
                                setTimeout(function() {
                                $('[data-bs-hover-animate]').removeClass('animated ' + $('[data-bs-hover-animate]').attr('data-bs-hover-animate'));
                                }, 2000)
                                $('[data-bs-hover-animate]').addClass('animated ' + $('[data-bs-hover-animate]').attr('data-bs-hover-animate'))
								
								if(r.status == 402)
									$('.error').html('<p style="color: light-red; margin-top: -20px;">  Invalid Password!</p>')
								if(r.status == 401)
									$('.error').html('<p style="color: light-red; margin-top: -20px;">  Passwords Do Not Match!</p>')
								if(r.status == 403)
									$('.error').html('<p style="color: light-red; margin-top: -20px;">  Password Length Invalid!</p>')	
									
                                console.log(r)
                        }
                });
			
		});
	
	</script>
    
</body>

</html>