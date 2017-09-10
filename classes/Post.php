<?php

	class Post {
		public static function createPost($postbody, $loggedInUserId, $userid) {	
			if(strlen($postbody) > 260 || strlen($postbody) < 1) {
				echo '<script type=\'text/javascript\'>alert(\'Post is too long or too short!\');</script>';
			} else {
				$topics = self::getTopics($postbody);	
			
				/*if(count(Notify::createNotify($postbody)) != 0) {
						foreach(Notify::createNotify($postbody) as $key => $n) {
							$s = $loggedInUserId;
							$r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
							if($r != 0) {
								DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :extra)', array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n["extra"]));
								DB::query('UPDATE users SET notifications=notifications+1 WHERE id=:userid', array(':userid'=>$r));
							}
					}
				}*/
				DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0, 0, \'\', :topics)', array(':postbody'=>$postbody, ':userid'=>$userid, ':topics'=>$topics));
			}
		}
		
		public static function createImgPost($postbody, $loggedInUserId, $profileUserId) {
				$topics = self::getTopics($postbody);
                if (strlen($postbody) > 160) {
                        die('Incorrect length!');
                }
                if ($loggedInUserId == $profileUserId) {
					
					/*if(count(Notify::createNotify($postbody)) != 0) {
						foreach(Notify::createNotify($postbody) as $key => $n) {
							$s = $loggedInUserId;
							$r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
							if($r != 0) {
								DB::query('INSERT INTO notifications VALUES (\'\', :type, :receiver, :sender, :extra)', array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n["extra"]));
								DB::query('UPDATE users SET notifications=notifications+1 WHERE id=:userid', array(':userid'=>$r));
							}
						}
					}*/
					
                        DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :userid, 0, 0, \'\', :topics)', array(':postbody'=>$postbody, ':userid'=>$profileUserId, ':topics'=>$topics));
                        $postid = DB::query('SELECT id FROM posts WHERE user_id=:userid ORDER BY ID DESC LIMIT 1;', array(':userid'=>$loggedInUserId))[0]['id'];
                        return $postid;
                } else {
                        die('Incorrect user!');
                }
        }
		
		public static function likePost($postid, $likerid) {
			if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postid, ':userid'=>$likerid))) {
				DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postid));
				DB::query('INSERT INTO post_likes VALUES (\'\', :postid, :userid)', array(':postid'=>$postid, ':userid'=>$likerid));
				if(!$likerid == Login::isLoggedIn()) {
					DB::query('UPDATE users SET notifications=notifications+1 WHERE id=:userid', array(':userid'=>Login::isLoggedIn()));
					Notify::createNotify("", $postid);
				}
			} else {
				DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postid));
				DB::query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postid, ':userid'=>$likerid));
			}
		}
			 
		public static function link_add($text) {
            $text = explode(" ", $text);
            $newstring = "";
            foreach ($text as $word) {
                if (substr($word, 0, 1) == "@") {
					if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>substr($word, 1)))) {
                        $newstring .= "<a href='profile.php?username=".substr($word, 1)."&post_id=0&commentopen=false' style='color: #F012BE;'>".htmlspecialchars($word)."</a> ";
					} else {
						$newstring .= htmlspecialchars($word)." ";
					}
                } else if (substr($word, 0, 1) == "#") {
                    $newstring .= "<a href='topics.php?topic=".substr($word, 1)."' style='color: #F012BE;'>".htmlspecialchars($word)."</a> ";
                } else {
                    $newstring .= htmlspecialchars($word)." ";
                }
				}
			return $newstring;
		}
		
		public static function getTopics($text) {
			
			$text = explode(" ", $text);
			$topics ="";
			foreach($text as $word) {	
				if(substr($word, 0, 1) == "#") {
					$topics .= substr($word, 1).",";
				}	
			}
			return $topics;
		}
		
	}

?>