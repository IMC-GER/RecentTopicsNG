<?php
/**
 *
 * Recent Topics. An extension for the phpBB Forum Software package.
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
	// Language pack author
	'RTNG_LANG_DESC'				=> 'English',
	'RTNG_LANG_EXT_VER' 			=> '2.2.15-pl21',
	'RTNG_LANG_AUTHOR' 				=> 'RTNG',

	//forum acp
	'RT_FORUMS'						=> 'Display on “recent topics”',
	'RT_FORUMS_EXPLAIN'				=> 'Enable to display topics in this forum in the “recent topics” extension.',

	//acp title
	'RTNG_TITLE'					=> 'Recent Topics',
	'RECENT_TOPICS'					=> 'Recent Topics',
	'RECENT_TOPICS_EXPLAIN'			=> 'On this page you can change the settings specific for the Recent Topics extension.<br><br>Specific forums can be included or excluded by editing the respective forums in your ACP.<br>Also be sure to check your user permissions, which allow users to change some of the settings found below for themselves.',
	'RT_CONFIG'						=> 'Configuration',

	//global settings
	'RT_GLOBAL_SETTINGS'			=> 'Global Settings',
	'RT_DISPLAY_INDEX'				=> 'Display on Index page',
	'RT_NUMBER'						=> 'Number of Recent topics to show',
	'RT_NUMBER_EXP'					=> 'Maximum number of topics to display per page.',
	'RT_PAGE_NUMBER'				=> 'Show all recent topic pages',
	'RT_PAGE_NUMBER_EXP'			=> 'This function overrides the “Maximum number of page” option and displays all pages, no matter how many pages were set for the option.',
	'RT_PAGE_NUMBERMAX'				=> 'Maximum number of pages',
	'RT_PAGE_NUMBERMAX_EXP'			=> 'Set the page maximum to display in the recent topics pagination unless overridden.',
	'RT_MIN_TOPIC_LEVEL'			=> 'Minimum topic type level',
	'RT_MIN_TOPIC_LEVEL_EXP'		=> 'Determines the minimum level of the topic-type to display. It will only display topics of the set level, and higher.',
	'RT_ANTI_TOPICS'				=> 'Excluded topic ID’s',
	'RT_ANTI_TOPICS_EXP'			=> 'Enter the topic IDs, comma separated (e.g. 7,9), otherwise 0 to show all topics. (as in the URL <code>viewtopic.php?t=12345</code>).',
	'RT_PARENTS'					=> 'Display parent forums',
	'RT_PARENTS_EXP'				=> 'Display parent forums inside the topic row of recent topics.',

	//User Overridable settings. these apply for anon users and can be overridden by UCP
	'RT_OVERRIDABLE'				=> 'UCP overridable Settings',
	'RT_LOCATION'					=> 'Display location',
	'RT_LOCATION_EXP'				=> 'Select location to display recent topics.',
	'RT_TOP'						=> 'Show on top',
	'RT_BOTTOM'						=> 'Show on bottom',
	'RT_SIDE'						=> 'Show on side',
	'RT_SEPARAT'					=> 'Only separate page',
	'RT_SORT_START_TIME'			=> 'Sort by topic start time',
	'RT_SORT_START_TIME_EXP'		=> 'Enable to sort recent topics by the starting time of the topic, instead of the last post time.',
	'RT_UNREAD_ONLY'				=> 'Only display unread topics',
	'RT_UNREAD_ONLY_EXP'			=> 'Enable to only display unread topics (whether they are “recent” or not). This function uses the same settings (excluding forums/topics etc.) as normal mode. Note: this only works for logged-in users; guests will get the normal list.',
	'RT_RESET_DEFAULT'				=> 'Overwrite user settings',
	'RT_RESET_DEFAULT_EXP'			=> 'When this option is enabled, the settings of all users are overwritten. Without the activation only default values for new users and guests are set.',
	'RT_RESET_ASK_BEFORE_EXP'		=> 'This setting will overwrite all user settings with your defaults.<br><strong>This process cannot be reversed!</strong>',
]);
