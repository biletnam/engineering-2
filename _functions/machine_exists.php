<?php

function Machine_Exists($Connection, $AssetTag, $Active = false) {
	$AssetTag = htmlentities($AssetTag, ENT_QUOTES, 'UTF-8');
	$SQL = 'SELECT * FROM `Machines` WHERE `AssetTag`=\''.$AssetTag.'\'';
	if ( $Active ) {
		$SQL .= ' AND `Active`=\'1\'';
	}
	$SQL .= ';';
	$MachineExists = mysqli_fetch_count($Connection, $SQL);
	return $MachineExists;
}
