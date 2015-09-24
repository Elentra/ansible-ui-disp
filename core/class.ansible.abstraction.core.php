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
		$taskContents = preg_replace('/\n*$/', '', $taskContents);
		$midArray = explode("\n\n", $taskContents);
		foreach($midArray as $entry)
		{
			$entry = str_replace("\n", "<br/>", $entry);
			$cacheEntry = explode("*", $entry);
			$cacheEntry[0] = preg_replace("/^\<br\/\>/", "", $cacheEntry[0]);
			$cacheEntry[1] = preg_replace("/^\<br\/\>/", "", $cacheEntry[1]);
			$cacheEntry[1] = explode("<br/>", $cacheEntry[1]);
			$returnArray[] = $cacheEntry;
		}
		return $returnArray;
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
		foreach($this->registeredProjects as $currentProject)
		{
			$hover = "";
			if($currentProject['id'] == $idActive) $hover = " class=\"active\"";
			$projectList .= "<li".$hover."><a href=\"playbooks.php?pr=".$currentProject['id']."\"><i class=\"icon-chevron-right\"></i> ".$currentProject['name']."</a></li>";
			
		}
		return $projectList;
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
}
?>
