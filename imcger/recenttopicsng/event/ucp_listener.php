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
class ucp_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	protected $ctrl_common;

	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\auth\auth $auth,
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\language\language $language,
		\phpbb\db\driver\driver_interface $db,
		\imcger\recenttopicsng\controller\controller_common $controller_common
	)
	{
		$this->auth			= $auth;
		$this->request		= $request;
		$this->template 	= $template;
		$this->user			= $user;
		$this->language		= $language;
		$this->db			= $db;
		$this->ctrl_common	= $controller_common;
	}

	/**
	 * Get subscribed events
	 */
	public static function getSubscribedEvents()
	{
		return [
		'core.ucp_prefs_view_data'        	=> 'ucp_prefs_get_data',
		'core.ucp_prefs_view_update_data'	=> 'ucp_prefs_set_data',
		'core.ucp_register_register_after'	=> 'ucp_register_set_data'
		];
	}

	/**
	 * Set template vars. On submit store settings to user table.
	 */
	public function ucp_prefs_get_data($event)
	{
		// Request the user option vars and add them to the data array
		$event['data'] = array_merge(
			$event['data'], [
				'user_rtng_enable'				 => $this->request->variable('user_rtng_enable', (int) $this->user->data['user_rtng_enable']),
				'user_rtng_location'			 => $this->request->variable('user_rtng_location', $this->user->data['user_rtng_location']),
				'user_rtng_sort_start_time'		 => $this->request->variable('user_rtng_sort_start_time', (int) $this->user->data['user_rtng_sort_start_time']),
				'user_rtng_unread_only'			 => $this->request->variable('user_rtng_unread_only', (int) $this->user->data['user_rtng_unread_only']),
				'user_rtng_disp_last_post'		 => $this->request->variable('user_rtng_disp_last_post', (int) $this->user->data['user_rtng_disp_last_post']),
				'user_rtng_disp_first_unrd_post' => $this->request->variable('user_rtng_disp_first_unrd_post', (int) $this->user->data['user_rtng_disp_first_unrd_post']),
				'user_rtng_index_topics_qty'	 => $this->request->variable('user_rtng_index_topics_qty', (int) $this->user->data['user_rtng_index_topics_qty']),
				'user_rtng_index_page_qty'		 => $this->request->variable('user_rtng_index_page_qty', (int) $this->user->data['user_rtng_index_page_qty']),
				'user_rtng_separate_topics_qty'	 => $this->request->variable('user_rtng_separate_topics_qty', (int) $this->user->data['user_rtng_separate_topics_qty']),
				'user_rtng_separate_page_qty'	 => $this->request->variable('user_rtng_separate_page_qty', (int) $this->user->data['user_rtng_separate_page_qty']),
			]
		);

		// Output the data vars to the template (except on form submit)
		if (!$event['submit'])
		{
			$template_vars = $this->ctrl_common->get_user_set_template_vars($this->user->data['user_id'], $event['data']);

			if (isset($template_vars['S_RTNG_SHOW']))
			{
				$this->language->add_lang('info_acp_rtng', 'imcger/recenttopicsng');
				$this->template->assign_vars($template_vars);
			}
		}
	}

	/**
	 * Update the UCP settings in the user table when submitting the form.
	 */
	public function ucp_prefs_set_data($event)
	{
		$event['sql_ary'] = array_merge(
			$event['sql_ary'], [
				'user_rtng_enable'			 	 => $event['data']['user_rtng_enable'],
				'user_rtng_location'		 	 => $event['data']['user_rtng_location'],
				'user_rtng_sort_start_time'	 	 => $event['data']['user_rtng_sort_start_time'],
				'user_rtng_unread_only'		 	 => $event['data']['user_rtng_unread_only'],
				'user_rtng_disp_last_post'		 => $event['data']['user_rtng_disp_last_post'],
				'user_rtng_disp_first_unrd_post' => $event['data']['user_rtng_disp_first_unrd_post'],
				'user_rtng_index_topics_qty'	 => $event['data']['user_rtng_index_topics_qty'],
				'user_rtng_index_page_qty'		 => $event['data']['user_rtng_index_page_qty'],
				'user_rtng_separate_topics_qty'	 => $event['data']['user_rtng_separate_topics_qty'],
				'user_rtng_separate_page_qty'	 => $event['data']['user_rtng_separate_page_qty'],
			]
		);
	}

	/**
	 * After registering a new user, transfer the default values to their settings.
	 */
	public function ucp_register_set_data($event)
	{
		// Read guest account settings as default
		$user_data = $this->ctrl_common->get_rtng_user_data();

		$sql = 'UPDATE ' . USERS_TABLE . '
                SET ' . $this->db->sql_build_array('UPDATE', $user_data) . '
                WHERE user_id = ' . (int) $event['user_id'];

		$this->db->sql_query($sql);
	}
}
