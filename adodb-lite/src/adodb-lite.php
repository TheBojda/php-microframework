<?php
	require_once "adodb.inc.php";
		
	global $DB;
	$DB = NewADOConnection('mysql');
	$DB->Connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	$DB->Execute("SET NAMES 'utf8'");
	$DB->Execute("SET CHARACTER SET 'utf8'");
	
	function adodb_GetAll($sql, $params) {
		global $DB;
		
		$p = array();
		foreach($params as $param)
			$p[] = $DB->qstr($param,get_magic_quotes_gpc());
		$query = vsprintf($sql, $p);
		return $DB->GetAll($query);
	}
	
	function adodb_GetOne($sql, $params) {
		global $DB;
		
		$p = array();
		foreach($params as $param)
			$p[] = $DB->qstr($param,get_magic_quotes_gpc());
		$query = vsprintf($sql, $p);
		$result = $DB->GetAll($query);
		if($result)
			return $result[0];
		else
			return FALSE;
	}

?>