<?php

function Machine_Enable($Connection, $AssetTag) {

	////	Check Machine Existence
	$AssetTag = htmlentities($AssetTag, ENT_QUOTES, 'UTF-8');
	$MachineExists = Machine_Exists($Connection, $AssetTag);
	if ( !$MachineExists ) {
		return array('warning' => 'Sorry, that Machine does not exist.');
	}

	////	Enable the Machine
	$Result = mysqli_query($Connection, 'UPDATE `Machines` SET `Active`=\'1\' WHERE `AssetTag`=\''.$AssetTag.'\';');
	return $Result;

}
