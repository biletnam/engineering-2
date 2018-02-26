<?php

function Line_Exists($Connection, $Line, $Active = false) {
	$Line = htmlentities($Line, ENT_QUOTES, 'UTF-8');
	$SQL = 'SELECT * FROM `Lines` WHERE `Line`=\''.$Line.'\'';
	if ( $Active ) {
		$SQL .= ' AND `Active`=\'1\'';
	}
	$SQL .= ';';
	$LineExists = mysqli_fetch_count($Connection, $SQL);
	return $LineExists;
}
