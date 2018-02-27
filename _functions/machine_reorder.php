<?php

function Machine_Reorder($Connection, $AssetTag, $Order) {

	////	Check Machine Existence
	$Machine = htmlentities($Machine, ENT_QUOTES, 'UTF-8');
	$Order   = htmlentities($Order,   ENT_QUOTES, 'UTF-8');
	$MachineExists = Machine_Exists($Connection, $AssetTag);
	if ( !$MachineExists ) {
		return array('warning' => 'Sorry, that Machine does not exist.');
	}

	////	Enable the Machine
	$SQL = 'UPDATE `Machines` SET `Order`=\''.$Order.'\' WHERE `AssetTag`=\''.$AssetTag.'\';';
	$Result = mysqli_query($Connection, $SQL);
	return $Result;

}
