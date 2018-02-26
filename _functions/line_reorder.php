<?php

function Line_Reorder($Connection, $Line, $Order) {

	////	Check Line Existence
	$Line = htmlentities($Line, ENT_QUOTES, 'UTF-8');
	$Order      = htmlentities($Order,      ENT_QUOTES, 'UTF-8');
	$LineExists = Line_Exists($Connection, $Line);
	if ( !$LineExists ) {
		return array('warning' => 'Sorry, that Line does not exist.');
	}

	////	Enable the Line
	$SQL = 'UPDATE `Lines` SET `Order`=\''.$Order.'\' WHERE `Line`=\''.$Line.'\';';
	$Result = mysqli_query($Connection, $SQL);
	return $Result;

}
