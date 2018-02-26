<?php

function Line_Disable($Connection, $Line) {

	////	Check Line Existence
	$Line = htmlentities($Line, ENT_QUOTES, 'UTF-8');
	$LineExists = Line_Exists($Connection, $Line, true);
	if ( !$LineExists ) {
		return array('warning' => 'Sorry, that Line does not exist. I guess that means it\'s sort of disabled already?');
	}

	////	Disable the Line
	$Result = mysqli_query($Connection, 'UPDATE `Lines` SET `Active`=\'0\' WHERE `Line`=\''.$Line.'\';');
	return $Result;

}
