<?php

	include('classes/Login.php');
	include('classes/DB.php');
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
    <link rel="stylesheet" href="assets/css/Contact-Form-Clean.css">
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
    <div class="contact-clean" style="background-image:url(&quot;assets/img/bareinside.jpg&quot;);background-size:cover;background-repeat:no-repeat;background-position:center; margin-top: 60px;">
        <form method="post" style="margin-top:28px;margin-bottom:-17px;box-shadow:5px 5px 5px grey;margin-bottom: 40px;">
            <h2 class="text-center">Contact us</h2>
            <div class="form-group has-success has-feedback">
                <input class="form-control" type="text" name="name" id="name" placeholder="Name"><!--<i class="form-control-feedback glyphicon glyphicon-ok" aria-hidden="true"></i></div>-->
                <br />
			</div>    
            <div class="form-group has-error has-feedback">
                <input class="form-control" type="email" id="email" name="email" placeholder="Email"><!--<i class="form-control-feedback glyphicon glyphicon-remove" aria-hidden="true"></i>-->
                <!--<p class="help-block">Please enter a correct email address.</p>-->
            </div>
            <br />
            <div class="form-group">
                <textarea class="form-control" rows="14" id="message" name="message" placeholder="Message"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="button" id="submit" data-bs-hover-animate="shake">send </button>
            </div>
        </form>
    </div>
    <div class="footer-basic" style="background-color:rgb(241,219,219);">
        <?php include('includes/footer.php'); ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
    <script type="text/javascript">
        $('#submit').click(function() {
                $.ajax({
                        type: "POST",
                        url: "api/send",
                        processData: false,
                        contentType: "application/json",
                        data: '{ "name": "'+ $("#name").val() +'", "email": "'+ $("#email").val() +'", "message": "'+ $("#message").val() +'" }',
                        success: function(r) {
                                console.log(r)
								window.location.href="thank-you.php";
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