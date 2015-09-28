<?php
$installProcess = true;

/* Put a step-by-step message */
function drawMsg($message, $type)
{
	$message = "<p><span class=\"badge badge-".$type."\">".$type."</span> ".$message."</p>";
	return $message;
}

$sys['system_root'] = str_replace("install.php", "", $_SERVER['SCRIPT_NAME']);

/* Put a step 1 questions form */
if($_POST['install'] != "true")
{
	$htmlOutput .= "<div><center><h3>Step 1</h3></center></div>";
	$htmlOutput .= "<div><center><h4>Please, provide a correct setting where your new system should be installed</h3></center><br/></div>";
	$htmlOutput .= "<div class=\"container\"><form method=\"post\" action=\"install.php\">";
	$htmlOutput .= "<table>";
	$htmlOutput .= "<tr><td>Database address : </td><td><input type=\"text\" name=\"db_host\" autocomplete=\"off\" placeholder=\"Enter the MySQL address here\"></td></tr>";
	$htmlOutput .= "<tr><td>Database name : </td><td><input type=\"text\" name=\"db_name\" autocomplete=\"off\" placeholder=\"Enter the database name\"></td></tr>";
	$htmlOutput .= "<tr><td>Database login : </td><td><input type=\"text\" name=\"db_user\" autocomplete=\"off\" placeholder=\"Enter a database user\"></td></tr>";
	$htmlOutput .= "<tr><td>Database password : </td><td><input type=\"text\" name=\"db_pass\" autocomplete=\"off\" placeholder=\"Enter a database password\"></td></tr>";
	$htmlOutput .= "<tr><td>Default admin user: </td><td><span><h5>admin</h5></span></td></tr>";
	$htmlOutput .= "<tr><td>Default admin pass: </td><td><input type=\"text\" name=\"admin_pass\" autocomplete=\"off\" placeholder=\"Type new password here\"></td></tr>";
	$htmlOutput .= "<tr><td colspan=\"2\"><center><br/><button type=\"submit\" class=\"btn btn-large btn-primary\"> Start installation </button><br/></center></td></tr>";
	$htmlOutput .= "<input type=\"hidden\" name=\"install\" value=\"true\">";
	$htmlOutput .= "<table>";
	$htmlOutput .= "</form></div>";
	$installProcess = false;
} else {
	$sys['db_host'] = $_POST['db_host'];
	$sys['db_user'] = $_POST['db_user'];
	$sys['db_pass'] = $_POST['db_pass'];
	$sys['db_name'] = $_POST['db_name'];
	$sys['admin_pass'] = $_POST['admin_pass'];
}

/* Step 0 : Check access to the config file */
$fileAddress = str_replace("install.php", "config.php", $_SERVER['SCRIPT_FILENAME']);
if($installProcess)
{
	if(!is_readable($fileAddress))
	{
		$htmlOutput .= drawMsg("Restricted read access to the config.php", "important");
		$installProcess = false;
	} else {
		/* Load config */
		$configFile = file_get_contents(str_replace("install.php", "config.php", $_SERVER['SCRIPT_FILENAME']));
	}
}
if($installProcess)
{
	if(!is_writeable($fileAddress))
	{
		$htmlOutput .= drawMsg("Restricted write access to the config.php", "important");
		$installProcess = false;
	} else {
		/* Load config */
		$htmlOutput .= drawMsg("Config file is available", "success");
		$configFile = file_get_contents(str_replace("install.php", "config.php", $_SERVER['SCRIPT_FILENAME']));
	}
}

/* Step 1 : Check MySQL Presense */
if($installProcess)
{
	$testSocket = @fsockopen($sys['db_host'], "3306");
	if($testSocket)
	{
		$htmlOutput .= drawMsg("MySQL discovered on ".$sys['db_host'], "success");
	} else {
		$htmlOutput .= drawMsg("MySQL not found on ".$sys['db_name'], "important");
		$installProcess = false;
	}
}
/* Step 2 : Test MySQL Connection */
if($installProcess)
{
	$sqlLink = @mysql_connect($sys['db_host'], $sys['db_user'], $sys['db_pass']);
	if($sqlLink === false)
	{
		$htmlOutput .= drawMsg("Unable to connect to the database ".$sys['db_name'], "important");
		$installProcess = false;
	} else {
		$htmlOutput .= drawMsg("Database ".$sys['db_name']." has been connected", "success");
	}
}

/* Step 2 : Test database selection */
if($installProcess)
{
	$sqlDatabase = @mysql_select_db($sys['db_name'], $sqlLink);
	if($sqlDatabase === false)
	{
		$htmlOutput .= drawMsg("Database ".$sys['db_name']." does not exist", "warning");
		/* Step 2.1 : Create database */
		$createResult = @mysql_query("CREATE DATABASE `".$sys['db_name']."` CHARACTER SET utf8;", $sqlLink);
		if($createResult) $createResult = @mysql_select_db($sys['db_name'], $sqlLink);
		if($createResult)
		{
			$htmlOutput .= drawMsg("Database ".$sys['db_name']." created successfully", "success");
		} else {
			$htmlOutput .= drawMsg("Failed to create database ".$sys['db_name'], "important");
			$installProcess = false;
		}
	} else {
		$htmlOutput .= drawMsg("Using database".$sys['db_name'], "success");
	}
}

/* Step 3 : Checking tables */
if($installProcess)
{
	$createAttempt = @mysql_query("CREATE TABLE IF NOT EXISTS `users` (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, login VARCHAR(255) UNIQUE, name VARCHAR(255), password VARCHAR(255), class VARCHAR(255));");
	if($createAttempt === false) $installProcess = false;

	$createAttempt = @mysql_query("CREATE TABLE IF NOT EXISTS `projects` (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), project_root VARCHAR(255), project_yml VARCHAR(255), creator INT, time DATETIME, log_path VARCHAR(255));");
	if($createAttempt === false) $installProcess = false;

	$createAttempt = @mysql_query("CREATE TABLE IF NOT EXISTS `tasks` (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, parent_id INT, name VARCHAR(255), timestamp DATETIME, log_file VARCHAR(255));");
	if($createAttempt === false) $installProcess = false;
	
	$createAttempt ? $htmlOutput .= drawMsg("Creating MySQL tables", "success") : $htmlOutput .= drawMsg("Creating MySQL tables", "important");

}

/* Step 4 : Create admin user */
if($installProcess)
{
	$userPresenceCheck = @mysql_query("SELECT * FROM `users`;");
	if(mysql_num_rows($userPresenceCheck) == 0)
	{
		$htmlOutput .= drawMsg("Generating password hash", "success");
		$hashSalt = substr(md5(rand(0,255).rand(0,255).rand(0,255).rand(0,255)), 0, 8);
		$sshaGeneric = "{SSHA}".base64_encode(sha1($sys['admin_pass'].$hashSalt,true).$hashSalt);
		$createAttempt = @mysql_query("INSERT INTO `users` (login, name, password, class) VALUES ('admin', 'Generic Administrator', '".$sshaGeneric."', 'Administrators');");
		if($createAttempt)
		{
			$htmlOutput .= drawMsg("Admin user created", "success");
		} else {
			$htmlOutput .= drawMsg("Failed to create an admin user", "important");
			$installProcess = false;
		}
	} else {
			$htmlOutput .= drawMsg("Users already exists", "warning");
	}
}

/* Step 5 : Write config */
if($installProcess)
{
	$calcPath = str_replace("install.php", "", $_SERVER['SCRIPT_NAME']);
	$configFile = preg_replace("/\$config\['db_name'\] = \".*\"/", "\$config['db_name'] = \"".$sys['db_name']."\";", $configFile);
	$configFile = preg_replace("/\$config\['db_host'\] = \".*\"/", "\$config['db_host'] = \"".$sys['db_host']."\";", $configFile);
	$configFile = preg_replace("/\$config\['db_user'\] = \".*\"/", "\$config['db_user'] = \"".$sys['db_user']."\";", $configFile);
	$configFile = preg_replace("/\$config\['db_pass'\] = \".*\"/", "\$config['db_pass'] = \"".$sys['db_pass']."\";", $configFile);
	$configFile = preg_replace("/\$config\['system_root'\] = \".*\"/", "\$config['system_root'] = \"".$calcPath."\";", $configFile);
	$configWriteSuccess = file_put_contents(str_replace("install.php", "config.php", $_SERVER['SCRIPT_FILENAME']), $configFile);
	($configWriteSuccess === false) ? $htmlOutput .= drawMsg("Config file not created", "important") : $htmlOutput .= drawMsg("Config file created", "success");
}

/* Step 5 : Check and generate keys */
if($installProcess)
{
	if(!file_exists(str_replace("install.php", "", $_SERVER['SCRIPT_FILENAME'])."core/keys.chain"))
	{
		
	} else {
		$htmlOutput .= drawMsg("RSA keys file already exists", "warning");
	}
}
/* Step 6 : Draw ending message */
if($installProcess)
{
	$htmlOutput .= "<div><center><h3>Congratulations</h3></center></div>";
	$htmlOutput .= "<div><center><h4>Ansible UI installation has been successfully completed</h3></center><br/></div>";
	$htmlOutput .= "<div><center><a href=\"index.php\" class=\"btn btn-large btn-primary\">Start to use Ansible UI</a></center></div>";
} else {
	$htmlOutput .= "<div><center><h4>Ansible UI installation failed.</h3></center></div>";
	$htmlOutput .= "<div><center><h4>Fix the issues and try again</h3></center></div>";
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Installer</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/docs.css">
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/generic.css">
    </head>
    <body>
	<div class="container">
		<p><h3>Ansible UI Installation</h3></p>
	</div>
	<div class="container">
	        <?php echo $htmlOutput; ?>
	<div>
    </body>
</html>

