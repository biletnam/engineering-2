<?php

function Line_Enable($Connection, $Line) {

	////	Check Line Existence
	$Line = htmlentities($Line, ENT_QUOTES, 'UTF-8');
	$LineExists = Line_Exists($Connection, $Line);
	if ( !$LineExists ) {
		return array('warning' => 'Sorry, that Line does not exist.');
	}

	////	Enable the Line
	$Result = mysqli_query($Connection, 'UPDATE `Lines` SET `Active`=\'1\' WHERE `Line`=\''.$Line.'\';');
	return $Result;

}
