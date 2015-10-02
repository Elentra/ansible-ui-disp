<?php
/* Environment */
require_once("config.php");

/* Core utils */
require_once("core/auth.container.php");

/* Ansible driver */
if(!(isset($config)))
{
	require_once("config.php");
}
if(!(isset($DB)))
{
	require_once("external/class.database.mysql.external.php");
	$DB = new Database($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
}
require_once("core/class.ansible.abstraction.core.php");
$Ansible = new Ansible($DB);


/* IF-Event - create user start */
if(isset($_GET['create']))
{
	$loadFormUsers = "users.create.tpl";
}
/* IF-Event - create user execute */
elseif(isset($_POST['login']) and isset($_POST['password-one']) and isset($_POST['password-two']) and isset($_POST['class']))
{
	$createUser = true;
	$newUserName = $DB->escapeData($_POST['login']);
	/* Check */
	if($_POST['password-one'] != $_POST['password-two']) $createUser = false;
	if(count($DB->getData("users", "WHERE login='".$newUserName."'")) > 0) $createUser = false;

	/* Construct */
	$newUser['login'] = $_POST['login'];
	$newUser['name'] = $_POST['name'];
	$newUser['password'] = $Auth->construct_SSHA($_POST['password-one']);
	$newUser['class'] = $_POST['class'];

	/* IF - OK - Send create */
	$userCreated = $DB->insertData("users", $newUser);
	if($userCreated)
	{
		$spawnSuccess = true;
		$spawnSuccessText = "User <strong>".$newUser['login']."</strong> has been created successfully.";
	} else {
		$spawnError = true;
		$spawnErrorText = "Unable to comply. User <strong>".$newUser['login']."</strong> was NOT created.";
	}
	$loadFormUsers = "users.default.tpl";
}
/* IF-Event - load user */
elseif(isset($_GET['deleteID']) and ($_GET['deleteID'] != "") and ($_GET['deleteID'] > 0))
{
	$rowDeleted = false;
	if($_GET['deleteID'] != $_SESSION['id'])
	{
		/* IF checks passed - Delete user entry */
		$deleteRowId = $DB->escapeData($_GET['deleteID']);
		$rowDeleted = $DB->deleteRow("users", $deleteRowId);
	}
	if($rowDeleted)
	{
		$spawnWarning = true;
		$spawnWarningText = "User has been deleted successfully";
	} else {
		$spawnError = true;
		$spawnErrorText = "Unsuccessful. Cannot delete such user.";
	}
	$loadFormUsers = "users.default.tpl";
}
/* IF-Event - load user */
elseif(isset($_GET['uid']))
{
	$searchID = $DB->escapeData($_GET['uid']);
	$Users = $DB->getData("users", "WHERE id=".$searchID." LIMIT 1");
	if($Users)
	{
		$showUserId = $Users[0]['id'];
		$showUserLogin = $Users[0]['login'];
		$showUserName = $Users[0]['name'];
		$showUserGroup = $Users[0]['class'];
		$showUserCreated = $Users[0]['id'];
		$loadFormUsers = "users.show.tpl";
	} else {
		$spawnError = true;
		$spawnErrorText = "User with this ID does not exist.";
		$loadFormUsers = "users.default.tpl";
	}
}
/* IF-Event - default */
else
{
	$loadFormUsers = "users.default.tpl";
}

/* Gather existing users */
$Users = $DB->getData("users", "ORDER BY `id` ASC");

/* Additional display variables */
foreach($Users as $User)
{
	$class = "";
	if($_GET['uid'] == $User['id']) $class = " class=\"active\"";
	$viewUserList .= "<li".$class."><a href=\"users.php?uid=".$User['id']."\"><i class=\"fa fa-user\"></i> ".$User['name']."</a>";
	/*if($User['id'] != $_SESSION['id'])
	{
		$viewUserList .= "<a href=\"users.php?deleteID=".$User['id']."\" class=\"\"><i class=\"fa fa-times\"></i></a>";
	}*/
	$viewUserList .= "</li>";
}

/* Page template processing */
require_once("template/users.tpl");
?>
