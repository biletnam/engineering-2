<?php

function Department_Enable($Connection, $Department) {

	////	Check Department Existence
	$Department = htmlentites($Department, ENT_QUOTES, 'UTF-8');
	$DepartmentExists = Department_Exists($Connection, $Department);
	if ( !$DepartmentExists ) {
		return array('warning' => 'Sorry, that department does not exist.');
	}

	////	Enable the Department
	$Result = mysqli_query($Connection, 'UPDATE `Departments` SET `Active`=\'1\' WHERE `Department`=\''.$Department.'\';');
	return $Result;

}
