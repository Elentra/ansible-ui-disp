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

/* Additional display variables */
$viewPlaybooksHover = "active";
$viewPlaybooksList = $Ansible->playbooksList($_GET['pr']);
if($viewPlaybooksList == "") $viewPlaybooksList = "<li class=\"navbar-inner\"><h5>No current playbooks</h5></li>";
if(isset($_GET['pr']))
{
	$playbookProperty = $Ansible->findProjectById($_GET['pr']);
	$viewPlaybook = $playbookProperty['name'];
}



/* Page template processing */
require_once("template/playbooks.tpl");
?>
