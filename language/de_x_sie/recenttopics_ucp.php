<?php
/**
 *
 * Recent Topics NG. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, IMC, https://github.com/IMC-GER / LukeWCS, https://github.com/LukeWCS
 * @copyright (c) 2017, Sajaki, https://www.avathar.be
 * @copyright (c) 2015, PayBas
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ‚ ‘ ’ « » “ ” … „ “
//
$lang = array_merge($lang, [
	'RTNG_ENABLE'				=> '„Aktuelle Themen“ anzeigen',
	'RTNG_TOP'					=> 'Ansicht oben',
	'RTNG_BOTTOM'				=> 'Ansicht unten',
	'RTNG_SIDE'					=> 'Ansicht auf der Seite',
	'RTNG_SEPARATE'				=> 'Nur serarate Seite',
	'RTNG_LOCATION'				=> 'Anzeigeort',
	'RTNG_LOCATION_EXP'			=> 'Wählen Sie den Anzeigeort des Blocks „Aktuelle Themen“ auf der Forenseite.',
	'RTNG_NUMBER'				=> 'Anzahl aktuelle Themen',
	'RTNG_NUMBER_EXP'			=> 'Maximale Anzahl Themen pro Seite',
	'RTNG_SORT_START_TIME'		=> 'Nach Themen Startzeit sortieren',
	'RTNG_SORT_START_TIME_EXP'	=> 'Wenn diese Option aktiviert ist, werden die Themen nach dem Datum des ersten Beitrags anstelle des letzten Beitrags sortiert.',
	'RTNG_UNREAD_ONLY'			=> 'Nur ungelesene Themen anzeigen',
]);
