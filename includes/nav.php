<nav class="navbar navbar-default navigation-clean-button" style="background-color:#ebdcdc;position:absolute;top:0;left:0;width:100%;z-index:99;">
        <div class="container">
            <div class="navbar-header"><a class="navbar-brand navbar-link" style="font-family:quicksand;font-size:22px;" href="index.php">JohnsonBoards </a>
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav">
                    <li role="presentation"><a href="shops.php?shop_id=0">Shops </a></li>
                    <li role="presentation"><a href="spots.php">Spots </a></li>
                    <li role="presentation"><a href="search.php">Search </a></li>
                    <li role="presentation"><a href="feed.php?commentopen=false&post_id=0">Feed </a></li>
                    <li role="presentation"><a href="contact.php" style="padding-right:10px;">Contact </a></li>
                </ul>
                <?php
				
                if(!Login::isLoggedIn()) {
					echo '<p class="navbar-text navbar-right actions"><a class="navbar-link login" href="login.php">Login</a> <a class="btn btn-default action-button" role="button" href="create-account.php">Sign Up</a></p>';
				} else {
					$username = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['username'];
					echo '<div style="width: 200px;float: right; text-align: center;"><p class="navbar-text navbar-right actions" style="text-align: center;"><a class="navbar-link login" href="login.php">Welcome</a><a href="profile.php?username='.$username.'&commentopen=false&post_id=0">';
					$first = DB::query('SELECT first_name FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['first_name'];
					$last = DB::query('SELECT last_name FROM users WHERE id=:userid', array(':userid'=>Login::isLoggedIn()))[0]['last_name'];
					if($first != '' && $last != '') {
						echo ($first . " " . $last);
					} else {
						echo $username; 
					}
					echo '</a><br /><form action="logout.php" method="post" style="width: 100px; margin-left: 100px;"><input type="submit" name="confirm" value="Logout" class="btn btn-default action-button" style="height: 20px; padding-top: 0;width:100px;" ></form></p></div>';
				}
				
				?>
            </div>
        </div>
    </nav>