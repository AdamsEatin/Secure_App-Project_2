<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		<title>Secure App 2.0 : Log Viewer </title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Secure App System 2.0 : Log Viewer</h1>
		
		<?php 
			include 'config.php';
			
			$file = fopen("log.txt", "r");
			if($file){
				
				while(($line = fgets($file)) !== FALSE){
					$data = trim($line);
					$dec_data = decrypt($data);
					echo"<h3>$dec_data</h3>";
				}
				fclose($file);
			}
			
		?>
		
		<form>
			<a href= "/Welcome.php">
			<input type="button" value="Home">
			</a>
			<button formaction="/index.php">Logout</button>
		</form>
	</body>
</html>