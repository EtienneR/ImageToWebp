<?php $title = 'Convert image to webp image'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="css/bootstrap.min.css" />
</head>
<body>

	<div class="container">

		<h1><?php echo $title; ?></h1>
		<p>/!\ : Working only on Chrome and Opera - <em>jpg</em>, <em>gif</em> or <em>png</em> files accepted<p>
		<form action="index.php" method="POST" enctype='multipart/form-data' role="form">
			<div class="form-group">
				<input type="file" name="file" required />
			</div><!-- // .form-group -->
			<button type="submit" class="btn btn-primary">Upload</button>
		</form>

<?php
if (isset($_FILES['file'])){
	$path = 'images';
	if (!file_exists($path)) {
		mkdir($path, 0777);
	}
?>
<div class="row">
<?php

	//var_dump($_FILES);
	$error 	  = $_FILES['file']['error'];
	$filename = $_FILES['file']['name'];
	//1 Mo = 2^20 octets = 1048576 octets
	$max_size = 2097152;
	$filename_orignal = $path . '/' . $filename;

	if ($error == 0){

		$valid_extension  = array('jpg', 'jpeg', 'gif', 'png');
		$upload_extention = strtolower(  substr(  strrchr($filename, '.')  ,1)  );
		if ( in_array($upload_extention, $valid_extension) ){
			$name = $path.'/' . str_replace(' ', '_', $filename);
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
			$orignial_ko = number_format($_FILES['file']['size'] / 1024, 2);
			$webp_ko = number_format(filesize($name) / 1024, 2);

			echo '<div class="col-xs-6 col-md-6">';
			echo 'Original file size : ' . $orignial_ko . ' Ko  <br />';
			echo '<a href="' . $filename_orignal . '" class="thumbnail"><img src=' . $filename_orignal . ' alt="" /></a>';
			echo '</div>';
			echo '<div class="col-xs-6 col-md-6">';
			echo 'Web file size ' . $webp_ko . ' Ko <br />';
			echo '<a href="' . $name . '" class="thumbnail"><img src=' . $name . ' alt="" /></a>';
			echo '</div>';
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
?>
</div><!-- // .row -->
<?php
}
?>

	</div><!-- // .container -->

</body>
</html>