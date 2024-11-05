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
	'RTNG_LANG_EXT_VER' 			=> '3.0.0-a2',
	'RTNG_LANG_AUTHOR' 				=> 'IMC-GER',

	//forum acp
	'RTNG_FORUMS'					=> 'Display on “Recent Topics NG”',
	'RTNG_FORUMS_EXPLAIN'			=> 'Enable this checkbox to display topics from this forum in the list of recent topics.',

	//acp title
	'RTNG_NAME'						=> 'Recent Topics NG', // Please do not translate the name of the extension
	'RTNG_DESIG'					=> 'Recent Topics',
	'RTNG_EXPLAIN'					=> 'On this page you can change the settings specific for the Recent Topics extension.<br><br>Specific forums can be included or excluded by editing the respective forums in your ACP.<br>Also be sure to check your user permissions, which allow users to change some of the settings found below for themselves.',
	'RTNG_CONFIG'					=> 'Configuration',

	//global settings
	'RTNG_GLOBAL_SETTINGS'			=> 'Global Settings',
	'RTNG_INDEX_DISPLAY_EXP'		=> 'Display on Index page',
	'RTNG_ALL_TOPICS'				=> 'Show all recent topic pages',
	'RTNG_ALL_TOPICS_EXP'			=> 'This function overrides the “Maximum number of page” option and displays all pages, no matter how many pages were set for the option.',
	'RTNG_MIN_TOPIC_LEVEL'			=> 'Minimum topic type level',
	'RTNG_MIN_TOPIC_LEVEL_EXP'		=> 'Determines the minimum level of the topic-type to display. It will only display topics of the set level, and higher.',
	'RTNG_ANTI_TOPICS'				=> 'Excluded topic ID’s',
	'RTNG_ANTI_TOPICS_EXP'			=> 'Enter the topic IDs, comma separated (e.g. 7,9), otherwise 0 to show all topics. (as in the URL <code>viewtopic.php?t=12345</code>).',
	'RTNG_PARENTS'					=> 'Display parent forums',
	'RTNG_PARENTS_EXP'				=> 'Display parent forums inside the topic row of recent topics.',
	'RTNG_SIMPLE_TOPICS_QTY'		=> 'Maximale Anzahl anzuzeigender Themen pro Seite in der vereinfachte Anzeige',
	'RTNG_SIMPLE_TOPICS_QTY_EXP'	=> '',
	'RTNG_SIMPLE_PAGE_QTY'			=> 'Maximale Anzahl der Seiten in der vereinfachte Anzeige',
	'RTNG_SIMPLE_PAGE_QTY_EXP'		=> 'Die vereinfachte Seite kann mit folgenden Link aufgerufen werden.',

	//User Overridable settings. these apply for anon users and can be overridden by UCP
	'RTNG_OVERRIDABLE'				=> 'UCP overridable Settings',
	'RTNG_ENABLE'					=> 'Display recent topics',
	'RTNG_LOCATION'					=> 'Display location',
	'RTNG_LOCATION_EXP'				=> 'Select location to display recent topics.',
	'RTNG_TOP'						=> 'Show on top',
	'RTNG_BOTTOM'					=> 'Show on bottom',
	'RTNG_SIDE'						=> 'Show on side',
	'RTNG_SEPARATE'					=> 'Only separate page',
	'RTNG_SORT_START_TIME'			=> 'Sort by topic start time',
	'RTNG_SORT_START_TIME_EXP'		=> 'Enable to sort recent topics by the starting time of the topic, instead of the last post time.',
	'RTNG_UNREAD_ONLY'				=> 'Only display unread topics',
	'RTNG_UNREAD_ONLY_EXP'			=> 'Enable to only display unread topics (whether they are “recent” or not). This function uses the same settings (excluding forums/topics etc.) as normal mode. Note: this only works for logged-in users; guests will get the normal list.',
	'RTNG_DISP_LAST_POST'			=> 'Display the last post as topic title',
	'RTNG_DISP_LAST_POST_EXP'		=> '',
	'RTNG_DISP_FIRST_UNRD_POST'		=> 'Display the first unread post as topic title',
	'RTNG_DISP_FIRST_UNRD_POST_EXP'	=> '',
	'RTNG_INDEX_TOPICS_QTY'			=> 'Maximale Anzahl anzuzeigender Themen pro Seite auf der Startseite',
	'RTNG_INDEX_TOPICS_QTY_EXP'		=> '',
	'RTNG_INDEX_PAGE_QTY'			=> 'Maximale Anzahl der Seiten auf der Startseite',
	'RTNG_INDEX_PAGE_QTY_EXP'		=> '',
	'RTNG_SEPARATE_TOPICS_QTY'		=> 'Maximale Anzahl anzuzeigender Themen pro Seite bei separate Anzeige',
	'RTNG_SEPARATE_TOPICS_QTY_EXP'	=> '',
	'RTNG_SEPARATE_PAGE_QTY'		=> 'Maximale Anzahl der Seiten bei separate Anzeige',
	'RTNG_SEPARATE_PAGE_QTY_EXP'	=> '',
	'RTNG_RESET_DEFAULT'			=> 'Overwrite user settings',
	'RTNG_RESET_DEFAULT_EXP'		=> 'When this option is enabled, the settings of all users are overwritten. Without the activation only default values for new users and guests are set.',
	'RTNG_RESET_ASK_BEFORE_EXP'		=> 'This setting will overwrite all user settings with your defaults.<br><strong>This process cannot be reversed!</strong>',
]);
