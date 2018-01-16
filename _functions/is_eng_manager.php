<?php

// isEngManager($Sitewide['Authenticated'])
function isEngManager($Authenticated) {
	if (
		!empty($Authenticated) &&
		(
			(
				$Authenticated['Department'] == 'Engineering' &&
				$Authenticated['Role'] == 'Manager'
			) ||
			$Authenticated['Role'] == 'Admin'
		)
	) return true;
	return false;
}

