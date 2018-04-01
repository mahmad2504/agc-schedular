<?php

require_once('gan.php');
require_once('tj.php');

date_default_timezone_set('Asia/Karachi');

class Obj{
}

function GetToday($format)
{
	//return "2017-08-12";
	return Date($format);
}

?>