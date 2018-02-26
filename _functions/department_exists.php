<?php

function Department_Exists($Connection, $Department, $Active = false) {
	$Department = htmlentites($Department, ENT_QUOTES, 'UTF-8');
	$SQL = 'SELECT * FROM `Departments` WHERE `Department`=\''.$Department.'\'';
	if ( $Active ) {
		$SQL .= ' AND `Active`=\'1\'';
	}
	$SQL .= ';';
	$DepartmentExists = mysqli_fetch_count($Connection, $SQL);
	return $DepartmentExists;
}
