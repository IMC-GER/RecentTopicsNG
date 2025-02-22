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
	'ACL_CAT_RTNG' 						=> 'Recent Topics NG',
	'ACL_U_RTNG_VIEW'					=> 'Can view Recent Topics NG.',
	'ACL_U_RTNG_ENABLE'					=> 'Can enable or disable Recent Topics NG.',
	'ACL_U_RTNG_LOCATION'				=> 'Can select display location of Recent Topics NG blocks.',
	'ACL_U_RTNG_SORT_START_TIME'		=> 'Can change topic sort order.',
	'ACL_U_RTNG_UNREAD_ONLY'			=> 'Can change setting to only display unread topics.',
	'ACL_U_RTNG_DISP_LAST_POST'			=> 'Can select the last post as display in the topic title.',
	'ACL_U_RTNG_DISP_FIRST_UNRD_POST'	=> 'Can select the first unread post as display in the topic title.',
	'ACL_U_RTNG_INDEX_TOPICS_QTY'		=> 'Can change the number of recent topics per page in the forum overview.',
	'ACL_U_RTNG_INDEX_PAGE_QTY'			=> 'Can change the number of pages displayed in the forum overview.',
	'ACL_U_RTNG_SEPARATE_TOPICS_QTY'	=> 'Can change the number of recent topics per page on the separate page.',
	'ACL_U_RTNG_SEPARATE_PAGE_QTY'		=> 'Can change the number of topics displayed on the separate page.',
]);
