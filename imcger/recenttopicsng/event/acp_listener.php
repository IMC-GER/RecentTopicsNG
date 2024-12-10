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

namespace imcger\recenttopicsng\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class acp_listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\request\request */
	protected $request;

	protected $ctrl_common;

	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\template\template $template,
		\phpbb\request\request $request,
		\imcger\recenttopicsng\controller\controller_common $controller_common
	)
	{
		$this->template 	= $template;
		$this->request		= $request;
		$this->ctrl_common	= $controller_common;
	}

	/**
	 * Get subscribed events
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
	 */
	public function acp_manage_forums_request_data($event)
	{
		$array = $event['forum_data'];
		$array['forum_rtng_disp'] = $this->request->variable('forum_rtng_disp', true);
		$event['forum_data'] = $array;
	}

	/**
	 * Default settings for new forums
	 */
	public function acp_manage_forums_initialise_data($event)
	{
		if ($event['action'] == 'add')
		{
			$array = $event['forum_data'];
			$array['forum_rtng_disp'] = true;
			$event['forum_data'] = $array;
		}
	}

	/**
	 * ACP forums template output
	 */
	public function acp_manage_forums_display_form($event)
	{
		$array = $event['template_data'];
		$array['RTNG_DISP_FORUM'] = $event['forum_data']['forum_rtng_disp'];
		$event['template_data'] = $array;
	}

	/**
	 * Modify users preferences data
	 */
	public function acp_users_prefs_modify_data($event)
	{
		$event['user_row'] = array_merge($event['user_row'], [
			'user_rtng_enable'				 => $this->request->variable('user_rtng_enable', $event['user_row']['user_rtng_enable']),
			'user_rtng_sort_start_time'		 => $this->request->variable('user_rtng_sort_start_time', $event['user_row']['user_rtng_sort_start_time']),
			'user_rtng_unread_only'			 => $this->request->variable('user_rtng_unread_only', $event['user_row']['user_rtng_unread_only']),
			'user_rtng_location'			 => $this->request->variable('user_rtng_location', $event['user_row']['user_rtng_location']),
			'user_rtng_disp_last_post'		 => $this->request->variable('user_rtng_disp_last_post', $event['user_row']['user_rtng_disp_last_post']),
			'user_rtng_disp_first_unrd_post' => $this->request->variable('user_rtng_disp_first_unrd_post', $event['user_row']['user_rtng_disp_first_unrd_post']),
			'user_rtng_index_topics_qty'	 => $this->request->variable('user_rtng_index_topics_qty', $event['user_row']['user_rtng_index_topics_qty']),
			'user_rtng_index_page_qty'		 => $this->request->variable('user_rtng_index_page_qty', $event['user_row']['user_rtng_index_page_qty']),
			'user_rtng_separate_topics_qty'	 => $this->request->variable('user_rtng_separate_topics_qty', $event['user_row']['user_rtng_separate_topics_qty']),
			'user_rtng_separate_page_qty'	 => $this->request->variable('user_rtng_separate_page_qty', $event['user_row']['user_rtng_separate_page_qty']),
		]);
	}

	/**
	 * Modify SQL query before users preferences are updated
	 */
	public function acp_users_prefs_modify_sql($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], [
			'user_rtng_enable'				=> $event['user_row']['user_rtng_enable'],
			'user_rtng_sort_start_time'		=> $event['user_row']['user_rtng_sort_start_time'],
			'user_rtng_unread_only'			=> $event['user_row']['user_rtng_unread_only'],
			'user_rtng_location'			=> $event['user_row']['user_rtng_location'],
			'user_rtng_disp_last_post'		=> $event['user_row']['user_rtng_disp_last_post'],
			'user_rtng_disp_first_unrd_post'=> $event['user_row']['user_rtng_disp_first_unrd_post'],
			'user_rtng_index_topics_qty'	=> $event['user_row']['user_rtng_index_topics_qty'],
			'user_rtng_index_page_qty'		=> $event['user_row']['user_rtng_index_page_qty'],
			'user_rtng_separate_topics_qty'	=> $event['user_row']['user_rtng_separate_topics_qty'],
			'user_rtng_separate_page_qty'	=> $event['user_row']['user_rtng_separate_page_qty'],
		]);
	}

	/**
	 * Modify users preferences data before assigning it to the template
	 */
	public function acp_users_prefs_modify_template_data($event)
	{
		$template_vars = $this->ctrl_common->get_user_set_template_vars($event['user_row']['user_id'], $event['user_row']);

		if (isset($template_vars['S_RTNG_SHOW']))
		{
			// Vars not used in guest account
			if ($event['user_row']['user_id'] == ANONYMOUS)
			{
				unset($template_vars['RTNG_DISP_FIRST_UNRD_POST']);
				unset($template_vars['RTNG_UNREAD_ONLY']);
			}

			$event['user_prefs_data'] = array_merge($event['user_prefs_data'], $template_vars);
		}
	}
}
