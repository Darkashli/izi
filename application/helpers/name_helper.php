<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Split lastname into lastname ([1]) and insertion ([1]).
 *
 * @param string $lastname
 * @return array Lastname and insertion
 *
 */
function splitLastname($lastname)
{
	$lastnameE = explode(' ', $lastname);
	if (count($lastnameE) == 1) {
		return array('', $lastname);
	}
	else {
		$lastnameSl = array_slice($lastnameE, 0, -1);
		$lastnameI = implode(' ', $lastnameSl);
		$lastnameEn = end($lastnameE);
		return array($lastnameI, $lastnameEn);
	}
}
