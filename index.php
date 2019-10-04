
<?php 

	// Set root directory here
	$rootdir = "/var/www/WebRoot";
	$relative_path = (isset($_GET["path"]) ? ($_GET["path"]) : "");
	
	$dir = $rootdir . $relative_path;

	// Sort in ascending order - this is default
	$nodes = scandir($dir);
	sort($nodes);

	// Skip undesired files
	function toSkip($filename) {
		global $relative_path;

		switch ($filename) {

			case ".":
			case "..":
				// Skip . and .. if root directory
				if (getcwd().$relative_path != "/private/var/www/WebRoot") {
					return false;
				}
			case ".DS_Store":
				return true;
				break;
			
			default:
				return false;
				break;
		}
	}


	// Return file type based on filename extension
	function getFileType($filename) {
		if (strrpos($filename, ".")) {
			return " ".substr($filename, strrpos($filename, ".")+1);
		}
	}


	// Print current node (file or directory)
	function printNode($nodename) {
		global $relative_path, $rootdir;
		$constructed_path = "$relative_path/$nodename";

		if (substr($constructed_path, -2) == "..") {
			$constructed_path = substr($constructed_path, 0, strlen($constructed_path)-3);
			$constructed_path = substr($constructed_path, 0, strrpos($constructed_path, "/"));
		} else if (substr($constructed_path, -1) == ".") {
			$constructed_path = substr($constructed_path, 0, strlen($constructed_path)-2);
		}

		if (is_dir("$rootdir$relative_path/$nodename")) {
			$href = "?path=$constructed_path";
			$target = '';
		} else {
			$href = "$constructed_path";
			$target = '_blank';
		}

		$classList = (is_dir("$rootdir$relative_path/$nodename") ? "directory" : ("file". getFileType($nodename)));

		echo "<li class='$classList'><img /><a href='$href' target='$target'>" . $nodename . "</a></li>";
	}

?>


<!doctype html>
<html>
	<head>

		<meta charset="utf-8">

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css" />
		<link rel="stylesheet" type="text/css" href="css/main.css" />

	</head>
	<body>
		<div class="container">
			
			<?php 
				echo "<h1>Index of $rootdir$relative_path:</h1>";	
			?>

			<ul>

				<?php 

					// Print directories first
					foreach ($nodes as $nodename) {
						if (!toSkip($nodename) && is_dir("$rootdir$relative_path/$nodename")) {
							printNode($nodename);
						}
					}

					// ...files then
					foreach ($nodes as $nodename) {
						if (!toSkip($nodename) && !is_dir("$rootdir$relative_path/$nodename")) {
							printNode($nodename);
						}
					}

				?>

			</ul>

		</div>
	</body>
</html>