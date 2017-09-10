<?php
include('classes/DB.php');
include('classes/Image.php');
include('classes/Login.php');

if(!Login::isLoggedIn()) {
	die('User Not Logged In');
}

if(DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))) {
	$username = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['username'];
}

if (isset($_POST['save'])) {
	
	$first_name = $_POST['firstName'];
	$last_name = $_POST['lastName'];
	$town = $_POST['town'];
	$break = $_POST['fbreak'];
	$desc = $_POST['desc'];
		
	DB::query('UPDATE users SET first_name=:firstname, last_name=:lastname, town=:town, fbreak=:fbreak, description=:desc WHERE id=:userid', array(':firstname'=>$first_name, ':lastname'=>$last_name, ':town'=>$town, ':fbreak'=>$break, ':desc'=>$desc, ':userid'=>Login::isLoggedIn()));
	
	if($_FILES['profileimg']['size'] != 0 && $_FILES['profileimg']['error'] == 0) {
		Image::uploadImage('profileimg', 'UPDATE users SET profileimg = :profileimg WHERE id=:userid', array(':userid'=>Login::isLoggedIn()));
	}
	if($_FILES['bannerimg']['size'] != 0 && $_FILES['bannerimg']['error'] == 0) {
		Image::uploadImage('bannerimg', 'UPDATE users SET bannerimg = :bannerimg WHERE id=:userid', array(':userid'=>Login::isLoggedIn()));
	}
	header("Location: profile.php?post_id=0&commentopen=false&username=".$username); 
	exit();
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
    <div class="register-photo">
        <div class="form-container" style="margin-top:51px;">
            <div class="image-holder" style="background-image:url(&quot;assets/img/forumlink.jpg&quot;);"></div>
            <form method="post" action="my-account.php" enctype="multipart/form-data">
                <h2 class="text-center"><strong>Edit</strong> your account.<br /><a href="change-password.php" style="font-size: 80%;">Change Password</a></h2>
				
                <div class="form-group">
                    <input class="form-control" type="text" name="firstName" placeholder="First Name" 
                    <?php
						if(DB::query('SELECT first_name FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn())))
							echo 'value="'.DB::query('SELECT first_name FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['first_name'].'"'
					?>>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="lastName" placeholder="Last Name"
                    <?php
						if(DB::query('SELECT last_name FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn())))
							echo 'value="'.DB::query('SELECT last_name FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['last_name'].'"'
					?>>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="town" placeholder="Town"
                    <?php
						if(DB::query('SELECT town FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn())))
							echo 'value="'.DB::query('SELECT town FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['town'].'"'
					?>>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="fbreak" placeholder="Favorite Break"
                    <?php
						if(DB::query('SELECT fbreak FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn())))
							echo 'value="'.DB::query('SELECT fbreak FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['fbreak'].'"'
					?>>
                </div>
                <div class="form-group">
                    <input class="form-control" type="textarea" name="desc" rows="4" cols="50" placeholder="Profile Description"
                    <?php
						if(DB::query('SELECT description FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn())))
							echo 'value="'.DB::query('SELECT description FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['description'].'"'
					?>>
                </div>
                <div style="background:#f7f9fc;border:none;border-bottom:1px solid #dfe7f1;border-radius:0;box-shadow:none;outline:none;color:inherit;text-indent:6px;height:50px;">
                    <p style="margin-bottom:-9px;margin-right:1px;margin-left:4px;font-size:12px;">Profile Image:</p>
                    <input type="file" style="width:262px;border:none;font-size:11px;" name="profileimg">
                </div>
                <div style="background:#f7f9fc;border:none;border-bottom:1px solid #dfe7f1;border-radius:0;box-shadow:none;outline:none;color:inherit;text-indent:6px;height:50px;">
                    <p style="margin-bottom:-9px;margin-right:1px;margin-left:4px;font-size:12px;">Banner Image:</p>
                    <input type="file" style="width:262px;border:none;font-size:11px;" name="bannerimg">
                </div>
                <div class="form-group"></div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit" name="save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <div class="footer-basic" style="background-color:rgb(241,219,219);">
       <?php include('includes/footer.php'); ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
</body>

</html>