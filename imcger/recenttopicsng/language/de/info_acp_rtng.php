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
	// Language pack author
	'RTNG_LANG_DESC'				=> 'Deutsch (Du)',
	'RTNG_LANG_EXT_VER' 			=> '1.0.0',
	'RTNG_LANG_AUTHOR' 				=> 'IMC-GER / LukeWCS',

	//forum acp
	'RTNG_FORUMS'					=> 'In „Recent Topics NG“ anzeigen',
	'RTNG_FORUMS_EXPLAIN'			=> 'Aktiviere dieses Kontrollkästchen, um Themen dieses Forum in den Aktuelle Themen anzuzeigen.',

	//acp title
	'RTNG_NAME'						=> 'Recent Topics NG', // Please do not translate the name of the extension
	'RTNG_DESIG'					=> 'Aktuelle Themen',
	'RTNG_EXPLAIN'					=> 'Auf dieser Seite kannst du die Einstellungen der Erweiterung „Recent Topics NG“ anpassen.<br><br>Spezifische Foren können eingeschlossen oder ausgeschlossen werden.<br>Überprüfe auch die Benutzerberechtigungen, welche Benutzern erlauben, einige der Parameter für sich selbst zu verändern. Diese haben dann Vorrang vor den Einstellungen des Admin-Panels.',
	'RTNG_CONFIG'					=> 'Einstellungen',

	//allgemeine Einstellungen
	'RTNG_GLOBAL_SETTINGS'			=> 'Globale Einstellungen',
	'RTNG_INDEX_DISPLAY_EXP'		=> 'Anzeigen auf der Index-Seite.',
	'RTNG_ALL_TOPICS'				=> 'Alle Seiten anzeigen',
	'RTNG_ALL_TOPICS_EXP'			=> 'Diese Funktion überschreibt die Optionen „Anzahl der Seiten...“ und zeigt alle Seiten an, egal wie viele Seiten bei den Optionen eingestellt wurden.',
	'RTNG_MIN_TOPIC_LEVEL'			=> 'Minimaler Thementyp',
	'RTNG_MIN_TOPIC_LEVEL_EXP'		=> 'Definiert das Minimum des anzuzeigenden Thementyps. Wenn du einen Thementyp angibst, werden nur Themen dieses oder eines höheren Typs angezeigt.',
	'RTNG_ANTI_TOPICS'				=> 'Ausgeschlossene Themen',
	'RTNG_ANTI_TOPICS_EXP'			=> 'Gebe die Themen-IDs ein, kommagetrennt (z. B. 7,9), andernfalls 0, um alle Themen anzuzeigen. (wie in der URL <code>viewtopic.php?t=12345</code>).',
	'RTNG_PARENTS'					=> 'Übergeordnete Foren anzeigen',
	'RTNG_PARENTS_EXP'				=> 'Übergeordnete Foren in der Liste der aktuellen Themen anzeigen.',
	'RTNG_SIMPLE_LINK'				=> 'Link zur vereinfachten Seite',
	'RTNG_SIMPLE_TOPICS_QTY'		=> 'Anzahl anzuzeigender Themen pro Seite in der vereinfachten Anzeige',
	'RTNG_SIMPLE_TOPICS_QTY_EXP'	=> 'Damit kannst du für die vereinfachte Anzeige festlegen, wie viele Themen maximal pro Seite der Liste angezeigt werden sollen.',
	'RTNG_SIMPLE_PAGE_QTY'			=> 'Anzahl der Seiten in der vereinfachten Anzeige',
	'RTNG_SIMPLE_PAGE_QTY_EXP'		=> 'Damit kannst du für die vereinfachte Anzeige festlegen, wie viele Listen-Seiten maximal angezeigt werden sollen.',

	//Benutzereinstellungen
	'RTNG_OVERRIDABLE'				=> 'Einstellungen, die im Benutzerkontrollzentrum geändert werden können',
	'RTNG_ENABLE'					=> 'Aktuelle Themen anzeigen',
	'RTNG_LOCATION'					=> 'Anzeigeort',
	'RTNG_LOCATION_EXP'				=> 'Wähle den Anzeigeort der aktuellen Themen.',
	'RTNG_TOP'						=> 'Ansicht oben',
	'RTNG_BOTTOM'					=> 'Ansicht unten',
	'RTNG_SIDE'						=> 'Ansicht an der Seite',
	'RTNG_SEPARATE'					=> 'Nur separate Seite',
	'RTNG_SORT_START_TIME'			=> 'Nach Themen-Startzeit sortieren',
	'RTNG_SORT_START_TIME_EXP'		=> 'Wenn diese Option aktiviert ist, werden die Themen nach dem Themenstartzeitpunkt anstelle des letzten Beitrags sortiert.',
	'RTNG_UNREAD_ONLY'				=> 'Nur ungelesene Themen anzeigen',
	'RTNG_UNREAD_ONLY_EXP'			=> 'Diese Option zeigt nur ungelesene Themen an (egal ob diese aktuell sind oder nicht). Diese Funktion nutzt die gleichen Einstellungen (Ausgeschlossene Foren / Themen, etc.) wie die normale Version.<br>Hinweis: diese Funktion steht nur angemeldeten Benutzern zur Verfügung; Gäste sehen die normale „Aktuelle Themen“ Liste.',
	'RTNG_DISP_LAST_POST'			=> 'Link des Thementitels',
	'RTNG_DISP_LAST_POST_EXP'		=> 'Mit dieser Option kannst du festlegen, ob ein Klick auf den Thementitel zum ersten oder letzten Beitrag des Themas führen soll. Außerdem wird der entsprechende Beitragstitel als Thementitel verwendet.',
	'RTNG_FIRST_POST'				=> 'Zum ersten Beitrag',
	'RTNG_LAST_POST'				=> 'Zum letzten Beitrag',
	'RTNG_DISP_FIRST_UNRD_POST'		=> 'Link des Thementitels zu ungelesenen Beiträgen',
	'RTNG_DISP_FIRST_UNRD_POST_EXP'	=> 'Wenn du diese Option aktivierst, führt ein Klick auf den Thementitel zum ersten ungelesenen Beitrag des Themas, sofern ein solcher vorhanden ist. Außerdem wird der entsprechende Beitragstitel als Thementitel verwendet. Wenn es im Thema keine ungelesenen Beiträge gibt, oder diese Option deaktiviert ist, gilt die Einstellung von „Link des Thementitels“.',
	'RTNG_INDEX_TOPICS_QTY'			=> 'Anzahl anzuzeigender Themen pro Seite in der Foren-Übersicht',
	'RTNG_INDEX_TOPICS_QTY_EXP'		=> 'Damit kannst du für die Foren-Übersicht festlegen, wie viele Themen maximal pro Seite der Liste angezeigt werden sollen.',
	'RTNG_INDEX_PAGE_QTY'			=> 'Anzahl der Seiten in der Foren-Übersicht',
	'RTNG_INDEX_PAGE_QTY_EXP'		=> 'Damit kannst du für die Foren-Übersicht festlegen, wie viele Listen-Seiten maximal angezeigt werden sollen.',
	'RTNG_SEPARATE_TOPICS_QTY'		=> 'Anzahl anzuzeigender Themen pro Seite bei separater Anzeige',
	'RTNG_SEPARATE_TOPICS_QTY_EXP'	=> 'Hier kannst du für die separate Anzeige einstellen, wie viele Themen maximal pro Seite der Liste angezeigt werden sollen.',
	'RTNG_SEPARATE_PAGE_QTY'		=> 'Anzahl der Seiten bei separater Anzeige',
	'RTNG_SEPARATE_PAGE_QTY_EXP'	=> 'Hier kannst du für die separate Anzeige einstellen, wie viele Listen-Seiten maximal angezeigt werden sollen.',
	'RTNG_RESET_DEFAULT'			=> 'Benutzereinstellungen überschreiben',
	'RTNG_RESET_DEFAULT_EXP'		=> 'Bei der Aktivierung dieser Option werden die Einstellungen aller Benutzer überschrieben. Ohne die Aktivierung werden nur Standardwerte für neue Benutzer und Gäste gesetzt.',
	'RTNG_RESET_ASK_BEFORE_EXP'		=> 'Diese Einstellung überschreibt alle Benutzereinstellungen mit deinen Standardwerten.<br><strong>Dieser Vorgang kann nicht rückgängig gemacht werden!</strong>',
]);
