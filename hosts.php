<?php
/* Environment */
require_once("config.php");

/* Check authentication */
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
$viewHostsHover = "active";
$viewBundleList = "";
$viewGroupList = "";

/* Process playbooks */
$viewPlaybooksList = $Ansible->playbooksList($_GET['pr']);
$projectName = "Undefined";
if($viewPlaybooksList == "") $viewPlaybooksList = "<li class=\"navbar-inner\"><h5>No current playbooks</h5></li>";
if(isset($_GET['pr']))
{
	$playbookProperty = $Ansible->findProjectById($_GET['pr']);
	$viewPlaybook = $playbookProperty['name'];
	if(isset($_GET['bundleID']) and ($_GET['bundleID'] != ""))
	{
		$bundleActive = $_GET['bundleID'];
	}
	$viewBundleList = $Ansible->fetchPlaybookBundles($_GET['pr'], $bundleActive);
	$projectName = $playbookProperty['name'];
}

/* Process bundles */
if($viewBundleList == "") $viewBundleList = "<li class=\"navbar-inner\"><h5>No items currently selected</h5></li>";

/* Process groups */
if($viewGroupList == "") $viewGroupList = "<li class=\"navbar-inner\"><h5>No items currently selected</h5></li>";

if(isset($_GET['pr']) and isset($_GET['bundleID']))
{
	$projectHostList = $Ansible->hostsList($_GET['pr'], $_GET['bundleID']);
}

/* Page template processing */
require_once("template/hosts.tpl");
?>
