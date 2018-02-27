<?php

function Machine_Disable($Connection, $AssetTag) {

	////	Check Machine Existence
	$AssetTag = htmlentities($AssetTag, ENT_QUOTES, 'UTF-8');
	$MachineExists = Machine_Exists($Connection, $AssetTag, true);
	if ( !$MachineExists ) {
		return array('warning' => 'Sorry, that Machine does not exist. I guess that means it\'s sort of disabled already?');
	}

	////	Disable the Machine
	$Result = mysqli_query($Connection, 'UPDATE `Machines` SET `Active`=\'0\' WHERE `AssetTag`=\''.$AssetTag.'\';');
	return $Result;

}
