<?php

$SQL = '
SELECT
	`table_schema` AS \'Database\',
	`data_length`,
	`index_length`,
	`data_length` + `index_length` AS \'Total Size\'
FROM
	`information_schema`.`tables`
WHERE
	`table_schema` = \'Engineering\'
GROUP BY
	`table_schema`';
$Result = mysqli_fetch_once($Sitewide['Database']['Connection'], $SQL);
echo '
<footer class="faded">
	<p>Loading '.number_format($Result['Total Size']).' bits of information took '.(microtime(true) - $Sitewide['Request']['Start']).'ms while running on PHP '.phpversion(),'</p>
</footer>';
