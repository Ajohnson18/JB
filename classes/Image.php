<?php

	class Image {
		public static function uploadImage($formname, $query, $params) {
			$image = base64_encode(file_get_contents($_FILES[$formname]['tmp_name']));
			$options = array('http'=>array(
                'method'=>"POST",
                'header'=>"Authorization: Bearer d0c4f99bafb7a335d546c415b6bbc2502bc946b4\n".
                "Content-Type: application/x-www-form-urlencoded",
                'content'=>$image
			));
			$context = stream_context_create($options);
			$imgurURL = "https://api.imgur.com/3/image";
		
			if($_FILES[$formname]['size'] > 10240000) {
				die('Image too big, must be less than 10MB!');
			}
		
			$response = file_get_contents($imgurURL, false, $context);
			$response = json_decode($response);
			
			$preparams = array($formname=>$response->data->link);
			
			$params = $preparams + $params;
		
			DB::query($query, $params);
		}
	}

?>