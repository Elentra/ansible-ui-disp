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
$viewDashboardHover = "active";
$viewDashboardTasks = $Ansible->tasksList();
if($viewDashboardTasks == "") $viewDashboardTasks = "<li class=\"navbar-inner\"><h5>No current tasks</h5></li>";

if(isset($_GET['task']))
{
	$taskProperty = $Ansible->findTaskById($_GET['task']);
	$tFileContent = $Ansible->parseTaskFile($taskProperty['log_file']);
	if($tFileContent != false)
	{
		$statusParseDown = $Ansible->statusFromFile($tFileContent);
		if(count($statusParseDown) > 0) foreach($statusParseDown as $hostStatus)
		{
			$viewStatusBlock .= "<tr><td>".$hostStatus['name']."</td><td>".$hostStatus['ok']."</td><td>".$hostStatus['changed']."</td><td>".$hostStatus['unreachable']."</td><td>".$hostStatus['failed']."</td></tr>";
		}
		foreach($tFileContent as $taskEntry)
		{
			if($taskEntry['type'] == "task")
			{
				$parseRole = preg_split("/\[|\||\]/", $taskEntry['header'])[1];
				$parseTask = preg_split("/\[|\||\]/", $taskEntry['header'])[2];
				foreach($taskEntry['data'] as $hostSub)
				{
					$hostSplit = preg_split("/\:\s\[|\]/", $hostSub)[1];
					$hostStatus = preg_split("/\:\s\[|\]/", $hostSub)[0];
					/* Row Open */ $viewTaskBlock .= "<tr>";
					/* Col task role */ $viewTaskBlock .= "<td>".$parseRole."</td>";
					/* Col task task */ $viewTaskBlock .= "<td>".$parseTask."</td>";
					/* Col task host */ $viewTaskBlock .= "<td>".$hostSplit."</td>";
					switch($hostStatus)
					{
					case 'ok':
						/* Col task status */ $viewTaskBlock .= "<td><span class=\"badge badge-success\">OK</span></td>";
					break;
					case 'skipping':
						/* Col task status */ $viewTaskBlock .= "<td><span class=\"badge badge-default\">Skipped</span></td>";
					break;
					case 'changed':
						/* Col task status */ $viewTaskBlock .= "<td><span class=\"badge badge-warning\">Changed</span></td>";
					break;
					case 'failed':
						/* Col task status */ $viewTaskBlock .= "<td><span class=\"badge badge-important\">Failed</span></td>";
					break;
					}
					/* Row Close */ $viewTaskBlock .= "</tr>";
				}
			}
		}
	}
}

/* Logic area */


/* Page template processing */
require_once("template/dashboard.tpl");
?>
