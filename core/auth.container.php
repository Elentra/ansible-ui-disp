<?php
session_start();
/* Aquire neccessary libs */
require_once("external/class.auth.module.core.php");
require_once("external/class.database.mysql.external.php");

/* Declare classes */
$Auth = new BasicAuth();
$DB = new Database($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
$sessionCurrentStatus = false;

/* Handle login request */
if(isset($_POST['login']) and isset($_POST['password']))
{
	$providedName = $_POST['login'];
	$providedPassword = $_POST['password'];

	/* Find if users exists */
	$userName = $DB->escapeData($providedName);
	$userParameters = $DB->getData("users", "WHERE login='".$userName."'");

	if(count($userParameters) == 0)
	{
		/* DO SOMETHING IN THAT CASE */
	} else {
		$Authenticated = $Auth->AuthOnSSHA($providedPassword,$userParameters[0]['password']);
		if($Authenticated)
		{
			$Auth->sessionEstablish($userParameters[0]);
			unset($_POST);
			header("Location: ".$config['system_root']);
			die();
		} else {
			/* DO SOMETHING IN THAT CASE */
		}
	}
}

/* If this is not index.php and request was not authorized, cut it loose */
if(($_SESSION['id'] == "") and ($_SESSION['key'] == ""))
{
	/* This is surely unauthorized session */
	if($config['system_root']."index.php" != $_SERVER['SCRIPT_NAME'])
	{
		header("Location: ".$config['system_root']);
		die();
	}
}

/* Handle automatic relogin */
if($_SESSION['login'] != "")
{
	$userName = $DB->escapeData($_SESSION['login']);
	$userParameters = $DB->getData("users", "WHERE login='".$userName."'");
	
	if(count($userParameters) > 0)
	{
		$sessionCurrentStatus = $Auth->sessionVerify($userParameters[0]['password']);
	}
}

/* Reinstall the session if credentials was verified */
if($sessionCurrentStatus)
{
	$Auth->sessionRegenerate();
}

/* Handle manual log out */
if(isset($_GET['logout']))
{
	$Auth->sessionDestroy();
	header("Location: ".$config['system_root']);
}


?>
