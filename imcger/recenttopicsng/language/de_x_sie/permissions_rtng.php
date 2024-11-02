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
	'ACL_CAT_RTNG' 						=> 'Aktuelle Themen NG',
	'ACL_U_RTNG_VIEW'					=> 'Kann „Aktuelle Themen NG“ sehen.',
	'ACL_U_RTNG_ENABLE'					=> 'Kann „Aktuelle Themen NG“ aktivieren / deaktivieren.',
	'ACL_U_RTNG_LOCATION'				=> 'Kann den Anzeigeort des Blocks „Aktuelle Themen NG“ ändern.',
	'ACL_U_RTNG_SORT_START_TIME'		=> 'Kann Sortierung des Blocks „Aktuelle Themen NG“ ändern.',
	'ACL_U_RTNG_UNREAD_ONLY'			=> 'Kann „Aktuelle Themen NG“-Modus auf „nur ungelesene“ ändern.',
	'ACL_U_RTNG_DISP_LAST_POST'			=> 'Kann .',
	'ACL_U_RTNG_DISP_FIRST_UNRD_POST'	=> 'Kann .',
	'ACL_U_RTNG_INDEX_TOPICS_NUMBER'	=> 'Kann Anzahl der aktuellen Themen pro Seite in der Foren-Übersicht ändern.',
	'ACL_U_RTNG_INDEX_PAGE_NUMBER'		=> 'Kann Anzahl der angezeigten Seite in der Foren-Übersicht ändern.',
	'ACL_U_RTNG_SEPARATE_TOPICS_NUMBER'	=> 'Kann Anzahl der aktuellen pro Seite auf der separaten Seite ändern.',
	'ACL_U_RTNG_SEPARATE_PAGE_NUMBER'	=> 'Kann Anzahl der angezeigten Seite auf der separaten Seite ändern.',
]);
