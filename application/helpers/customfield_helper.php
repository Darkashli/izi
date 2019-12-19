<?php

function getCustomFieldName($key)
{
	if (isset(CUSTOMFIELDNAMES[$key])) {
		return CUSTOMFIELDNAMES[$key];
	}
	else {
		return $key;
	}
}

function formatCustomFieldValue($key, $value)
{
	if (
		$key === 'izi_hiring_from' ||
		$key === 'izi_hiring_to' ||
		$key === 'izi_pickup_date' ||
		$key === 'izi_return_date' ||
		$key === 'izi_deliver_date' ||
		$key === 'izi_pickup_date_fetch'
	) {
		return date('d-m-Y', strtotime($value));
	}
	
	if (isset(CUSTOMFIELDKEYVALUES[$value])) {
		return CUSTOMFIELDKEYVALUES[$value];
	}
	
	return $value;
}
