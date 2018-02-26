<?php

function Department_Disable($Connection, $Department) {

	////	Check Department Existence
	$Department = htmlentities($Department, ENT_QUOTES, 'UTF-8');
	$DepartmentExists = Department_Exists($Connection, $Department, true);
	if ( !$DepartmentExists ) {
		return array('warning' => 'Sorry, that Department does not exist. I guess that means it\'s sort of disabled already?');
	}

	////	Disable the Department
	$Result = mysqli_query($Connection, 'UPDATE `Departments` SET `Active`=\'0\' WHERE `Department`=\''.$Department.'\';');
	return $Result;

}
