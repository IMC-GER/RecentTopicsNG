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
	'RTNG_LANG_EXT_VER' 			=> '3.0.0',
	'RTNG_LANG_AUTHOR' 				=> 'IMC-GER / LukeWCS',

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
	'RTNG_ALL_TOPICS_EXP'			=> 'This function overrides the “Number of pages...” options and displays all pages, regardless of how many pages were set in the options.',
	'RTNG_MIN_TOPIC_LEVEL'			=> 'Minimum topic type level',
	'RTNG_MIN_TOPIC_LEVEL_EXP'		=> 'Determines the minimum level of the topic-type to display. It will only display topics of the set level, and higher.',
	'RTNG_ANTI_TOPICS'				=> 'Excluded topic ID’s',
	'RTNG_ANTI_TOPICS_EXP'			=> 'Enter the topic IDs, comma separated (e.g. 7,9), otherwise 0 to show all topics. (as in the URL <code>viewtopic.php?t=12345</code>).',
	'RTNG_PARENTS'					=> 'Display parent forums',
	'RTNG_PARENTS_EXP'				=> 'Display parent forums inside the topic row of recent topics.',
	'RTNG_SIMPLE_LINK'				=> 'Link to the simplified page',
	'RTNG_SIMPLE_TOPICS_QTY'		=> 'Number of topics to display per page in the simplified display',
	'RTNG_SIMPLE_TOPICS_QTY_EXP'	=> 'This allows you to specify the maximum number of topics to be displayed per page of the list for the simplified display.',
	'RTNG_SIMPLE_PAGE_QTY'			=> 'Number of pages in the simplified display',
	'RTNG_SIMPLE_PAGE_QTY_EXP'		=> 'This allows you to specify the maximum number of list pages to be displayed for the simplified display.',

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
	'RTNG_UNREAD_ONLY_EXP'			=> 'Enable to only display unread topics (whether they are “recent” or not). This function uses the same settings (excluding forums/topics etc.) as normal mode.<br>Note: this only works for logged-in users; guests will get the normal list.',
	'RTNG_DISP_LAST_POST'			=> 'Topic title link',
	'RTNG_DISP_LAST_POST_EXP'		=> 'This option allows you to specify whether clicking on the topic title should lead to the first or last post in the topic. The corresponding post title is also used as the topic title.',
	'RTNG_FIRST_POST'				=> 'To the first post',
	'RTNG_LAST_POST'				=> 'To the last post',
	'RTNG_DISP_FIRST_UNRD_POST'		=> 'Topic title link to unread posts',
	'RTNG_DISP_FIRST_UNRD_POST_EXP'	=> 'If you activate this option, clicking on the topic title leads to the first unread post in the topic, if there is one. The corresponding post title is also used as the topic title. If there are no unread posts in the topic, or if this option is deactivated, the setting for “Topic title link” applies.',
	'RTNG_INDEX_TOPICS_QTY'			=> 'Number of topics to display per page in the forum overview',
	'RTNG_INDEX_TOPICS_QTY_EXP'		=> 'This allows you to specify the maximum number of topics to be displayed per page of the list in the forum overview.',
	'RTNG_INDEX_PAGE_QTY'			=> 'Number of pages in the forum overview',
	'RTNG_INDEX_PAGE_QTY_EXP'		=> 'This allows you to specify the maximum number of list pages to be displayed in the forum overview.',
	'RTNG_SEPARATE_TOPICS_QTY'		=> 'Number of topics to be displayed per page when displayed separately',
	'RTNG_SEPARATE_TOPICS_QTY_EXP'	=> 'Here you can specify the maximum number of topics to be displayed per page of the list for the separate display.',
	'RTNG_SEPARATE_PAGE_QTY'		=> 'Number of pages for separate display',
	'RTNG_SEPARATE_PAGE_QTY_EXP'	=> 'Here you can specify the maximum number of list pages to be displayed for the separate display.',
	'RTNG_RESET_DEFAULT'			=> 'Overwrite user settings',
	'RTNG_RESET_DEFAULT_EXP'		=> 'When this option is enabled, the settings of all users are overwritten. Without the activation only default values for new users and guests are set.',
	'RTNG_RESET_ASK_BEFORE_EXP'		=> 'This setting will overwrite all user settings with your defaults.<br><strong>This process cannot be reversed!</strong>',
]);
