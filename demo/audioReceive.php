<?php
	if ($_FILES["file"]["error"] > 0) {
		echo "Error: ". $_FILES["file"]["error"]. "<br>";
	} else {
		$extension = end(explode(".", $_FILES["file"]["name"]));
		if ($extension != 'wav') {
			echo "Error: input format";
		}
		if (file_exists($_FILES['file']['name'])) {
			$out = shell_exec('matlab -nodisplay -r "run wav.m"');
			#$out = system('python audio.py');
            echo $out;
		} else {
			move_uploaded_file($_FILES['file']['tmp_name'], $_FILES['file']['name']);
			$out = shell_exec('matlab -nodisplay -r "/home/luxuia/www/demo/wav"');

			echo $out;
		//	echo "finished";
		}
	}


?>
