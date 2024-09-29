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
	//forum acp
	'RT_FORUMS'						=> 'In „Aktuelle Themen“ anzeigen',
	'RT_FORUMS_EXPLAIN'				=> 'Aktiviere dieses Kontrollkästchen, um Themen in diesem Forum in der Erweiterung ”Aktuelle Themen” anzuzeigen.',

	//acp title
	'RECENT_TOPICS'					=> 'Aktuelle Themen',
	'RECENT_TOPICS_EXPLAIN'			=> 'Auf dieser Seite können Sie die Einstellungen der aktuellen Themenerweiterung anpassen.<br><br>Spezifische Foren können durch ändern der Einstellungen der jeweiligen Foren eingeschlossen oder ausgeschlossen werden.<br>Überprüfen Sie doch auch die Benutzerberechtigungen, so dass die Benutzer sich einige Parameter ändern können, die Vorrang haben vor den Einstellungen des Admin-Panels.',
	'RT_CONFIG'						=> 'Einstellungen',

	//allgemeine Einstellungen
	'RT_GLOBAL_SETTINGS'			=> 'Globale Einstellungen',
	'RT_DISPLAY_INDEX'				=> 'Anzeigen auf der Index-Seite.',
	'RT_NUMBER'						=> 'Anzahl Aktuelle Themen',
	'RT_NUMBER_EXP'					=> 'Maximale Anzahl Themen pro Seite.',
	'RT_PAGE_NUMBER'				=> 'Alle Seiten anzeigen',
	'RT_PAGE_NUMBER_EXP'			=> 'Diese Funktion überschreibt die Option „Maximale Seitenanzahl“ und zeigt alle Seiten an, egal wie viele Seiten bei der Option eingestellt wurden.',
	'RT_PAGE_NUMBERMAX'				=> 'Maximale Seitenanzahl',
	'RT_PAGE_NUMBERMAX_EXP'			=> 'Legt die maximale Anzahl der Seiten fest.',
	'RT_MIN_TOPIC_LEVEL'			=> 'Minimaler Thementyp',
	'RT_MIN_TOPIC_LEVEL_EXP'		=> 'Definiert das Minimum des anzuzeigenden Thementyps. Wenn Sie einen Thementyp angeben, werden nur Themen dieses oder eines höheren Typs angezeigt.',
	'RT_ANTI_TOPICS'				=> 'Ausgeschlossene Themen',
	'RT_ANTI_TOPICS_EXP'			=> 'Geben Sie die Themen-IDs ein, kommagetrennt (z. B. 7,9), andernfalls 0 um alle Themen anzuzeigen. (wie in der URL <code>viewtopic.php?t=12345</code>).',
	'RT_PARENTS'					=> 'Übergeordnete Foren anzeigen',
	'RT_PARENTS_EXP'				=> 'Übergeordnete Foren in der Liste der aktuellen Themen anzeigen.',

	//Benutzereinstellungen
	'RT_OVERRIDABLE'				=> 'Einstellungen, für die das Benutzerfeld Priorität hat',
	'RT_LOCATION'					=> 'Anzeigeort',
	'RT_LOCATION_EXP'				=> 'Wählen Sie den Anzeigeort der aktuellen Themen.',
	'RT_TOP'						=> 'Ansicht oben',
	'RT_BOTTOM'						=> 'Ansicht unten',
	'RT_SIDE'						=> 'Ansicht auf die Seite',
	'RT_SEPARAT'					=> 'Nur separate Seite',
	'RT_SORT_START_TIME'			=> 'Nach Themen Startzeit sortieren',
	'RT_SORT_START_TIME_EXP'		=> 'Wenn diese Option aktiviert ist, werden die Themen nach dem Datum des ersten Beitrags anstelle des letzten Beitrags sortiert.',
	'RT_UNREAD_ONLY'				=> 'Nur ungelesene Themen anzeigen',
	'RT_UNREAD_ONLY_EXP'			=> 'Diese Option zeigt nur ungelesene Themen an (egal ob diese aktuell sind oder nicht). Diese Funktion nutzt die gleichen Einstellungen (Ausgeschlossene Foren / Themen, etc.) wie die normale Version. Hinweis: diese Funktion steht nur angemeldeten Benutzern zur Verfügung; Gäste sehen die normale „Aktuelle Themen“ Liste.',
	'RT_RESET_DEFAULT'				=> 'Benutzereinstellungen überschreiben',
	'RT_RESET_DEFAULT_EXP'			=> 'Bei der Aktivierung dieser Option werden die Einstellungen aller Benutzer überschrieben. Ohne die Aktivierung werden nur Standardwerte für neue Benutzer und Gäste gesetzt.',
	'RT_RESET_ASK_BEFORE_EXP'		=> 'Diese Einstellung überschreibt alle Benutzereinstellungen mit Ihren Standardwerten.<br><strong>Dieser Vorgang kann nicht rückgängig gemacht werden!</strong>',
]);
