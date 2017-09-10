<?php

	class Comment {
		public static function createComment($commentbody, $postId, $userid) {
			DB::query('UPDATE posts SET comments=comments+1 WHERE id=:postId', array(':postId'=>$postId));
			$topics = Post::getTopics($commentbody);
			if(strlen($commentbody) > 160 || strlen($commentbody) < 1) {
				echo '<script type=\'text/javascript\'>alert(\'Post is too long or too short!\');</script>';
			} else {
				if(!DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid'=>$postId))) {
					die('Invalid Post Id;');
				} else {
					
					if(!$userid == Login::isLoggedIn()) {
						//DB::query('UPDATE users SET notifications=notifications+1 WHERE id=:userid', array(':userid'=>$userid));
						//Notify::createNotify($commentbody, $postId);
					}
					
					DB::query('INSERT INTO comments VALUES (\'\', :comment, :postid, NOW(), :userid, :topics)', array(':comment'=>$commentbody, ':userid'=>$userid, ':postid'=>$postId, ':topics'=>$topics));
				}
			}
			
			
			
		}
		
		public static function displayComments($postId, $page, $username) {
			$comments = DB::query('SELECT comments.comment, comments.id, users.username, comments.user_id FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id', array(':postid'=>$postId));
			foreach($comments as $comment) {
				echo (Post::link_add($comment['comment']))." ~ ".$comment['username'];
				if(Login::isLoggedIn() == $comment['user_id']) {
					echo '<form action="' .  $page . '.php?username=' . $username  .  '&id=' . $comment['id'] . '" method="post" style="margin-bottom: 30px; margin-top: -20px;">
						<input type="submit" name="delete-comment" value="Delete Comment" />
					</form>';
				}
				echo '<hr />';
			}
		}
	}

?>