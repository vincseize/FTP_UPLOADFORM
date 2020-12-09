<?php

// dir_upload == ftp dir server configuration 

$title = "FTP CHASSE PECHE ET BITURE";

$ip_server = "51.38.186.165";
$http_server = "http://".$ip_server."";
$infos_json = "infos.json";

// generate new json infos -> $infos_json
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $http_server.'/infos.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_POSTFIELDS,'email='.urlencode($login_email).'&pass='.urlencode($login_pass).'&login=Login');
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_HEADER, 0);
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
// curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
curl_exec($ch);

$str = file_get_contents($http_server.'/'.$infos_json);
$json = json_decode($str, true);

?>

<html>
	<head>
		<title><?php echo $title;?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	</head>
	<body>


	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <ul class="navbar-nav">
    <li class="nav-item active">
      <a class="nav-link" href="#"><?php echo $title;?></a>
    </li>
    <!-- <li class="nav-item">
      <a class="nav-link" href="#">Link</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link</a>
    </li>
    <li class="nav-item">
      <a class="nav-link disabled" href="#">Disabled</a>
    </li> -->
  </ul>
</nav>


		<div class="container">

		<br>
				<!-- <form class="upload-form" action="upload_file.php" method="post" enctype="multipart/form-data"> -->
				<form class="upload-form" action="" method="post" enctype="multipart/form-data">
					
					<p><input type="file" class="upload-file btn btn-default" data-max-size="2000000000" name="fileToUpload" id="fileToUpload"></p>
					<p><input type="submit" class="btn btn-primary" value="Upload file" name="fileToUploadSubmit"></p>
				</form>
				<!-- max 1.9 Giga -->
				<?php
					print_r($json);
				?>
		<br><br>

		<!-- <h2>Basic Progress Bar</h2> -->
		<div class='progress'>
			<div class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width:0%'>
			<span class='sr-only' id='span'>70% Complete</span>
			</div>
		</div>
		<p class="txt_progression">

		</p>

	</body>

	<script>

		function FileConvertSize(aSize){
			aSize = Math.abs(parseInt(aSize, 10));
			var def = [[1, 'octets'], [1024, 'ko'], [1024*1024, 'Mo'], [1024*1024*1024, 'Go'], [1024*1024*1024*1024, 'To']];
			for(var i=0; i<def.length; i++){
				if(aSize<def[i][0]) return (aSize/def[i-1][0]).toFixed(2)+' '+def[i-1][1];
			}
		}

		function progressBar(percent,local_file_size,done="false"){
				var file_size = FileConvertSize(local_file_size);
				var percent1 = percent;
				if (done=="true"){
					var percent1 = "100";
				}
				var percent2 = percent1+"%";
				$(".progress-bar").attr("aria-valuenow",percent1);
				$('.progress-bar').css("width", percent2);
				$('.txt_progression').html(file_size + " | " + percent2);
		}


		function Redirect() 
			{  
				window.location="http://51.38.186.165/ftp_upload_form.php"; 
			} 

			$(function(){
				var fileInput = $('.upload-file');
				var maxSize = fileInput.data('max-size');
				$('.upload-form').submit(function(e){
					if(fileInput.get(0).files.length){
						var fileSize = fileInput.get(0).files[0].size; // in bytes
						if(fileSize>maxSize){
							alert('file size is more then' + maxSize + ' bytes');
							return false;
						}else{
							// alert('file size is correct- '+fileSize+' bytes');
						}
					}else{
						alert('choose file, please');
						return false;
					}
					
				});
			});
			
	</script>
</html>

<?php

	if (isset($_POST["fileToUploadSubmit"])) {

		$ftp_server = $ip_server;
		$ftp_user_name = "lrdlscpeb";
		$ftp_user_pass = "lrdls";

		$file = $_FILES["fileToUpload"]["tmp_name"];
		// $remote_file = "upload_" . $_FILES["fileToUpload"]["name"];
		$remote_file = $_FILES["fileToUpload"]["name"];
		$type = $_FILES["fileToUpload"]["type"];

		$conn_id = ftp_connect($ftp_server);

		@ob_end_flush();

		$remote_file = $remote_file;
		$local_file = $file;

		$fp = fopen($local_file, 'r');
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		$ret = ftp_nb_fput($conn_id, $remote_file, $fp, FTP_BINARY);
		while ($ret == FTP_MOREDATA) {
			// Establish a new connection to FTP server
			if(!isset($conn_id2)) {
				$conn_id2 = ftp_connect($ftp_server);
				$login_result2 = ftp_login($conn_id2, $ftp_user_name, $ftp_user_pass);
			}

			// Retreive size of uploaded file.
			if(isset($conn_id2)) {
				clearstatcache(); // <- this must be included!!
				$remote_file_size = ftp_size($conn_id2, $remote_file);
				
			}

			// Calculate upload progress
			$local_file_size  = filesize($local_file);
			if (isset($remote_file_size) && $remote_file_size > 0 ){
				$i = ($remote_file_size/$local_file_size)*100;
							
				// printf(" %d%%", $i);

				echo "<script type='text/javascript'>progressBar($i,$local_file_size);</script>";
				// sleep(2);

				@flush();

			}
			$ret = ftp_nb_continue($conn_id);
					
		}
			
		if ($ret != FTP_FINISHED) {
			print("There was an error uploading the file...<br>");
			exit(1);
		}
		else {
			print("<br> HI HI Done!!! Refresh in 5 Seconds<br>");
			echo "<script type='text/javascript'>progressBar('100',$local_file_size,'true');</script>";
			



			echo "<script type='text/javascript'>"; 
				echo "setTimeout('Redirect()', 5000);";
			echo "</script>";

			unset($_FILES["fileToUpload"]["tmp_name"]);
			// die;
		}
		
		ftp_close($conn_id);

	}

?>




