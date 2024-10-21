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

namespace paybas\recenttopics\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class acp_listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\request\request */
	protected $request;

	/**
	 * acp_listener constructor.
	 *
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\language\language	$language	language object
	 * @param \phpbb\request\request	$request
	 */
	public function __construct
	(
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		\phpbb\request\request	 $request
	)
	{
		$this->template = $template;
		$this->language = $language;
		$this->request	= $request;
	}

	/**
	 * Get subscribed events
	 *
	 * @return array
	 * @static
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.acp_manage_forums_request_data'		=> 'acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data'	=> 'acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form'		=> 'acp_manage_forums_display_form',
			'core.acp_users_prefs_modify_data'			=> 'acp_users_prefs_modify_data',
			'core.acp_users_prefs_modify_sql'			=> 'acp_users_prefs_modify_sql',
			'core.acp_users_prefs_modify_template_data' => 'acp_users_prefs_modify_template_data',
		];
	}

	/**
	 * Request forum data and operate on it
	 * Submit form (add/update)
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function acp_manage_forums_request_data($event)
	{
		$array = $event['forum_data'];
		$array['forum_recent_topics'] = $this->request->variable('forum_recent_topics', 1);
		$event['forum_data'] = $array;
	}

	/**
	 * Default settings for new forums
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function acp_manage_forums_initialise_data($event)
	{
		if ($event['action'] == 'add')
		{
			$array = $event['forum_data'];
			$array['forum_recent_topics'] = '1';
			$event['forum_data'] = $array;
		}
	}

	/**
	 * ACP forums template output
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function acp_manage_forums_display_form($event)
	{
		$array = $event['template_data'];
		$array['RECENT_TOPICS'] = $event['forum_data']['forum_recent_topics'];
		$event['template_data'] = $array;
	}

	/**
	 * Add collapsequote language file
	 * Modify users preferences data
	 *
	 * @param	object		$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function acp_users_prefs_modify_data($event)
	{
		// Add language file in ACP
		$this->language->add_lang('recenttopics_ucp', 'paybas/recenttopics');

		$event['user_row'] = array_merge($event['user_row'], [
			'user_rtng_enable'			=> $this->request->variable('user_rtng_enable', $event['user_row']['user_rt_enable']),
			'user_rtng_sort_start_time'	=> $this->request->variable('user_rtng_sort_start_time', $event['user_row']['user_rt_sort_start_time']),
			'user_rtng_unread_only'		=> $this->request->variable('user_rtng_unread_only', $event['user_row']['user_rt_unread_only']),
			'user_rtng_location'		=> $this->request->variable('user_rtng_location', $event['user_row']['user_rt_location']),
			'user_rtng_topic_number'	=> $this->request->variable('user_rtng_topic_number', $event['user_row']['user_rt_number']),
		]);
	}

	/**
	 * Modify SQL query before users preferences are updated
	 *
	 * @param	object		$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function acp_users_prefs_modify_sql($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], [
			'user_rt_enable'			=> $event['user_row']['user_rtng_enable'],
			'user_rt_sort_start_time'	=> $event['user_row']['user_rtng_sort_start_time'],
			'user_rt_unread_only'		=> $event['user_row']['user_rtng_unread_only'],
			'user_rt_location'			=> $event['user_row']['user_rtng_location'],
			'user_rt_number'			=> $event['user_row']['user_rtng_topic_number'],
		]);
	}

	/**
	 * Modify users preferences data before assigning it to the template
	 *
	 * @param	object		$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function acp_users_prefs_modify_template_data($event)
	{
		$event['user_prefs_data'] = array_merge($event['user_prefs_data'], [
			'S_RTNG_ENABLE'			 => $event['user_row']['user_rtng_enable'],
			'S_RTNG_SORT_START_TIME' => $event['user_row']['user_rtng_sort_start_time'],
			'S_RTNG_UNREAD_ONLY'	 => $event['user_row']['user_rtng_unread_only'],
			'RTNG_TOPIC_NUMBER'		 => $event['user_row']['user_rtng_topic_number'],
			'RTNG_LOCATION'			 => $event['user_row']['user_rtng_location'],
			'RTNG_LOCATION_OPTIONS'  => [
					'RTNG_TOP'		 => 'RT_TOP',
					'RTNG_BOTTOM'	 => 'RT_BOTTOM',
					'RTNG_SIDE'		 => 'RT_SIDE',
					'RTNG_SEPARATE'  => 'RT_SEPARAT',
				],
			]);
	}
}
