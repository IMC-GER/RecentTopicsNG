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
	'RTNG_ENABLE'				=> 'Display recent topics',
	'RTNG_TOP'					=> 'Show on top',
	'RTNG_BOTTOM'				=> 'Show on bottom',
	'RTNG_SIDE'					=> 'Show on side',
	'RTNG_SEPARATE'				=> 'Only separate page',
	'RTNG_LOCATION'				=> 'Select location',
	'RTNG_LOCATION_EXP'			=> 'Select location to display recent topics.',
	'RTNG_NUMBER'				=> 'Number of Recent topics to show',
	'RTNG_NUMBER_EXP'			=> 'Maximum number of topics to display per page.',
	'RTNG_SORT_START_TIME'		=> 'Sort recent topics by topic start time',
	'RTNG_SORT_START_TIME_EXP'	=> 'Instead of sorting them by last post time.',
	'RTNG_UNREAD_ONLY'			=> 'Only display unread topics in recent topics',
]);
