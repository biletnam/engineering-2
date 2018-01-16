<?php

require_once __DIR__.'/../_puff/sitewide.php';

$Department = htmlentities($_GET['department'], ENT_QUOTES, 'UTF-8');
$Line = htmlentities($_GET['line'], ENT_QUOTES, 'UTF-8');
$SQL = 'SELECT * FROM `Machines` WHERE `Active`=\'1\' AND `Department`=\''.$Department.'\' AND `Line`=\''.$Line.'\' ORDER BY `Order` ASC, `Department` ASC, `Line` ASC, `Machine` ASC, `AssetTag` ASC;';
$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
while ( $Machine = mysqli_fetch_assoc($Result) ) {
	$Data[$Machine['AssetTag']] = $Machine['Machine'];
}
if ( $Line == 'Other' ) {
	$Data['Air'] = 'Air';
	$Data['Building'] = 'Building';
	$Data['Drains'] = 'Drains';
	$Data['Electrical'] = 'Electrical';
	$Data['Lighting'] = 'Lighting';
	$Data['Water'] = 'Water';
}
$Data['Other'] = 'Other';

echo json_encode($Data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
