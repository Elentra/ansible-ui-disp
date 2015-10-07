<?
/*
	Описание:
	Класс работы с Ansible.

	Исключения:


*/
class Ansible {

/* Variables */
protected $systemConfig;
protected $registeredProjects;
protected $registeredTasks;

/* Descriptors */
protected $linkDatabase;

	public function __construct($openDatabaseLink)
	{
		global $config;
		$this->systemConfig = $config;
		$this->linkDatabase = $openDatabaseLink;
		$this->registeredProjects = $this->linkDatabase->getData("projects");
		$this->registeredTasks = $this->linkDatabase->getData("tasks", "ORDER BY `timestamp` DESC");
	}

	/* Main methods */
	public function parseTaskFile($taskFilePath)
	{
		$taskContents = file_get_contents($taskFilePath);
		$taskContents = preg_replace('/(\*.*)/', '*', $taskContents);
		$taskContents = preg_replace('/( * )/', ' ', $taskContents);
		$taskContents = preg_replace('/\n*$/', '', $taskContents);
		$taskContents = preg_replace('/\s\:/', ':', $taskContents);
		$midArray = explode("\n\n", $taskContents);
		foreach($midArray as $entry)
		{
			$entry = str_replace("\n", "<br/>", $entry);
			$cacheEntry = explode("*", $entry);
			$retEntry = array();
			$cacheEntry[0] = preg_replace("/^\<br\/\>/", "", $cacheEntry[0]);
			$cacheEntry[1] = preg_replace("/^\<br\/\>/", "", $cacheEntry[1]);
			$cacheEntry[1] = explode("<br/>", $cacheEntry[1]);
			$retEntry['header'] = trim($cacheEntry[0]);
			$retEntry['data'] = $cacheEntry[1];
			unset($cacheEntry);
			$retEntry['type'] = strtolower(trim(preg_split("/\[|\:/", $retEntry['header'])[0]));
			$returnArray[] = $retEntry;
		}
		return $returnArray;
	}
	public function statusFromFile($taskFileContents)
	{
		foreach($taskFileContents as $eventContent)
		{
			if($eventContent['type'] == "play recap")
			{
				foreach($eventContent['data'] as $hostStatus)
				{
				$parsed = preg_split("/\:|\s/", $eventContent['data'][0]);
				$host['name'] = preg_replace("/.*\=/", "", $parsed[0]);
				$host['ok'] = preg_replace("/.*\=/", "", $parsed[2]);
				$host['changed'] = preg_replace("/.*\=/", "", $parsed[3]);
				$host['unreachable'] = preg_replace("/.*\=/", "", $parsed[4]);
				$host['failed'] = preg_replace("/.*\=/", "", $parsed[5]);
				$hosts[] = $host;
				}
			}
		}
		return $hosts;
	}

	public function runPriorityTask($projectName, $projectTarget)
	{
		

	}

	/* Additional methods */
	public function getProjectProperty($projectId, $projectField)
	{
		$project = $this->findProjectById($projectId);
		if($project)
		{
			return $project[$projectField];
		} else {
			return false;
		}
	}
	public function findProjectById($projectId)
	{
		foreach($this->registeredProjects as $currentProject)
		{
			if($currentProject['id'] == $projectId) return $currentProject;
		}
		return false;
	}
	public function findTaskById($taskId)
	{
		foreach($this->registeredTasks as $currentTask)
		{
			if($currentTask['id'] == $taskId) return $currentTask;
		}
		return false;
	}

	/* HTML Draw */
	public function playbooksList($idActive = "")
	{
		if(count($this->registeredProjects) > 0)
		{
			foreach($this->registeredProjects as $currentProject)
			{
				$hover = "";
				if($currentProject['id'] == $idActive) $hover = " class=\"active\"";
				$projectList .= "<li".$hover."><a href=\"?pr=".$currentProject['id']."\"><i class=\"icon-chevron-right\"></i> ".$currentProject['name']."</a></li>";	
			}
		}
		return $projectList;
	}

	public function fetchPlaybookBundles($projectId, $idActive = "")
	{
		$projectRoot = $this->registeredProjects[$projectId-1]['project_root'];
		$projectBundles = scandir($projectRoot);
		$returnString = "";
		foreach($projectBundles as $projectItem)
		{
			if(($projectItem == "testing") or ($projectItem == "stage") or ($projectItem == "production"))
			{
				$class = "";
				if($idActive == $projectItem) $class = " class=\"active\"";
				$returnString .= "<li".$class."><a href=\"?pr=".$projectId."&bundleID=".$projectItem."\">".$projectItem."</a></li>";
			}
		}
		return $returnString;
	}

	public function tasksList($taskLimit = "")
	{
		$countTotal = count($this->registeredTasks);
		for($task = 0; $task < $countTotal; $task++)
		{
			$hover = "";
			if($_GET['task'] == $this->registeredTasks[$task]['id']) $hover = " class=\"active\"";
			$taskShow .= "<li".$hover."><a href=\"dashboard.php?task=".$this->registeredTasks[$task]['id']."\"><i class=\"icon-chevron-right\"></i> ".$this->registeredTasks[$task]['timestamp']."</a></li>";
		}
		return $taskShow;
	}

	public function hostsList($projectName, $projectTarget)
	{
		$projectPath = "";
		foreach($this->registeredProjects as $enumProject)
		{
			if($enumProject['id'] === $projectName) $projectPath = $enumProject['project_root'];
		}
		if($projectPath === "")
		{
			throw new Exception("Incorrect project selected");
			return false;
		}
		if(!(file_exists($projectPath."/".$projectTarget)))
		{
			throw new Exception("Selected project target group does not exist");
			return false;
		}
		$fileContents = file($projectPath."/".$projectTarget);
		foreach($fileContents as $fileEntry)
		{
			if(preg_match("/\[|\]|^\s*$|^#/", $fileEntry) == false)
			{
				$hosts[] = explode(" ", $fileEntry)[0];
			}
		}
		return $hosts;
	}
}
?>
