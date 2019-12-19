<?php

defined('BASEPATH') OR exit('No direct script access allowed');

define('HARDWAREKIND', serialize(array('1' => 'Vaste computer', '2' => 'Telefoon', '3' => 'Tablet', '4' => 'Notebook', '5' => 'Server', '6' => 'Virtuele computer', '7' => 'Randapparatuur', '8' => 'Modem/switch/router', '9' => 'Printer')));
define('SHAREPERMISSION', serialize(array('1' => 'Volledig beheer', '2' => 'Lezen en schrijven', '3' => 'Lezen')));
define('YESNODROPDOWN', serialize(array('1' => 'Ja', '0' => 'Nee')));
define('KINDEXTENTION', serialize(array('1' => 'Component', '2' => 'Module', '3' => 'Plugin', '4' => 'Widget', '5' => 'Overig', '6' => 'Pakket')));
define('TIMEPERIOD', serialize(array('7 days' => '1 week', '1 month' => '1 maand', '3 months' => '1 kwartaal', '1 year' => '1 jaar')));
define('TAXDROPDOWN', serialize(array('21' => '21%', '9' => '9%', '0' => '0%')));
define('INVOICEDROPDOWN', serialize(array('5' => 'Openstaand', '1' => 'Betaald')));
define('TICKETSTATUSDROPDOWN', serialize(array('1' => 'Geopend', '5' => 'Gesloten')));
define('CLASSDROPDOWN', 'class="form-control"');
define('CLASSDROPDOWNSUBMIT', 'class="form-control" onchange="this.form.submit()"');
define('CLASSSELECTBOOTSTRAP', 'class="selectpicker" data-style="btn btn-info btn-round" data-size="7"');
define('CLASSSELECTBOOTSTRAPSUBMIT', 'class="selectpicker" data-style="btn btn-info btn-round" data-size="7" onchange="this.form.submit()"');
define('MAKEYOURCHOISE', 'Maak je keuze');
define('QUOTATIONSTATUSSES', serialize(array(
	'created' => 'Aangemaakt',
	'sent' => 'Verstuurd & wacht op klant',
	'invoiced' => 'Gefactureerd'
)));

define('DEFAULTQUOTATIONCREATED', 'created'); // The default created status.
define('DEFAULTQUOTATIONSENT', 'sent'); // The default sent satus.
define('DEFAULTQUOTATIONINVOICED', 'invoiced'); // The default invoiced satus.

// Define custom field names and keys that are imported from the izi account plugin.
define('CUSTOMFIELDNAMES', array(
	'izi_hiring_from' => 'Huren (Gebruik maken) van',
	'izi_hiring_to' => 'Huren (gebruik maken) tot',
	'izi_buy_off' => 'Ik wil eigen risico annulering afkopen (3% van het netto totaalbedrag) & Ik wil kosten voortvloeiend uit schade ten gevolge van brand- en/of storm afkopen (92%)',
	'izi_transport' => 'Transport',
	'izi_pickup_date' => 'Datum afhalen',
	'izi_return_date' => 'Datum terugbrengen',
	'izi_location' => 'Locatie',
	'izi_obstacles' => 'Obstakels',
	'izi_access_with_truck' => 'Bereikbaar met vrachtwagen',
	'izi_deliver_date' => 'Datum afleveren',
	'izi_pickup_date_fetch' => 'Datum ophalen',
	'izi_daypart_delivery' => 'Dagdeel levering',
	'izi_daypart_pickup' => 'Dagdeel ophalen',
	'izi_vertical_transport' => 'Verdieping per',
	'izi_floor' => 'Verdieping'
));
define('CUSTOMFIELDKEYVALUES', array(
	// 'transport' => array(
		'self_pickup_and_bring' => 'Zelf halen & brengen',
		'flexible_transport' => 'Flexibel transport',
		'transport_by_appointment' => 'Transport op afspraak',
		'self_pickup' => 'Zelf ophalen',
		'deliver' => 'Laten bezorgen',
		'deliver_and_assemble' => 'Bezorgen en monteren',
	// ),
	// 'floors' => array(
		'ground_floor' => 'Begane grond',
		'floor' => 'Verdieping',
		'basement' => 'Kelder',
	// ),
	// 'yes_and_no' => array(
		'yes' => 'Ja',
		'no' => 'Nee',
	// ),
	// 'dayparts' => array(
		'whole_day' => 'Hele dag',
		'morning' => 'Ochtend',
		'afternoon' => 'Middag',
	// ),
	// 'vertical_transport' => array(
		'stairs' => 'Trap',
		'elevator' => 'Lift'
	// )
));

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
