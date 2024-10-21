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

namespace paybas\recenttopics\controller;

class admin_controller
{
	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var language */
	protected $language;

	/** @var request */
	protected $request;

	/** @var string Custom form action */
	protected $u_action;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config				$config
	 * @param \phpbb\template\template			$template
	 * @param \phpbb\language\language			$language
	 * @param \phpbb\request\request			$request
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\extension\manager			$ext_manager
	 *
	 */
	public function __construct
	(
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\extension\manager $ext_manager
	)
	{
		$this->config		= $config;
		$this->template		= $template;
		$this->language		= $language;
		$this->request		= $request;
		$this->db			= $db;
		$this->ext_manager	= $ext_manager;
	}

	/**
	 * Display the options a user can configure for this extension
	 *
	 * @return null
	 * @access public
	 */
	public function display_options()
	{
		// Add ACP lang file
		$this->language->add_lang('info_acp_recenttopics', 'paybas/recenttopics');
		$this->language->add_lang(['viewforum', 'ucp', ]);

		add_form_key('paybas/recenttopics');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('paybas/recenttopics'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// Store the variable to the db
			$this->set_vars_config();

			// Upate user settings whith default data
			$overwrite_userset = $this->request->variable('rt_reset_default', 0);
			$this->set_vars_usertable((bool) $overwrite_userset);

			trigger_error($this->language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		$this->set_template_vars();
	}

	/**
	 * Set template variables
	 *
	 * @return null
	 * @access protected
	 */
	protected function set_template_vars()
	{
		// Read guest account settings as default
		$sql = 'SELECT user_rt_enable, user_rt_sort_start_time, user_rt_unread_only,
					   user_rt_location, user_rt_number
				FROM ' . USERS_TABLE . '
				WHERE user_id = ' . ANONYMOUS;

		$result	= $this->db->sql_query_limit($sql, 1);
		$user_data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$metadata_manager = $this->ext_manager->create_extension_metadata_manager('paybas/recenttopics');

		$this->template->assign_vars([
			'U_ACTION'		=> $this->u_action,
			'RTNG_TITLE'	=> $metadata_manager->get_metadata('display-name'),
			'RTNG_EXT_VER'	=> $metadata_manager->get_metadata('version'),

			'RT_PAGE_NUMBERMAX'			=> (int) $this->config['rt_page_numbermax'],
			'RT_ANTI_TOPICS'			=> $this->config['rt_anti_topics'],
			'RT_PARENTS'				=> (int) $this->config['rt_parents'],
			'RT_PAGE_NUMBER'			=> (int) $this->config['rt_page_number'],
			'RT_MIN_TOPIC_LEVEL'		=> (int) $this->config['rt_min_topic_level'],
			'RT_MIN_TOPIC_LEVEL_OPTIONS' => [
				'POST'				  => '0',
				'POST_STICKY'		  => '1',
				'ANNOUNCEMENTS'		  => '2',
				'GLOBAL_ANNOUNCEMENT' => '3',
			],

			'RTNG_ENABLE'				=> $user_data['user_rt_enable'],
			'RTNG_SORT_START_TIME'		=> $user_data['user_rt_sort_start_time'],
			'RTNG_UNREAD_ONLY'			=> $user_data['user_rt_unread_only'],
			'RTNG_LOCATION'				=> $user_data['user_rt_location'],
			'RTNG_TOPIC_NUMBER'				=> $user_data['user_rt_number'],
			'RTNG_LOCATION_OPTIONS' => [
				'RT_TOP'	 	=> 'RT_TOP',
				'RT_BOTTOM'		=> 'RT_BOTTOM',
				'RT_SIDE'	 	=> 'RT_SIDE',
				'RT_SEPARAT' 	=> 'RT_SEPARAT',
			],
		]);

	}

	/**
	 * Store the variable to config
	 *
	 * @return null
	 * @access protected
	 */
	protected function set_vars_config()
	{
		/*
		* acp options for everyone
		*/
		// Maximum number of pages
		$this->config->set('rt_page_numbermax', $this->request->variable('rt_page_numbermax', 0));

		//Show all recent topic pages
		$this->config->set('rt_page_number', $this->request->variable('rt_page_number', 0));

		// Minimum topic type level
		$this->config->set('rt_min_topic_level', $this->request->variable('rt_min_topic_level', 0));

		// variable should be '' as it is a string ("1, 2, 3928") here, not an integer.
		$rt_anti_topics = $this->request->variable('rt_anti_topics', '');
		$ants = explode(",", $rt_anti_topics);
		$checkants = true;
		foreach ($ants as $ant)
		{
			if (!is_numeric($ant))
			{
				$checkants = false;
			}
		}
		if ($checkants)
		{
			$this->config->set('rt_anti_topics', $rt_anti_topics);
		}

		$this->config->set('rt_parents', $this->request->variable('rt_parents', 0));
	}

	/**
	 * Upate user settings
	 *
	 * @param  bool		$all_user	Store data to all user
	 * @return null
	 * @access protected
	 */
	protected function set_vars_usertable($all_user)
	{
		$sql_ary = [
			'user_rt_enable'		  => (int) $this->request->variable('user_rtng_enable', 0),
			'user_rt_sort_start_time' => (int) $this->request->variable('user_rtng_sort_start_time', 0),
			'user_rt_unread_only'	  => (int) $this->request->variable('user_rtng_unread_only', 0),
			'user_rt_location'		  => $this->request->variable('user_rtng_location', 'RT_TOP'),
			'user_rt_number'		  => (int) $this->request->variable('user_rtng_topic_number', 5),
		];

		$sql_where = $all_user ? '' : ' WHERE user_id = ' . ANONYMOUS;

		$sql = 'UPDATE ' . USERS_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . $sql_where;

		$this->db->sql_query($sql);
	}

	/**
	 * Set page url
	 *
	 * @param string $u_action Custom form action
	 * @return null
	 * @access public
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
