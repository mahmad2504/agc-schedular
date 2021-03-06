<?php

class Query
{
	private $task;
	private $jql;
	private $njql;
	private $filter=null;
	private $filterfile;
	private $rebuild;
	private $jiracred;
	
	function multiexplode ($delimiters,$string) {

		$ready = str_replace($delimiters, $delimiters[0], $string);
		$launch = explode($delimiters[0], $ready);
		return  $launch;
	}

	function  QueryPreProcess($project,$jql)
	{
		if( explode("(",strtolower(trim($jql)))[0] == 'link')
		{
			$output = $this->multiexplode(array("(",")"),strtolower(trim($jql)));
			$obj = new Obj();
			$obj->query = 'issue in ('.$output[1].')';
			$obj->jiracred = $project->GetJiraCredentials($output[1]);
			return $obj;
		}
	
		else if(strtolower(trim($jql)) == 'implements(parent)')
		{
			$fields = 'key,status,summary,start,end,timeoriginalestimate,timespent,labels,assignee,created,issuetype,issuelinks,emailAddress,aggregatetimespent,subtasks';
			$parent = $this->task->Parenttask;
			if($parent == null)
			{
				echo "Parent Not found for quesry in ".$this->task->Name.EOL;
				return '';
			}
			$jirainfo = $project->GetJiraCredentials($parent->Tags[0]);
			if($jirainfo != null)
				Jirarest::SetUrl($jirainfo->url,$jirainfo->user,$jirainfo->pass);
			
			$tasks  = Jirarest::Search("key=".$parent->Tags[0],1,$fields);
			
			//var_dump($tasks[0]);
			//$this->task->Tags[0].EOL;
			
			//echo count($tasks[0]['issuelinks'][LINK_IMPLEMENTS]);
			
			$links = array_merge($tasks[0]['issuelinks'][LINK_IMPLEMENTS]);
			
			$query="issue in (";
			$del = "";
			foreach($links as $link)
			{
				$query=$query."$del".$link;
				$del = ",";
			}
			$query=$query.")";
			if(count($links) == 0)
			{
				$query = '';
				return $query;
			}
			if($jirainfo != null)
			{
				$obj = new Obj();
				$obj->query = $query;
				$obj->jiracred = $jirainfo;
				return $obj;
			}
			return $query;
		}
		else if(strtolower(trim($jql)) == 'tests(parent)')
		{
			$fields = 'key,status,summary,start,end,timeoriginalestimate,timespent,labels,assignee,created,issuetype,issuelinks,emailAddress,aggregatetimespent,subtasks';
			$parent = $this->task->Parenttask;
			if($parent == null)
			{
				echo "Parent Not found for quesry in ".$this->task->Name.EOL;
				return '';
			}
			$jirainfo = $project->GetJiraCredentials($parent->Tags[0]);
			if($jirainfo != null)
				Jirarest::SetUrl($jirainfo->url,$jirainfo->user,$jirainfo->pass);
			
			$tasks  = Jirarest::Search("key=".$parent->Tags[0],1,$fields);
			
			$links = array_merge($tasks[0]['issuelinks'][LINK_TESTS]);
			
			$query="issue in (";
			$del = "";
			foreach($links as $link)
			{
				$query=$query."$del".$link;
				$del = ",";
			}
			$query=$query.")";
			if(count($links) == 0)
			{
				$query = '';
				return $query;
			}
			if($jirainfo != null)
			{
				$obj = new Obj();
				$obj->query = $query;
				$obj->jiracred = $jirainfo;
				return $obj;
			}
			return $query;
		}
		else if(strtolower(trim($jql)) == 'implements')
		{
			
			$fields = 'key,status,summary,start,end,timeoriginalestimate,timespent,labels,assignee,created,issuetype,issuelinks,emailAddress,aggregatetimespent,subtasks';
			
			
			$jirainfo = $project->GetJiraCredentials($this->task->Tags[0]);
			if($jirainfo != null)
				Jirarest::SetUrl($jirainfo->url,$jirainfo->user,$jirainfo->pass);
			
			$tasks  = Jirarest::Search("key=".$this->task->Tags[0],1,$fields);
			
			//var_dump($tasks[0]);
			//$this->task->Tags[0].EOL;
			
			//echo count($tasks[0]['issuelinks'][LINK_IMPLEMENTS]);
			
			$links = $tasks[0]['issuelinks'][LINK_IMPLEMENTS];
			
			$query="issue in (";
			$del = "";
			foreach($links as $link)
			{
				$query=$query."$del".$link;
				$del = ",";
			}
			$query=$query.")";
			if(count($links) == 0)
			{
				$query = '';
				return $query;
			}
			if($jirainfo != null)
			{
				$obj = new Obj();
				$obj->query = $query;
				$obj->jiracred = $jirainfo;
				return $obj;
			}
			return $query;
		}
		else if(strtolower(trim($jql)) == 'tests')
		{
			
			$fields = 'key,status,summary,start,end,timeoriginalestimate,timespent,labels,assignee,created,issuetype,issuelinks,emailAddress,aggregatetimespent,subtasks';
			
			$jirainfo = $project->GetJiraCredentials($this->task->Tags[0]);
			if($jirainfo != null)
				Jirarest::SetUrl($jirainfo->url,$jirainfo->user,$jirainfo->pass);
			
			$tasks  = Jirarest::Search("key=".$this->task->Tags[0],1,$fields);
			
			//var_dump($tasks[0]);
			//$this->task->Tags[0].EOL;
			
			//echo count($tasks[0]['issuelinks'][LINK_IMPLEMENTS]);
			
			$links = $tasks[0]['issuelinks'][LINK_TESTS];
			
			$query="issue in (";
			$del = "";
			foreach($links as $link)
			{
				$query=$query."$del".$link;
				$del = ",";
			}
			$query=$query.")";
			if(count($links) == 0)
			{
				$query = '';
				return $query;
			}
			if($jirainfo != null)
			{
				$obj = new Obj();
				$obj->query = $query;
				$obj->jiracred = $jirainfo;
				return $obj;
			}
			return $query;
		}
		else if(strtolower(trim($jql)) == 'implements+tests')
		{
			
			$fields = 'key,status,summary,start,end,timeoriginalestimate,timespent,labels,assignee,created,issuetype,issuelinks,emailAddress,aggregatetimespent,subtasks';
			
			$jirainfo = $project->GetJiraCredentials($this->task->Tags[0]);
			if($jirainfo != null)
				Jirarest::SetUrl($jirainfo->url,$jirainfo->user,$jirainfo->pass);
			
			$tasks  = Jirarest::Search("key=".$this->task->Tags[0],1,$fields);
			
			//var_dump($tasks[0]);
			//$this->task->Tags[0].EOL;
			
			//echo count($tasks[0]['issuelinks'][LINK_IMPLEMENTS]);
			$links = array_merge($tasks[0]['issuelinks'][LINK_IMPLEMENTS],$tasks[0]['issuelinks'][LINK_TESTS]);
			
			
			$query="issue in (";
			$del = "";
			foreach($links as $link)
			{
				$query=$query."$del".$link;
				$del = ",";
			}
			$query=$query.")";
			if(count($links) == 0)
			{
				$query = '';
				return $query;
			}
			if($jirainfo != null)
			{
				$obj = new Obj();
				$obj->query = $query;
				$obj->jiracred = $jirainfo;
				return $obj;
			}
			return $query;
		}
		else if(strtolower(trim($jql)) == 'sub-tasks')
		{
			$jirainfo = $project->GetJiraCredentials($this->task->Tags[0]);
			$query = 'parent = '.$this->task->Tags[0];
			
			if($jirainfo != null)
			{
				$obj = new Obj();
				$obj->query = $query;
				$obj->jiracred = $jirainfo;
				return $obj;
			}
			return $query;
		}
		else if(strtolower(trim($jql)) == 'subtasks')
		{
			$jirainfo = $project->GetJiraCredentials($this->task->Tags[0]);
			$query = 'issue in subtaskIssuesFromQuery("key='.$this->task->Tags[0] .'")';
			if($jirainfo != null)
			{
				$obj = new Obj();
				$obj->query = $query;
				$obj->jiracred = $jirainfo;
				return $obj;
			}
			return $query;
		}
		else if(strtolower(trim($jql)) == 'issuesinepic')
		{
			$jirainfo = $project->GetJiraCredentials($this->task->Tags[0]);
			$query = "'Epic Link' = ".$this->task->Tags[0];
			if($jirainfo == null)
				return $query;
			
			$obj = new Obj();
			$obj->query = $query;
			$obj->jiracred = $jirainfo;
			return $obj;
		}
		else
		{
			$njql = explode("=",$jql);
			$jirainfo = null;
			if(trim($njql[0]) == 'structure')
			{
				$result = Jirarest::GETStructureInfo($njql[1]);
				if($result == null)
				{
					echo "Structure ".$njql[1]." Not found on default Jira server".EOL;
					foreach($project->ExtraJiraCredentials as $jirainfo)
					{
						Jirarest::SetUrl($jirainfo->url,$jirainfo->user,$jirainfo->pass);
						$result = Jirarest::GETStructureInfo($njql[1]);
						if($result != null)
						{
							echo "Found on ".$jirainfo->url.EOL;
							break;
						}
						echo "Structure ".$njql[1]." Not found on ".$jirainfo->url.EOL;
					}
					if($result == null)
						exit(-1);
					
				}
				if(isset($result->error))
				{
					echo $result->error.EOL;
					exit(-1);
				}
				$rows = Jirarest::GetStructure($njql[1]);
				$query="id in (";
				$del = "";
				foreach($rows as $row)
				{
					$query=$query."$del".$row->taskid;
					$del = ",";
				}
				$query=$query.")";
				if($jirainfo == null)
					return $query;
			
				$obj = new Obj();
				$obj->query = $query;
				$obj->jiracred = $jirainfo;
				return $obj;
			}
			else
				return $jql;
		}
	}
	function __construct($project,$task,$jql,$jira,$rebuild)
	{
		global $PLAN_FOLDER;
		$this->task = $task;
		$this->jiracred = $jira;
		//echo "C ".$jira->url.EOL;
		Jirarest::SetUrl($jira->url,$jira->user,$jira->pass);
		
		$njql = $this->QueryPreProcess($project,$jql);
		if (is_object($njql))
		{
			$this->njql = $njql->query;
			$this->jiracred = $njql->jiracred;
		}
		else
			$this->njql = $njql;
		
		$this->jql = $jql;
		
		//$this->njql = $njql;
		
		
		//echo $task->Name." ".$task->IsParent.EOL;
		$md5 = md5($this->njql);
		//echo $njql." ".$md5.EOL;
		$this->filterfile = $PLAN_FOLDER."\\".$md5;
		$this->rebuild = $rebuild;
		
		
		if(strlen(trim($jql)) < 5) // bad check
		{
			echo "Invalid JQL ".$jql.EOL;
			exit();
			//$this->Run();
			/*$this->filter = new Filter($filterfile,$njql,$rebuild);
			foreach($this->filter->GetData() as $key=>$jtask)
			{
				$jtask->query =$this;
			}*/
		}
	}
	public function Run()
	{
		//echo "R ".$this->njql.EOL;
		//echo "R ".$this->jiracred->url.EOL;
		
		Jirarest::SetUrl($this->jiracred->url,$this->jiracred->user,$this->jiracred->pass);
		$rebuild = $this->rebuild;
		if (!file_exists($this->filterfile))
			$rebuild = 1;
		//echo $this->task->IsParent.EOL;
		//if($this->task->IsParent == 0)
		//	$rebuild = 1;
		//echo $this->njql.EOL;
		//echo "this->cached=".$this->cached.EOL;
		if( isset($this->cached))
			$this->filter = new Filter($this->filterfile,$this->njql,$rebuild,$this->cached);
		else
			$this->filter = new Filter($this->filterfile,$this->njql,$rebuild,-1);
		
		$data = $this->filter->GetData();
		if($data != null)
		{
			foreach($this->filter->GetData() as $key=>$jtask)
			{
				$jtask->query =$this;
			}
		}
		$this->rebuild = 0;
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'Task':
				return $this->task;
			case 'jql';
				return $this->jql;
			case 'Jiratasks':
				if($this->filter == null)
					return null;
				
				return $this->filter->GetData();
			default:
				echo "Query cannot get ".$name." property";
				break;
		}
	}
}
class JiraInfo
{
	private $url;
	private $user;
	private $pass;
	function __construct($root)
	{
		$infostring = trim($root->getAttribute('webLink'));
		$infostring = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $infostring);
		$info = explode(" ",$infostring);
		//echo count($info).EOL;
		if(count($info) != 2)
		{
			echo "Jira Info  missing or incomplete".EOL;
			echo "[$infostring] Given".EOL;
			echo "[url user:pass] Required".EOL;
			exit();
		}
		$this->url = trim($info[0]);
		$url = filter_var($this->url, FILTER_VALIDATE_URL);
		if (strlen($url) == 0) 
		{
			echo 'Jira url ['.$this->url.'] is not a valid URL'.EOL;
			echo "Update project properties".EOL;
			exit();
		}
		$info = explode(":",$info[1]);
		if(count($info) < 2)
		{
			$t=decrypt($info[0],"abcdef");
			$info = explode(":",$t);
		}
		
		if(count($info) != 2)
		{
			echo "Jira user:password missing or incomplete".EOL;
			echo "Update Project settings".EOL;
			exit();
		}
		$this->user = trim($info[0]);
		$this->pass = trim($info[1]);
		//echo '['.$this->url.']['.$this->user.']['.$this->pass.']'.EOL;
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'url':
				return $this->url;
			case 'user':
				return $this->user;
			case 'pass':
				return $this->pass;
		}
	}
}
class GanProject
{
	private $jira;
	private $name;
	private $start=null; 
	private $end=null;
	private $root;
	private $isarchived=0;
	private $additional_jira=array();
	
	function __construct($doc)
	{
		$this->root=$doc->documentElement; 
		$this->jira = new JiraInfo($this->root);
		//$jirainfo = trim($this->root->getAttribute('webLink'));
		//
		//if($this->jiraurl == 'http://')||(($this->jiraurl == ''))
		//{
		//	echo 'Jira url missing'.EOL;
		//}
		$this->name = $this->root->getAttribute('name');
		
		$descriptions = $this->root->getElementsByTagName('description');
		
		foreach($descriptions as $desc)
		{
			$data = explode("\n", $desc->textContent);
			foreach($data as $d)
			{
				if(strlen($d)<5)
					continue;
				$fields = explode("=",$d);
				switch(strtolower($fields[0]))
				{
					case 'archived':
						$this->isarchived = 1;
						break;
					case 'start':
						$dt = Date('Y-m-d',strtotime($fields[1]));
						$this->start =  $dt;
						break;
					case 'end':
						//Date('Y-m-D'.strtotime($fields[1]);
						$dt = Date('Y-m-d',strtotime($fields[1]));
						$this->end = $dt;
						break;
					case 'jiraurl':
						$subfields = explode(" ",$fields[1]);
						$projident = explode(",",$subfields[0]);
						$jiraurl = $subfields[1];
						
						$subfields[2]=decrypt($subfields[2],"abcdef");
						$cred = explode(":",$subfields[2]);
						$obj = new Obj();
						
						$obj->url=$jiraurl;
						$obj->user = $cred[0];
						$obj->pass = $cred[1];
						
						
						foreach($projident as $p)
						{
							$this->additional_jira[strtoupper($p)] = $obj;
						}
						//echo $jiraurl.EOL;
						//var_dump($cred);
						//var_dump($projident);
						//var_dump($obj);
						break;
					default:
						echo "Unknown filed='".$fields[0]."' configured in project ".EOL;
						break;
				}
			}
		}
		if(($this->start == null)||($this->end == null))
		{
			echo "Project Start or End missing.".EOL;
			echo "Update Project properties".EOL;
			exit();
		}
	}
	public function GetJiraCredentials($jirakey)
	{
		$j = explode("-",$jirakey);
		if(array_key_exists(strtoupper($j[0]),$this->additional_jira))
		{
			return $this->additional_jira[strtoupper($j[0])];
		}
		return null;
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'IsArchived':
				return $this->isarchived;
				break;
			case 'ExtraJiraCredentials':
				return $this->additional_jira;
				break;
			case  'Start':
				return $this->start;
				break;
			case 'End':
				return $this->end;
				break;
			case 'Jira':
				return $this->jira;
			//case 'Jira':
			//	return $this->jira->url;
			//case 'Jiraurl':
			//	return $this->jira->url;
			//	break;
			case 'Name':
				return $this->name;
				break;
			default:
				echo "GanProject cannot get ".$name." property";
				break;
		}
	}
	public function __set($name,$value)
	{
		switch($name)
		{
			//case 'Jiraurl':
			//	$this->jiraurl = $value;
			//	$this->root->setAttribute('webLink',$value);
			//	break;
			case 'Name':
				$this->name = $value;
				$this->root->setAttribute('name',$value);
				break;
			default:
				echo "GanProject cannot set ".$name." property";
				break;
		}
	}
}
class GanCalendar
{
	private $holidays=array();
	function __construct($doc)
	{
		$xpath = new DOMXPath($doc);
		$records = $xpath->query('/project/calendars/date');
		foreach ($records as $i => $record) 
		{
			if($record->getAttribute('type')=="HOLIDAY")
			{
				$year = $record->getAttribute('year');
				$month = $record->getAttribute('month');
				$date = $record->getAttribute('date');
				$this->holidays[] = $year."-".$month."-".$date;
			}
		}
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'Holidays':
				return $this->holidays;
				break;
			default:
				echo "GanCalendar cannot get ".$name." property";
				break;
		}
	}
}


class GanResource
{
	private $name;
	private $email;
	private $id;
	private $efficiency = 1.0;
	private $group = null;
	private $domelement;
	private $parent;
	private $tasks=array();
	private $vacations=array();

	
	function __construct($doc,$DOMElement=null,$effciencyid=null,$groupid=null,$parent=null)
	{
		if($DOMElement == null)
		{
			$this->domelement = $doc->createElement('resource');
			$this->domelement->setAttribute('function',"Default:0");
			$this->domelement->setAttribute('phone','');
			return;
		}
		$this->parent = $parent;
		$this->name = $DOMElement->getAttribute('name');
		$this->id = $DOMElement->getAttribute('id');
		$this->email = $DOMElement->getAttribute('contacts');
		$this->domelement = $DOMElement;
		if($effciencyid != null)
		{
			$customproperties = $DOMElement->getElementsByTagName('custom-property');
			foreach($customproperties as $cp)
			{
				if($cp->getAttribute('definition-id') == $effciencyid)
				{
					$this->efficiency = $cp->getAttribute('value');
					if(($this->efficiency <0) || ($this->efficiency > 1))
						$this->efficiency = 1.0;
					break;
				}
			}
		}
		if($groupid != null)
		{
			$customproperties = $DOMElement->getElementsByTagName('custom-property');
			foreach($customproperties as $cp)
			{
				if($cp->getAttribute('definition-id') == $groupid)
				{
					$group = $cp->getAttribute('value');
					$group = explode(",",$group);
					foreach($group as $name)
					{
						$resource = $this->parent->FindResource($name);
						if($resource==null)
						{
							echo "Group resource name ".$name." not found";
						}
						else
							$this->group[] = $resource;
						
					}
					break;
				}
			}
		}
			
	}
	public function __set($name,$value)
	{
		switch($name)
		{
			case 'Task':
				$this->tasks[$value->Id] = $value;
				break;
			case 'Vacation':
				$this->vacations[] = $value;
				break;
			case 'Email':
				$this->email = $value;
				$this->domelement->setAttribute('contacts',$this->email);
				break;
			case 'Name':
				$this->name = $value;
				$this->domelement->setAttribute('name',$this->name);
				break;
			case 'Id':
				$this->id = $value;
				$this->domelement->setAttribute('id',$this->id);
				break;
			default:
				echo "GanResource cannot set ".$name." property";
				break;
		}
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'Group':
				return $this->group;
				break;
			case 'Email':
				return $this->email;
				break;
			case 'Tasks':
				return $this->tasks;
				break;
			case 'DOMElement':
				return $this->domelement;
			case 'Vacations':
				return $this->vacations;
				break;
			case 'Id':
				return $this->id;
				break;
			case 'Name':
				return $this->name;
				break;
			case 'Efficiency':
				return $this->efficiency;
				break;
			default:
				echo "GanResource cannot get ".$name." property";
				break;
		}
	}
	public function __toString()
	{
		$str = $this->id." ".$this->name;
		foreach($this->vacations as $vacation)
		{
			$str = $str." ".$vacation;
		}
		return $str;
	}
}

class GanResources
{
	private $list=array();
	private $doc;
	function __construct($doc)
	{
		$this->doc =  $doc;
		$xpath = new DOMXPath($doc);
		
		$efficiencyid = null;
		$groupid = null;
		$records = $xpath->query('/project/resources/custom-property-definition');
		foreach ($records as $i => $cd) 
		{
			if( strtolower($cd->getAttribute('name')) == 'efficiency')
			{
				$efficiencyid = $cd->getAttribute('id');	
			}
			else if( strtolower($cd->getAttribute('name')) == 'group')
			{
				$groupid = $cd->getAttribute('id');
			}
		}
		$records = $xpath->query('/project/resources/resource');
		foreach ($records as $i => $record) 
		{
			$resource = new GanResource($doc,$record,$efficiencyid,$groupid,$this);
			$this->list[$resource->Id] = $resource; 
		}
		// Read the vacations of resources
		$records = $xpath->query('/project/vacations/vacation');
		foreach ($records as $i => $record) 
		{
			$resourceid = $record->getAttribute('resourceid');
			$start = $record->getAttribute('start');
			$end = $record->getAttribute('end');
			$interval = DateInterval::createFromDateString('1 day');
			$daterange = new DatePeriod( new DateTime($start), $interval, new DateTime($end));
			
			foreach($daterange as $date)
			{
				$this->list[$resourceid]->Vacation = $date->format("Y-m-d");
			}
			
		}
	}
	public function FindResource($name)
	{
		foreach($this->list as $res)
		{
			if($res->Name == $name)
				return $res;
		}
		return null;
	}
	public function Add($resource)
	{
		$nextid=0;
		foreach($this->list as $res)
		{
			//echo $res->Name." ".$resource->Name."-".EOL;
			if($res->Name == $resource->Name)
			{
				echo "Resource ".$resource->Name." already exist".EOL;
				return -1;
			}
			if($nextid<=$res->Id)
			{
				$nextid = $res->Id+1;
			}
		}
		$resource->Id = $nextid;
		$pnode = $this->doc->getElementsByTagName('resources')->item(0);
		$pnode->appendChild($resource->DOMElement);
		$this->list[$nextid] = $resource;
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'List':
				return $this->list;
				break;
			default:
				echo "GanResources cannot get ".$name." property";
				break;
		}
	}
}

class GanAllocation
{
	private $domelement;
	private $taskid;
	private $resourceid;
	function __construct($DOMElement)
	{
		$this->domelement = $DOMElement;
		//<allocation task-id="0" resource-id="0" function="Default:0" responsible="true" load="100.0"/>
		$this->taskid = $DOMElement->getAttribute('task-id');
		$this->resourceid = $DOMElement->getAttribute('resource-id');
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'TaskId':
				return $this->taskid;
			case 'ResourceId':
				return $this->resourceid;
			default:
				echo "GanAllocation cannot access ".$name." property";
				break;
		}
	}
	
}
class GanAllocations
{
	private $list=array();
	private $doc;
	function __construct($doc)
	{
		$this->doc = $doc;
		$xpath = new DOMXPath($doc);
		$records = $xpath->query('/project/allocations/allocation');
		foreach ($records as $i => $allocation) 
		{
			$allocation = new GanAllocation($allocation);
			$index = $allocation->TaskId.$allocation->ResourceId;
			$this->list[$index] = $allocation;
		}
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'List':
				return $this->list;
			default:
				echo "GanAllocations cannot access ".$name." property";
				break;
		}
	}
}
class CustomProperty
{
	private $domelement;
	private $id;
	private $name;
	function __construct($DOMElement)
	{
		$this->domelement = $DOMElement;
		$this->id = $DOMElement->getAttribute('id');
		$this->name = $DOMElement->getAttribute('name');
		//echo $this->id." ".$this->name.EOL;
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'Id':
				return $this->id;
			case 'Name':
				return $this->name;
			default:
				echo "CustomProperty cannot access ".$name." property";
				break;
		}
	}
}
class CustomProperties
{
	private $doc;
	private $list;
	function __construct($doc)
	{
		$this->doc = $doc;
		//$depth =  $this->Depth();
		$xpath = new DOMXPath($doc);
		$records = $xpath->query('/project/tasks/taskproperties/taskproperty');
		foreach ($records as $i => $taskproperty) 
		{
			$cp = new CustomProperty($taskproperty);
			$this->list[$cp->Id] =  $cp;
		}
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'List':
				return $this->list;
			default:
				echo "CustomProperties cannot access ".$name." property";
				break;
			
		}
	}
}
class GanTask
{
	private $doc;
	private $hassubtasks = 0;
	private $children = array();
	private $domelement;
	private $name;
	private $id;
	private $jaresource=0;
	private $aresource=null;          // actual assigned resource 
	private $resources=array();  // resources assigned for planning
	private $effort=0.1;       // effort planned
	private $timespent=0;
	private $aeffort=null;       // actual effort 
	private $query=null;
	private $level;
	private $extid;
	private $deadline=null;
	private $tstart=null;
	private $successors_ids=array();
	private $predecessors=array();
	private $tags=array();
	private $status = 'OPEN';
	private $start;            // Task start date 
	private $end;              // Task end date
	private $isparent=0;
	private $tag_cpid=null;
	private $priority = -1;
	private $rassigned = false;// Flag to see if resourse is assigned or not in aresource
	private $tagxmlnode = null;
	private $IsTrakingDatesGiven=0;
	private $parenttask=null;
	private $jtask=null;
	private $exclude = 0;
	private $querynode;
	public $handled=0;
	public $refcount=0;
	private $project;
	//<task id="0" name="Project-1" color="#8cb6ce" meeting="false" start="2017-08-24" duration="1" complete="0" expand="true">
	function __construct($project,$parenttask,$doc, $DOMElement=null,$cproperties=null,$jira=null,$rebuild=0,$tstart=null,$tend=null)
	{
		$this->doc = $doc;
		$this->tstart = $tstart;
		$this->parenttask = $parenttask;
		$this->deadline = $tend;
		$this->project = $project;
		$found = 0;
		$queryid = 0;
		foreach($cproperties->List as $cpid=>$obj)
		{
			//echo $obj->Name.EOL;
			if( strtolower(trim($obj->Name)) == 'tag')
			{
				$this->tag_cpid = $cpid;
				$found++;
			}
			if( strtolower(trim($obj->Name)) == 'query')
			{
				$queryid = $cpid;
				$found++;
			}
		}
		//echo $queryid.$found.EOL;
		if($found != 2)
		{
			echo "Tag/Query Column not found".EOL;
			echo "Create one in project and retry".EOL;
			exit(1);
		}
		if($DOMElement == null)
		{
			$this->domelement = $doc->createElement('task');
			$this->domelement->setAttribute('duration',1);
			$this->domelement->setAttribute('complete',0);
			$this->domelement->setAttribute('expand',"true");
			$this->domelement->setAttribute('meeting',"false");
			
			$cp = $doc->createElement('customproperty');
			$cp->setAttribute('taskproperty-id',$queryid);
			$cp->setAttribute('value','');
			$this->domelement->appendChild($cp);
			$this->querynode = $cp;
			return;
		}
		$this->domelement = $DOMElement;
		$this->id = $DOMElement->getAttribute('id');
		
		if($this->id == 0)
			$this->id = -1;
		
	
		$this->name = $DOMElement->getAttribute('name');
	
			
		$childtasks = $DOMElement->childNodes;
		$sextid = 1;
		
		$childnodes = $DOMElement->childNodes;
		
		$querystring = null;
		foreach($childnodes as $child)
		{
			if($child->nodeName == 'customproperty')
			{
				$cpid = $child->getAttribute('taskproperty-id');
				$cpvalue = $child->getAttribute('value');
				//echo $cpid.$cproperties->List[$cpid]->Name.EOL;
				switch( strtolower(trim($cproperties->List[$cpid]->Name)))
				{
					case 'effort':
						if($cpvalue > 0)
							$this->effort = $cpvalue;
						break;
					case 'query':
						if(strlen(trim($cpvalue)) > 0)
						{
							$querystring = trim($cpvalue);
						}
						$this->querynode = $child;
						break;
					case 'tag':
						//echo $cpvalue.EOL;
						$this->tags = explode(",",$cpvalue);
						break;
					case 'deadline':
						$this->IsTrakingDatesGiven = 2;
						$this->deadline = explode("T",$cpvalue)[0];
						break;
					case 'tracking start':
						$this->IsTrakingDatesGiven = 1;
						$this->tstart = explode("T",$cpvalue)[0];
						break;
					default:
						echo "Unknown custom property ".$cproperties->List[$cpid]->Name." ".EOL;
						break;
				}
				//echo $cpid." ".$cpvalue.EOL;
			}
			else if($child->nodeName == 'depend')
			{
				$this->successors_ids[] = $child->getAttribute('id');
			}
		}
		if($querystring != null)
		{
			$query =  new Query($project,$this,$querystring,$jira,$rebuild);
			$this->query = $query;//$cpvalue;
			
		}
	}
	public function __set($name,$value)
	{
		switch($name)
		{
			case 'Query':
				if(strlen(trim($value)) > 0)
				{
					//$query =  new Query($this->project,$this,trim($value),$jira,$rebuild);
					//$this->query = $query;//$cpvalue;
					$this->querynode->setAttribute('value',$value);
				}
				break;
			case 'Exclude':
				$this->exclude = $value;
				break;
			case 'Jtask':
				$this->jtask =  $value;
				break;
			case 'Parenttask':
				$this->parenttask = $value;
				break;
			case 'HasSubtasks':
				$this->hassubtasks = $value;
				return;
			case 'Priority':
				$this->priority = $value;
				break;
			case 'Parent':
				$this->isparent = $value;
				break;
			case 'Timespent':
				if($value > 0.1)
					$this->timespent = $value;
				break;
			case 'Id':
				$this->id = $value;
				$this->domelement->setAttribute('id',$value);
				break;
			case 'Name':
				$value = str_replace('"'," ",$value);
				$value = str_replace('&'," ",$value);
				$this->name = $value;
				$this->domelement->setAttribute('name',$value);
				break;	
			case 'Tag':
				//echo "setting ".$value.EOL;
				$this->tags[] = $value;
				if($this->tagxmlnode == null)
				{
					$cp = $this->doc->createElement('customproperty');
					$cp->setAttribute('taskproperty-id',$this->tag_cpid);
					$cp->setAttribute('value',$value);
					$this->domelement->appendChild($cp);
					$this->tagxmlnode = $cp;
				}
				else
				{
					$tag = implode(",",$this->tags);
					$this->tagxmlnode->setAttribute('value',$tag);
				}
				
				break;
			case 'IsParent':
				$this->isparent = $value;
				break;
			case 'ExtId':
				$this->extid = $value;
				break;
			case 'Level':
				$this->level = $value;
				break;
			case 'Child':
				$this->children[] = $value;
				break;
			case 'Predecessor':
				$this->predecessors[] = $value;
				break;
			case 'JiraAssignedResource';
				$this->jaresource = $value;
				break;
			case 'ActualResource':
				if($value != null)
				{
					$value->Task = $this;
					$this->aresource = $value;
				}
				break;
			case 'PlannedResource':
				if($value != null)
				{
					$value->Task = $this;
					$this->resources[] = $value;
				}
				break;
			case 'ActualEffort':
				$this->aeffort = $value;
				break;
			case 'PlannedEffort':
				if($value > 0)
					$this->effort = $value;
				break;
			case 'Status':
				$this->status = strtoupper($value);
				if (( $this->status == 'DONE')||( $this->status == 'RESOLVED')||($this->status == 'CLOSED' )||($this->status == 'IMPLEMENTED' )||($this->status == 'VERIFIED')||($this->status == 'SATISFIED'))
				{
					$this->status = 'RESOLVED';
					break;
				}
				if ( ($this->status == 'OPEN')||($this->status == 'REOPENED') )
					$this->status = 'OPEN';
				else
					$this->status = 'OPEN';
				break;
			case 'Start':
				$this->start = $value;
				break;
			case 'End':
				$this->end = $value;
				break;
			default:
				echo "GanTask cannot set ".$name." property";
				break;
			
		}
	}
	
	public function __get($name)
	{
		switch($name)
		{
			case 'IsExcluded':
				foreach($this->Tags as $tag)
				{
					if(strtoupper($tag) == 'EXCLUDE')
						return true;
				}
				return $this->exclude;
			case 'Jtask':
				return $this->jtask;
				break;
			case 'JiraId':
				if(count($this->Tags)>0)
					return $this->Tags[0];
				else
					return null;
				break;
			case 'Parenttask':
				return $this->parenttask;
				break;
			case 'HasSubtasks':
				return $this->hassubtasks;
			case 'IsTrakingDatesGiven':
				return $this->IsTrakingDatesGiven;
			case 'Priority':
				return $this->priority;
				break;
			case 'IsDelayed':
				if(($this->timespent > $this->aeffort)&&($this->timespent > $this->effort))
					return true;
				return false;
			case 'StartConstraintDate':
				return $this->tstart;
				return null;
			case 'Timespent':
				if($this->Status == 'RESOLVED')
					return $this->Duration;
				return $this->timespent;
				break;
			case 'IsParent':
				return $this->isparent;
			case 'DOMElement':
				return $this->domelement;
				
			case 'Status':
				if($this->status == 'OPEN')
					if($this->timespent > 0)
						return 'IN PROGRESS';
				return $this->status;
			case 'JiraAssignedResource';
				return $this->jaresource;
				break;
			case 'Tags':
				return $this->tags;
				break;
			case 'Id':
				return $this->id;
				break;
			case 'Title':
			case 'Name':
				return $this->name;
				break;
			case 'ExtId':
				return $this->extid;
				break;
			case 'Level':
				return $this->level;
				break;
			case 'Children':
				return $this->children;
				break;
			case 'Query':
				return $this->query;
				break;
			case 'ChildCount':
				return count($this->children);
			case 'TrackingStartDate':
			case 'Tstart':
				return $this->tstart;
			case 'TrackingEndDate':
			case 'Tend':
				return $this->deadline;
			case 'Deadline':
				if($this->IsTrakingDatesGiven)
					return $this->deadline;
				else
					return null;
			case 'Start':
				return $this->start;
			case 'End':
				return $this->end;
			case 'PlannedResources':
				return $this->resources;
			case 'SuccessorIds':
				return $this->successors_ids;
			case 'Predecessors':
				return $this->predecessors;
			case 'PlannedEffort':
				return $this->effort;
			case 'Progress':
				if($this->Status == 'RESOLVED')
					return 100;
				$duration = $this->Duration;
				$progress = round($this->timespent/$duration*100,1);
				if($progress > 100)
					return 100;
				return $progress;
			case 'ActualResource':
				return $this->aresource;
				break;
			case 'ActualEffort':
				return $this->aeffort;
				break;
			case 'Duration':
				if($this->ActualEffort == null)
				{
					$effort = $this->PlannedEffort;
				}
				else
				{
					$effort = $this->ActualEffort;
				}
				if($this->timespent > $effort)
				{
					return $this->timespent;
				}
				return $effort;
				break;

			case 'Resources':
				if($this->isparent)
				{
					$a = array();
					return $a;
				}
				if($this->ActualResource == null)
				{
					$groupresources = array();
					foreach($this->PlannedResources as $resource)
					{
						
						if($resource->Group != null)
						{
							$groupresources  = array_merge($groupresources , $resource->Group);
						}
						else
							$groupresources[] = $resource;
					}
					return $groupresources;
					//return $this->PlannedResources;
				}
				else
				{
					$a = array();
					$a[0] = $this->ActualResource;
					return $a;
				}
			default:
				echo "GanTask cannot access ".$name." property";
				break;
			
		}
	}
	
	public function __toString()
	{
		$str = $this->ExtId." ".$this->Level." ".$this->Id." ".$this->Name;
		return $str;
	}
	
}
class GanTasks
{
	private $tree;
	private $list;
	private $doc;
	private $cproperties;
	private $jira;
	private $listbyexitid=array();
	
	function ProcessTaskNode($project,$DOMElement,$level,$extid,$rebuild,$tstart,$tend,$parenttask=null)
	{
		$task = new GanTask($project,$parenttask,$this->doc,$DOMElement,$this->cproperties,$this->jira,$rebuild,$tstart,$tend);
		$this->list[$task->Id] = $task;
		$task->Level = $level;
		$task->ExtId = $extid;
		//echo $task->Id." ".count($task->Resources).EOL;
		$task->IsParent = 0;
		$this->listbyexitid[$task->ExtId] = $task;
		$childtasks = $DOMElement->childNodes;
		$sectid = 1;
		foreach($childtasks as $ctask)
		{
			if($ctask->nodeName == 'task')
			{
				$task->IsParent = 1;
				$tstart = $task->TrackingStartDate;
				$tend = $task->TrackingEndDate;
				$task->Child = $this->ProcessTaskNode($project,$ctask,$level+1,$extid.".".$sectid,$rebuild,$tstart,$tend,$task);
				$sectid = $sectid + 1;
			}
		}
		return $task;
	}
	function AddChild($task,$parent)
	{
		$nextid = 0;
		foreach($this->list as $t)
		{
			if($nextid <= $t->Id )
				$nextid = $t->Id + 1;
		}
		if($nextid == 0)
			$nextid = 1;
		$task->Id= $nextid;
		$task->Level = $parent->Level+1;
		
		$count = count($parent->Children);
		$task->ExtId =  $parent->ExtId.'.'.(string)($count+1);
		$task->IsParent = 0;
		//echo $task->Name.EOL;
		$parent->Parent = 1;
		$domelement = $parent->DOMElement;
		
		$domelement->appendChild($task->DOMElement);
		//echo count($this->list[0]->Children).EOL;
		$parent->Child = $task;
		$this->list[$task->Id] = $task;
		$this->listbyexitid[$task->ExtId] = $task;
		//echo count($this->list[0]->Children).EOL;
	}
	function Add($task)
	{
		$nextid = 0;
		foreach($this->list as $t)
		{
			if($nextid <= $t->Id )
				$nextid = $t->Id + 1;
		}
		if($nextid == 0)
			$nextid = 1;
		$task->Id= $nextid;
		$task->Level = 2;
		$count = count($this->tree[0]->Children);
		$task->ExtId = '1.'.(string)($count+1);
		$task->IsParent = 0;
		//echo $task->Name.EOL;
		$parent = $this->tree[0]->DOMElement;
		$this->tree[0]->Parent = 1;
		$parent->appendChild($task->DOMElement);
		//echo count($this->list[0]->Children).EOL;
		$this->tree[0]->Child = $task;
		$this->list[$task->Id] = $task;
		$this->listbyexitid[$task->ExtId] = $task;
		//echo count($this->list[0]->Children).EOL;
	}
	function ResolveDependecy()
	{
		foreach($this->list as $task)
		{
			//if(count($task->SuccessorIds)>0)
			//echo $task->Id."-------->";
			foreach($task->SuccessorIds as $id)
			{
				if(array_key_exists($id,$this->list))
					$this->list[$id]->Predecessor = $task;
			}
		}
	}
	function Depth()
	{
		$xpath = new DOMXPath($this->doc);
		$depth = 0;
		$searchpath = '/project/tasks';
		while(1)
		{
			$searchpath = $searchpath."/task";
			
			$records = $xpath->query($searchpath);
		
			if($records->length== 0)
				return $depth;
			else
				$depth = $depth + 1;
		}
	}
	
	function __construct($doc,$cproperties,$jira=null,$rebuild=0,$project=null)
	{
		$this->doc = $doc;
		$this->cproperties = $cproperties;
		$this->jira = $jira;
		$xpath = new DOMXPath($doc);
		$records = $xpath->query('/project/tasks/task');
	
		$extid = 1;
		foreach ($records as $i => $task) 
		{

			{
				$start = $project->Start;
				$end = $project->End;
				$this->tree[] = $this->ProcessTaskNode($project,$task,1,$extid,$rebuild,$start,$end);
				$this->ResolveDependecy();
				$this->UpdateTasks();
				return 1;
			}
		}
		//$this->ResolveDependecy();
		//$this->UpdateTasks();
		throw new Exception( 'Failed!' );
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'List':
				return $this->list;
			case 'Tree':
				return $this->tree;
			case 'ListByExtId':
				return $this->listbyexitid;
			default:
				echo "GanTasks cannot access ".$name." property";
				break;
			
		}
		
	}
	private $priority;
	private $max_priority;
	private function AssignPriority($task,$exclude=0)
	{
		$task->Exclude = $exclude;
		if($task->IsParent)
		{
			$task->Priority=-1;
			foreach($task->Children as $ctask)
			{
				$this->AssignPriority($ctask,$task ->IsExcluded);
			}
		}
		
		if($task->Status == 'IN PROGRESS')
			$task->Priority = $this->max_priority--;
		else
			$task->Priority = $this->priority--;
		
		//echo $task->Tags[0]." ".$task->Status." ".$task->Priority.EOL;
		
	}
	public function UpdateTasks()
	{
		$this->UpdateSummaryTasksStatus();
		$this->UpdateSummaryTasksTimeSpent();
		$this->UpdateSummaryTasksDuration();
		$this->UpdateTags();
		//$this->ComputeResourceUtilization();
		
		$this->priority  = count($this->list);
		$this->max_priority = $this->priority*2;
		if($this->max_priority > 1000)
			$this->max_priority = 1000;
		foreach($this->tree as $task )
			$this->AssignPriority($task);
			
		/*foreach($this->list as $task )
		{
			if($task->IsParent)
				$task->Priority=-1;
			else
				$task->Priority = $priority--;
		}*/
	}
	private function UpdateTags()
	{
		return;
		/*foreach($this->list as $task)
		{
			if($task->IsParent)
			{
				if(strlen($task->Tag)>0)
				{
					if($task->Tag[0] != '#')
						$task->Tag = '#'.$task->Tag;
				}
			}
		}*/
	}
	private function ComputeStatus(&$task)
	{
		if($task->IsParent == 0)
		{
			//echo "-->".$task->Jira." ".$task->Duration.EOL;
			return $task->Status;
		}
		//$task->Status = 'RESOLVED';
		$children = $task->Children;
		$status_srray = array();
		for($i=0;$i<count($children);$i++)
		{
			$status = $this->ComputeStatus($children[$i]);
			//echo $children[$i]->Tag." ".$status.EOL;
			$status_srray[$status] = 1;
		}
		//print_r($status_srray);
		if (array_key_exists("IN PROGRESS",$status_srray))
				$task->Status = "IN PROGRESS";
		else if (array_key_exists("OPEN",$status_srray))
			$task->Status = "OPEN";
		else if (array_key_exists("RESOLVED",$status_srray))
			$task->Status = "RESOLVED";
		else
		{
			echo "unknown task status ";
			$task->Status = "OPEN";
			print_r($status_srray);
		}
		//echo $task->Status.EOL;
		return $task->Status;
		
	}
	private function ComputeDuration(&$task)
	{
		if($task->IsParent == 0)
		{
			//echo $task->Id." ".$task->Duration.EOL;
			return $task->Duration;
		}
		$duration = 0;
		$children = $task->Children;
		//echo "pre  ". $duration.EOL;
		for($i=0;$i<count($children);$i++)
		{
			$duration = $duration + $this->ComputeDuration($children[$i]);
		}
		if($task->Timespent > $duration)
			$task->ActualEffort = $task->Timespent;
		else
			$task->ActualEffort = $duration;
		//echo $task->Id." ".$task->Duration.EOL;
		return $task->ActualEffort;
		
	}
	private function UpdateSummaryTasksDuration()
	{
		$tasks = $this->tree;
		for ($i=0;$i<count($tasks);$i++)
		{
			$this->ComputeDuration($tasks[$i]);
		}

	}
	private function ComputeTimeSpent(&$task)
	{
		if($task->IsParent == 0)
		{
			return $task->Timespent;
		}
		$timespent = 0;
		$children = $task->Children;
		for($i=0;$i<count($children);$i++)
		{
			$timespent = $timespent + $this->ComputeTimeSpent($children[$i]);
			
		}
		$task->Timespent = $timespent;
		return $task->Timespent;
		
	}
	private function UpdateSummaryTasksTimeSpent()
	{
		$tasks = $this->tree;
		for ($i=0;$i<count($tasks);$i++)
		{
			$this->ComputeTimeSpent($tasks[$i]);
		}

	}
	private function UpdateSummaryTasksStatus()
	{
		$tasks = $this->tree;
		for ($i=0;$i<count($tasks);$i++)
		{
			$this->ComputeStatus($tasks[$i]);
		}

	}
}
class Gan
{
	private $project;
	private $calendar;
	private $resources;
	private $allocations;
	private $cproperties;
	private $tasks;
	private $doc;
	private $filename;
	function __construct($filename,$rebuild=0)
	{
		if(($filename == null)||(!file_exists ($filename)))
		{
			echo $filename." does not exist".EOL;
			return;
		}
		$this->filename = $filename;
		$xmldata = file_get_contents($filename);
		$this->doc = new DOMDocument();
		$this->doc->loadXML($xmldata);
		$this->project = new GanProject($this->doc);
		$this->calendar = new GanCalendar($this->doc);
		$this->cproperties =  new CustomProperties($this->doc);
		$this->tasks = new GanTasks($this->doc,$this->cproperties,$this->project->Jira,$rebuild,$this->project);
		$this->resources =  new GanResources($this->doc);
		$this->allocations =  new GanAllocations($this->doc);
		$this->AssignResource();
	}
	function Update()
	{
		$this->tasks->UpdateTasks();
	}
	function AddTask($name,$tag,$ptask=null)
	{
		$tstart = $this->Start;
		$tend = $this->End;
		$parenttask = $ptask;
		if($ptask!=null)
		{
			$tstart = $ptask->TrackingStartDate;
			$tend = $ptask->TrackingEndDate;
			$parenttask = $this->TaskTree[0];
		}
		$task =  new GanTask(null,$parenttask,$this->doc,null,$this->cproperties,null,0,$tstart,$tend);
		$task->Name =  $name;
		$task->Tag = $tag;
		if($ptask == null)
			$this->tasks->Add($task);
		else
		{
			$this->tasks->AddChild($task,$ptask);
		}
		return $task;
	}
	function GetResource($name)
	{
		//echo "Looking fof [".$name."]".EOL;
		foreach($this->Resources as $resource)
		{
			if($resource->Name == $name)
			{
				//echo "returning ".$resource->Name.EOL;
				return $resource;
			}
		}
		return null;
	}
	
	function AddResource($name,$email=null)
	{
		$resource = $this->GetResource($name);
		if($resource==null)
		{
			$resource =  new GanResource($this->doc);
			$resource->Name = $name;
			$resource->Email = $email;
			$this->resources->Add($resource);
		}
		else
			$resource->Email = $email;
		return $resource;
	}
	function AssignResource()
	{
		foreach($this->allocations->List as $allocation)
		{
			$resource = $this->Resources[$allocation->ResourceId];
			//echo $allocation->TaskId." ".$allocation->ResourceId.EOL;
			if(array_key_exists($allocation->TaskId,$this->TaskList))
			{
				
				$task = $this->TaskList[$allocation->TaskId];
				$task->PlannedResource = $resource;
			}
		}
	}

	function Save($filename=null)
	{
		if($filename == null)
			$filename = $this->filename;

		$this->doc->save($filename);
	}
	public function __get($name)
	{
		switch($name)
		{
			case 'TaskListByExtId':
				return $this->tasks->ListByExtId;
			case 'Queries':
				$q = array();
				
				foreach($this->tasks->List as $task)
				{
					//echo $task->Name.EOL;
					if( $task->Query != null)
						$q[] = $task->Query;
				}
				return $q;
				//return $this->tasks->Tree[0]->Query;
			case 'Progress':
				return $this->tasks->Tree[0]->Progress;
			case 'Start':
				return $this->project->Start;
			case 'End':
				return $this->project->End;
			case 'Jira':
				return $this->project->Jira;
			//case 'Jiraurl':
			//	return $this->project->Jiraurl;
			//	break;
			case 'Name':
				return $this->project->Name;
				break;
			case 'Holidays':
				return $this->calendar->Holidays;
				break;
			case 'Resources':
				return $this->resources->List;
			case 'TaskTree':
				return $this->tasks->Tree;
			case 'TaskList':
				return $this->tasks->List;
			case 'CustomProperties':
				return $this->cproperties->List;
			
			default:
				echo "Gan cannot access ".$name." property";
				break;
		}
	}
	public function __set($name,$value)
	{
		switch($name)
		{
			//case 'Jiraurl':
			//	$this->project->Jiraurl = $value;
			//	break;
			default:
				echo "Gan cannot set ".$name." property";
				break;
		}
	}
	public function DumpTask($task)
	{
		$color = 'white';
		if($task->IsParent == 1)
		{
			if($task->Level == 1)
				$color = '#ffcccc';
			else
				$color = 'yellow';
		}
			
		echo '<tr bgcolor='.$color.'>';
		echo '<td>'.$task->Id.'</td>';
		echo '<td>'.$task->Level.'</td>';
		echo '<td>'.$task->ExtId.'</td>';
		echo '<td>'.$task->IsParent.'</td>';
		echo '<td>'.$task->HasSubtasks.'</td>';
		echo '<td>'.$task->ChildCount.'</td>';
		if($task->Parenttask ==  null)
			echo '<td>'.'nu'.'</td>';
		else
			echo '<td>'.$task->Parenttask->Id.'</td>';
		echo '<td>';
		for($i=1;$i<$task->Level;$i++)
			echo "&nbsp&nbsp";
		echo $task->Name.'</td>';
		if($task->Query == null)
			echo '<td>'."".'</td>';
		else
			echo '<td>'.$task->Query->jql.'</td>';
		echo '<td>';
		foreach($task->Tags as $tag)
			echo $tag.",";
		echo '</td>';
		echo '<td>'.$task->Status.'</td>';
		echo '<td>'.$task->Start.'</td>';
		echo '<td>'.$task->End.'</td>';
		
		$color = 'black';
	
		
		$str="";
		if($task->IsParent == 1)
		{
			
		}
		else
		{
			$color = 'blue';
			if($task->ActualResource == null)
				$color = 'black';
			foreach($task->Resources as $resource)
				$str = $str.$resource->Name.",";
		}
		echo '<td style="color:'.$color.'">';
		echo $str;
		echo '</td>';
		
		if($task->ActualEffort == null)
			$color = 'black';
		else
			$color = 'blue';
		
		
		echo '<td style="color:'.$color.';">'.$task->Duration.'</td>';
		
		if($task->Timespent > 0)
			echo '<td style="color:blue;">'.round($task->Timespent,1).'</td>';
		else
			echo '<td>'.round($task->Timespent,1).'</td>';
		echo '<td>'.$task->Progress.'</td>';  
		echo '<td>'.'No early'.'</td>';  
		echo '<td>'.$task->Deadline.'</td>';  
		
		echo '<td>';
		foreach($task->SuccessorIds as $id)
			echo $id.",";
		'</td>';
		
		echo '<td>';
		//echo count($task->Predecessors).EOL;
		foreach($task->Predecessors as $stask)
			echo $task->Id.",";
		'</td>'; 
		echo '<td>'.$task->Tstart.'</td>';
		echo '<td>'.$task->Tend.'</td>';
		echo '</tr>';
		foreach($task->Children as $stask)
			$this->DumpTask($stask);
	}
	public function Dump($debug=1,$header=1)
	{
		if($debug == 0)
			return;
		if($header==1)
		{
			echo '<table style="font-size: 70%;" border="1"><col width="80"><col width="200">';
			
			// Project Name
			echo '<tr>';
			echo '<td>Project</td>';
			echo '<td>';
			echo $this->Name;
			'</td>';
			echo '</tr>';
			
			// Jira Url
			echo '<tr>';
			echo '<td>Duration</td>';
			echo '<td>';
			echo $this->Start." -  ".$this->End;
			'</td>';
			echo '</tr>';
			
			
			// Jira Url
			echo '<tr>';
			echo '<td>Jira</td>';
			echo '<td>';
			echo $this->Jira->url;
			'</td>';
			echo '</tr>';
			
				
			// Global Calendar
			echo '<tr>';
			echo '<td>Holidays</td>';
			echo '<td>'.
			$del = "";
			$str = ""; 
			foreach($this->Holidays as $date)
			{
				$str .= $del.$date;
				$del = ",";
			}
			echo $str;
			'</td>';
			echo '</tr>';
			//// Resources 
			echo '<tr>';
			echo '<td>Resources</td>';
			$users = "";
			$del = "";
			foreach($this->Resources as $resource)
			{
				if($resource->Efficiency != 1)
					$users .= $del.$resource->Name."(".$resource->Efficiency.")";
				else
					$users .= $del.$resource->Name.
				$del = ",";
			}
			echo '<td>'.$users.'</td>';
			echo '</tr>';
			// Resources Calendar
			
			foreach($this->Resources as $resource)
			{
				$vacations = $resource->Vacations;
				$del = "";
				$str = ""; 
				foreach($vacations as $date)
				{
					$str .= $del.$date;
					$del = ",";
				}
				echo '<tr>';
				echo '<td>'.$resource->Name.'</td>';
				echo '<td>'.$str.'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		echo '<table style="font-size: 70%;" border="1">';
		echo '<col width="20">';  //ID
		echo '<col width="10">';  //Level
		echo '<col width="40">';  //External ID
		echo '<col width="20">';  //Is Parent
		echo '<col width="20">';  //Has Subtasks
		echo '<col width="20">';  //Children Count
		echo '<col width="20">';  //Parent task if
		echo '<col width="200">'; //Summary
		echo '<col width="200">'; //Query
		echo '<col width="70">'; //Jira Key
		echo '<col width="70">'; //Status
		echo '<col width="70">'; // Start
		echo '<col width="70">'; //End
		echo '<col width="100">'; //Resources
		echo '<col width="40">';  //Duration
		echo '<col width="30">';  //Timespent
		echo '<col width="60">';  //Progress
		echo '<col width="70">'; //No early date
		echo '<col width="70">'; //Dealine
		echo '<col width="50">';  //DependenciesIds
		echo '<col width="50">';  //Predecessor task ids
		echo '<col width="70">'; //Tracking Start Date for Dashboard
		echo '<col width="70">'; //Tracking end Date for Dashboard
		echo '<tr>';
			echo '<th>ID</th>';
			echo '<th>L</th>';
			echo '<th>Ext</th>';
			echo '<th>P</th>';
			echo '<th>St</th>';
			echo '<th>C</th>';
			echo '<th>PID</th>';
			echo '<th>Summary</th>';
			echo '<th>Query</th>';
			echo '<th>Jira</th>';
			echo '<th>Status</th>';
			echo '<th>Start</th>';
			echo '<th>End</th>';
			echo '<th>Resources</th>';
			echo '<th>Dur</th>';
			echo '<th>Tsp</th>';
			echo '<th>Progress</th>';
			echo '<th>No Early</th>';
			echo '<th>Deadline</th>';
			echo '<th>Succ</th>';
			echo '<th>Pred</th>';
			echo '<th>Tstart</th>';
			echo '<th>Tend</th>';
			//echo '<th>Board</th>';
		echo '</tr>';
		$tasks = $this->TaskTree;
		
		//ini_set('xdebug.var_display_max_depth', 10);
		//var_dump($tasks);
		foreach($tasks as $task)
		{
			$this->DumpTask($task);
		}
		echo '</table>';
		
		
	}
}


?>