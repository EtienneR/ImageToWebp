<?php $title = 'Convert image to webp image'; ?>
<html>
<head>
	<meta charset="UTF-8" />
	<title><?php echo $title; ?></title>
</head>
<body>
	<h1><?php echo $title; ?></h1>
	<p>/!\ : Working only on Chrome and Opera - <em>jpg</em>, <em>gif</em> or <em>png</em> files accepted<p>
	<form action="index.php" method="POST" enctype='multipart/form-data'>
		<input type="file" name="file" required />
		<input type="submit" value="Uploader" />
	</form>
</body>
</html>


<?php
if (isset($_FILES['file'])){
	//var_dump($_FILES);
	$error 	  = $_FILES['file']['error'];
	$filename = $_FILES['file']['name'];
	//1 Mo = 2^20 octets = 1048576 octets
	$max_size = 2097152;

	if ($error == 0){

		$valid_extension  = array('jpg', 'jpeg', 'gif', 'png');
		$upload_extention = strtolower(  substr(  strrchr($filename, '.')  ,1)  );
		if ( in_array($upload_extention, $valid_extension) ){
			$name = 'img/' . str_replace(' ', '_', $filename);
			move_uploaded_file($_FILES['file']['tmp_name'], $name);

			$image = '';
			switch ($upload_extention) {
				case 'jpg':
				case 'jpeg':
					$image = imagecreatefromjpeg($name);
					break;

				case 'gif':
					$image = imagecreatefromgif($name);
					break;

				case 'png':
					$image = imagecreatefrompng($name);
					break;
			}

			$name = substr($name, 0, -3).'webp';
			imagewebp($image, $name);

			echo 'Webp image : <br /><img src=' . $name . ' alt=' . $name .' />';
		}
		else{
			echo 'Bad extension';
		}
	}

	elseif($error == 1){
		echo 'File too big, upload limited to ' . $max_size/1048576 . ' Mo';
	}

	else{
		echo 'Error';
	}
}


?>