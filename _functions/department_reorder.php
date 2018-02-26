<?php

function Department_Reorder($Connection, $Department, $Order) {

	////	Check Department Existence
	$Department = htmlentites($Department, ENT_QUOTES, 'UTF-8');
	$Order      = htmlentites($Order,      ENT_QUOTES, 'UTF-8');
	$DepartmentExists = Department_Exists($Connection, $Department);
	if ( !$DepartmentExists ) {
		return array('warning' => 'Sorry, that department does not exist.');
	}

	////	Enable the Department
	$Result = mysqli_query($Connection, 'UPDATE `Departments` SET `Order`=\''.$Order.'\' WHERE `Department`=\''.$Department.'\';');
	return $Result;

}
