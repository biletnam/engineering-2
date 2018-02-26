<?php

function Department_Reorder($Connection, $Department, $Order) {

	////	Check Department Existence
	$Department = htmlentities($Department, ENT_QUOTES, 'UTF-8');
	$Order      = htmlentities($Order,      ENT_QUOTES, 'UTF-8');
	$DepartmentExists = Department_Exists($Connection, $Department);
	if ( !$DepartmentExists ) {
		return array('warning' => 'Sorry, that department does not exist.');
	}

	////	Enable the Department
	$SQL = 'UPDATE `Departments` SET `Order`=\''.$Order.'\' WHERE `Department`=\''.$Department.'\';';
	$Result = mysqli_query($Connection, $SQL);
	return $Result;

}
