<?php

	require_once("DB.php");
	require_once("Login.php");
	require_once("Mail.php");
	include("Post.php");

	$db = new DB("127.0.0.1", "johnsonboards", "root", "");
	
	if($_SERVER['REQUEST_METHOD'] == "GET") {
		
		if($_GET['url'] == "posts") {
			
			$token = $_COOKIE['SNID'];
			$start = (int)$_GET['start'];
			$userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
			
			$followingposts = $db->query('SELECT posts.*, users.username, users.profileimg FROM users, posts, followers
			WHERE posts.user_id = followers.user_id
			AND users.id = posts.user_id
			AND follower_id = :userid
			ORDER BY posts.id DESC
			LIMIT 5
			OFFSET '.$start.';', array(':userid'=>$userid));
			
			$response = "[";
			
			foreach($followingposts as $post) {
				
				$response.= "{";
					$response.= '"PostId": '.$post['id'].',';
					$response.= '"PostImage": "'.$post['postimg'].'",';
					$response.= '"ProfileImage": "'.$post['profileimg'].'",';
					$response.= '"PostBody": "'.$post['post_body'].'",';
					$response.= '"PostedBy": "'.$post['username'].'",';
					$response.= '"PostedOn": "'.$post['posted_at'].'",';
					$response.= '"Likes": '.$post['likes'].',';
					$response.= '"Comments": '.$post['comments'].'';
				$response.= "},";
			
			}
			$response = substr($response, 0, strlen($response)-1);
			$response.= "]";
			
			echo($response);

		} else if ($_GET['url'] == "profileposts") {
			
			$start = (int)$_GET['start'];
			$getuser = $_GET['username'];
			$userid = $db->query('SELECT id FROM users WHERE username=:username', array(':username'=>$getuser))[0]['id'];
			$posts = $db->query('SELECT posts.*, users.`username` FROM users, posts
                WHERE users.id = posts.user_id
                AND users.id = :userid
                ORDER BY posts.id DESC
				LIMIT 5
				OFFSET '.$start.';', array(':userid'=>$userid));
			
				
			$response = "[";
			
			foreach($posts as $post) {
				
				$profileimg = $db->query('SELECT profileimg FROM users WHERE id=:id', array(':id'=>$userid))[0]['profileimg'];
				$username = $db->query('SELECT username FROM users WHERE id=:id', array(':id'=>$userid))[0]['username'];
				
				$response.= "{";
					$response.= '"PostId": '.$post['id'].',';
					$response.= '"PostById": '.$post['user_id'].',';
					$response.= '"PostImage": "'.$post['postimg'].'",';
					$response.= '"ProfileImage": "'.$profileimg.'",';
					$response.= '"PostBody": "'.$post['post_body'].'",';
					$response.= '"PostedBy": "'.$username.'",';
					$response.= '"PostedOn": "'.$post['posted_at'].'",';
					$response.= '"Likes": '.$post['likes'].',';
					$response.= '"Comments": '.$post['comments'].'';
				$response.= "},";
			
			}
			$response = substr($response, 0, strlen($response)-1);
			$response.= "]";
			
			echo($response);
			
		} else if ($_GET['url'] == "comments" && isset($_GET['post_id'])) {
		
			$comments = $db->query('SELECT comments.*, users.username FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id ORDER BY comments.id DESC', array(':postid'=>$_GET['post_id']));
			
			$output = "";
			$output .= "[";
			
			foreach($comments as $comment) {
				
				$output .= "{";
				$output .= '"Comment": "'.$comment['comment'].'",';
				$output .= '"CommentedBy": "'.$comment['username'].'",';
				$output .= '"CommentPostId": "'.$comment['post_id'].'",';
				$output .= '"CommentId": "'.$comment['id'].'",';
				$output .= '"CommentedById": "'.$comment['user_id'].'"';
				$output .= "},";
			
				}
			$output = substr($output, 0, strlen($output)-1);
			$output .= "]";
			echo $output;
		} else if($_GET['url'] == "shop-comments") {
			
			$token = $_COOKIE['SNID'];
			$userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
			
			$shopposts = $db->query('SELECT shop_comment.*, users.username FROM users, shop_comment
			WHERE posts.shop_id = :shop,
			AND users.id = :userid
			ORDER BY shop_comment.id DESC;', array(':userid'=>$userid, ':shop'=>$_GET['shop_id']));
			
			$response = "[";
			
			foreach($shopposts as $post) {
				
				$response.= "{";
					$response.= '"PostId": '.$post['id'].',';
					$response.= '"PostBody": "'.$post['comment'].'",';
					$response.= '"PostedBy": "'.$post['username'].'"';
				$response.= "},";
			
			}
			$response = substr($response, 0, strlen($response)-1);
			$response.= "]";
			
			echo($response);
		}
		
	} else if($_SERVER['REQUEST_METHOD'] == "POST") {
		
		$token = $_COOKIE['SNID'];
		
		$postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);
		
		if ($_GET['url'] == "auth") {
			
                $postBody = file_get_contents("php://input");
                $postBody = json_decode($postBody);
			
                $username = $postBody->username;
                $password = $postBody->password;
			
                if ($db->query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
                        if (password_verify($password, $db->query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) { 
								$cstrong = True;
                                $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                                $user_id = $db->query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
                                $db->query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                                echo '{ "Token": "'.$token.'" }';
							
								setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
								setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
							
                        } else {
                                echo '{ "Error": "Invalid username or password!" }';
                                http_response_code(401);
                        }
                } else {
                        echo '{ "Error": "Invalid username or password!" }';
                        http_response_code(401);
                }
		} else if($_GET['url'] == "users") {	
			
			$postBody = file_get_contents("php://input");
			$postBody = json_decode($postBody);
			
			$username = $postBody->username;
			$email = $postBody->email;
			$password = $postBody->password;
			
			if (!$db->query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {

                if (strlen($username) >= 3 && strlen($username) <= 32) {

                        if (preg_match('/[a-zA-Z0-9_]+/', $username)) {

                                if (strlen($password) >= 6 && strlen($password) <= 60) {

                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                                if (!$db->query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {

                                       $db->query('INSERT INTO users VALUES (\'\', :username,  :email, :password, \'\', \'\', \'\', \'\', \'assets/img/default-profile.png\', \'\', \'assets/img/default-banner.png\', 0)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                                       echo '{ "Success": "User Created!" }';
                                       http_response_code(200);
									
										
									
										$message = '<div id="welcome-email" style="width: 500px;background: blue;text-align: center;font-family: \'Verdana\';background-image:url(&quot;https://media.timeout.com/images/103259929/image.jpg&quot;);background-size:cover;">
										<div style="background-color:rgba(20,19,19,0.7);">
										<img src="https://i.imgur.com/IoLoB4F.png" style="margin-top:34px;margin-bottom:-37px;margin-right:-9px;">
										<h1 style="margin-top:22px;padding:24px;margin-right:-12px;color: white;"><strong>Welcome!</strong> </h1>
											<img src="http://i.imgur.com/DZNeXlV.png" style="width:475px;">
											<p style="padding:29px;margin-top:-26px;font-size:19px;background-color:rgba(251,248,248,0);color:rgb(249,246,246);">
											Thank you so much for creating an account with us at JohnsonBoards! We hope that you can learn alot about the Jersey Shore Surf Community and communicate with others with the same goal. There is plenty of stuff to do with your new account! Try posting your first post, or updating your profile. Please enjoy all that we have laid out for you and I hope you find this sight enjoyable!</p>
											<p style="margin-top:-10px;font-size:21px;color:rgb(232,232,232);background-color:rgba(7,7,7,0);">
											~ Alex Johnson (Creator of JohnsonBoards)</p>
											<img src="http://i.imgur.com/DZNeXlV.png" style="width:471px;margin-top:-8px;">
											<p style="color:rgb(248,248,248);"><strong>Profile Information:</strong><br />
											Username: '.$username.'<br />
											Password: '.$password.'<br />
											Email: '.$email.'<br /><br />
											Website: <a href="johnsonboards.com" style="color: lightgrey">JohnsonBoards.com</a><br /><br />
											<a href="johnsonboards.com/profile.php?username='.$username.'" style="color: lightgrey">Click HERE To Go To Your Profile!</a>
											</p>
										</div>
									</div>;';

									Mail::sendMail('Welcome To JohnsonBoards!', $message, $email);
									
									
									
                                } else {
                                    echo '{ "Error": "Email in use!" }';
                                    http_response_code(404);
                                }
                        } else {
                                echo '{ "Error": "Invalid Email!" }';
                                http_response_code(405);
                                }
                        } else {
                               echo '{ "Error": "Invalid Password!" }';
                               http_response_code(406);
                        }
                        } else {
                                echo '{ "Error": "Invalid Username!" }';
                                http_response_code(407);
                        }
                } else {
                        echo '{ "Error": "Invalid Username!" }';
                        http_response_code(408);
                }

			} else {
					echo '{ "Error": "User exists!" }';
                    http_response_code(409);
			}
			
		} else if($_GET['url'] == "likes") {
			
			$postid = $_GET['id'];
			$token = $_COOKIE['SNID'];
			$likerid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
			$posterid = $db->query('SELECT user_id FROM posts WHERE id=:id', array(':id'=>$postid))[0]['user_id'];
			
			if (!$db->query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postid, ':userid'=>$likerid))) {
				$db->query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postid));
				$db->query('INSERT INTO post_likes VALUES (\'\', :postid, :userid)', array(':postid'=>$postid, ':userid'=>$likerid));
			} else {
				$db->query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postid));
				$db->query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postid, ':userid'=>$likerid));
			}
			
			echo "{";
			echo '"Likes":';
			echo $db->query('SELECT likes FROM posts WHERE id=:postid', array(':postid'=>$postid))[0]['likes'];
			echo "}";
			
		} else if ($_GET['url'] == "create-comment") {
		
			$postBody = file_get_contents("php://input");
			$postBody = json_decode($postBody);
			
			$commentbody = $postBody->comment;
			$token = $_COOKIE['SNID'];
			$userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
			
			$db->query('UPDATE posts SET comments=comments+1 WHERE id=:postId', array(':postId'=>$_GET['id']));
			if(strlen($commentbody) > 160 || strlen($commentbody) < 1) {
				
				echo '{ "Error": "Comment length invalid" }';
                http_response_code(409);
				
			} else {
				
				if(!$db->query('SELECT id FROM posts WHERE id=:postid', array(':postid'=>$_GET['id']))) {
					
					echo '{ "Error": "Postid Invalid!" }';
                    http_response_code(409);
					
				} else {		
					
					$db->query('INSERT INTO comments VALUES (\'\', :comment, :postid, NOW(), :userid, \'\')', array(':comment'=>$commentbody, ':userid'=>$userid, ':postid'=>$_GET['id']));
					
					
					$comments = $db->query('SELECT comments.*, users.username FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id ORDER BY comments.id DESC', array(':postid'=>$_GET['id']));
					
					$output = "";
					$output .= "[";

					foreach($comments as $comment) {

						$output .= "{";
						$output .= '"Comment": "'.$comment['comment'].'",';
						$output .= '"CommentedBy": "'.$comment['username'].'",';
						$output .= '"CommentedById": "'.$comment['user_id'].'",';
						$output .= '"CommentPost": "'.$comment['post_id'].'",';
						$output .= "},";

						}
					$output = substr($output, 0, strlen($output)-1);
					$output .= "]";
					echo $output;

				}
			}
			
		} else if($_GET['url'] == "send") {
			
			$postBody = file_get_contents("php://input");
			$postBody = json_decode($postBody);
			
			$name = $postBody->name;
			$email = $postBody->email;
			$oldmessage = $postBody->message;
			
			if(strlen($name) != 0) {
				if(strlen($email) != 0) {
					if(strlen($oldmessage) != 0) {
						$message = "<html><head><title>Message</title></head><body style=\"text-align:center\">";
						$message .= "<h3>Name: ".$name."</h3>";
						$message .= "<br />Email: ".$email."<br /><br />";
						$message .= "<p>".$oldmessage."</p>";
						
						$headers[] = 'MIME-Version: 1.0';
						$headers[] = 'Content-type: text/html; charset=iso-8859-1';
						
						$headers = 'From: '.$name.' <'.$email.'>';
						
						Mail::sendMail($headers, $message, 'ajohnso18@gmail.com');
												
						echo '{ "Success": "Email Sent!" }';
                		http_response_code(200);
						
					} else {
						echo '{ "Error": "Message Invalid!" }';
                		http_response_code(409);
					}
				} else {
					echo '{ "Error": "Email Invalid!" }';
                	http_response_code(409);
				}
			} else {
				echo '{ "Error": "Name Invalid!" }';
                http_response_code(408);
			}
			
		} else if($_GET['url'] == "forgot") {
			$postBody = file_get_contents("php://input");
			$postBody = json_decode($postBody);
			
			$email = $postBody->email;
			
			if($db->query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {
				$username = $db->query('SELECT username FROM users WHERE email=:email', array(':email'=>$email))[0]['username'];
				$password = $db->query('SELECT password FROM users WHERE email=:email', array(':email'=>$email))[0]['password'];
				
				$new_password = generateRandomString();
				
				$db->query('UPDATE users SET password=:password WHERE email=:email', array(':password'=>password_hash($new_password, PASSWORD_BCRYPT), ':email'=>$email));
				
				$message = '<div id="welcome-email" style="width: 500px;background: blue;text-align: center;font-family: \'Verdana\';background-image:url(&quot;https://media.timeout.com/images/103259929/image.jpg&quot;);background-size:cover;">
										<div style="background-color:rgba(20,19,19,0.7);">
										<img src="https://i.imgur.com/IoLoB4F.png" style="margin-top:34px;margin-bottom:-37px;margin-right:-9px;">
											<img src="http://i.imgur.com/DZNeXlV.png" style="width:471px;margin-top:-8px;">
											<p style="color:rgb(248,248,248);"><strong>Profile Information:</strong><br />
											Username: '.$username.'<br />
											Password: '.$new_password.'<br />
											Email: '.$email.'<br /><br />
											Website: <a href="johnsonboards.com" style="color: lightgrey">JohnsonBoards.com</a><br /><br />
											P.S. We highly recommend changing your password when you log back in!
											</p>
										</div>
									</div>;';

				Mail::sendMail('JohnsonBoards Password Change', $message, $email);
				
			} else {
				echo '{ "Error": "Invalid Email" }';
                http_response_code(402);
			}
			
		} else if ($_GET['url'] == "change") {
			$postBody = file_get_contents("php://input");
			$postBody = json_decode($postBody);
			
			$oldpassword = $postBody->oldpassword;
			$newpassword = $postBody->newpassword;
			$repeatpassword = $postBody->repeatpassword;
			
			$token = $_COOKIE['SNID'];
			
			$userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
			
			$username = $db->query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];
			
			if (password_verify($oldpassword, $db->query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {
				if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {	
					if($newpassword == $repeatpassword) {

						$db->query('UPDATE users SET password=:password WHERE username=:username', array(':password'=>password_hash($newpassword, PASSWORD_BCRYPT), ':username'=>$username));

						echo '{ "Status": "Success!!!" }';
						http_response_code(200);

					} else {
						echo '{ "Error": "Passwords do not match!" }';
						http_response_code(401);
					}	
				} else {
					echo '{ "Error": "Passwords length invalid!" }';
					http_response_code(403);
				}
			} else {
				echo '{ "Error": "Invalid Password" }';
                http_response_code(402);
			}
		} else if ($_GET['url'] == "delete-post") {
			$postid = $_GET['postid'];
			$token = $_COOKIE['SNID'];
			$userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
			$user_id = $db->query('SELECT user_id FROM posts WHERE id=:id', array(':id'=>$postid))[0]['user_id'];
			
			if($userid == $user_id) {
				$db->query('DELETE FROM comments WHERE post_id=:id', array(':id'=>$postid));
				$db->query('DELETE FROM post_likes WHERE post_id=:id', array(':id'=>$postid));
				$db->query('DELETE FROM posts WHERE id=:id', array(':id'=>$postid));
				
				echo '{ "Status": "'.$postid.'" }';
            	http_response_code(200);
				
			} else {
				echo '{ "Error": "Invalid User" }';
				http_response_code(401);
			}
		}
	
		
		
	} else if($_SERVER['REQUEST_METHOD'] == "DELETE") {
		
		if ($_GET['url'] == "auth") {
                if ($db->query('SELECT token FROM login_tokens WHERE user_id=:userid', array(':userid'=>$login->isLoggedIn()))) {
						$ltoken = $db->query('SELECT token FROM login_tokens WHERE user_id=:userid', array(':userid'=>$login->isLoggedIn()));
                        if ($db->query("SELECT token FROM login_tokens WHERE token=:token", array(':token'=>sha1($ltoken)))) {
                                $db->query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($ltoken)));
                                echo '{ "Status": "Success" }';
                                http_response_code(200);
                        } else {
                                echo '{ "Error": "Invalid token" }';
                                http_response_code(400);
                        }
                } else {
                        echo '{ "Error": "Malformed request" }';
                        http_response_code(400);
                }
        }
		
	} else {
		http_response_code(405);
	}

	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

?>