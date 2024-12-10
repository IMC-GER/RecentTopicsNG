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

namespace imcger\recenttopicsng\controller;

class admin_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\template\template */
	protected $language;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string Custom form action */
	protected $u_action;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/** @var \phpbb\controller\helper */
	protected $helper;

	protected $ctrl_common;

	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\extension\manager $ext_manager,
		\phpbb\controller\helper $helper,
		\imcger\recenttopicsng\controller\controller_common $controller_common
	)
	{
		$this->config		= $config;
		$this->template		= $template;
		$this->language		= $language;
		$this->request		= $request;
		$this->db			= $db;
		$this->ext_manager	= $ext_manager;
		$this->helper		= $helper;
		$this->ctrl_common	= $controller_common;
	}

	/**
	 * Display the options a user can configure for this extension
	 */
	public function display_options()
	{
		$this->language->add_lang(['viewforum', 'ucp']);

		add_form_key('imcger/recenttopicsng');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('imcger/recenttopicsng'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// Store the variable to the db
			$this->set_vars_config();

			// Upate user settings whith default data
			$overwrite_userset = $this->request->variable('rtng_reset_default', 0);
			$this->set_vars_usertable((bool) $overwrite_userset);

			trigger_error($this->language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		$this->set_template_vars();
	}

	/**
	 * Set page url
	 *
	 * @param string $u_action Custom form action
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}

	/**
	 * Set template variables
	 */
	protected function set_template_vars()
	{
		$metadata_manager = $this->ext_manager->create_extension_metadata_manager('imcger/recenttopicsng');

		$board_url		  = generate_board_url(true);
		$simple_page_path = $this->helper->route('imcger_recenttopicsng_page_controller', ['page' => 'simple']);
		$simple_page_url  = $board_url . explode('?', $simple_page_path)[0];

		$this->template->assign_vars([
			'U_ACTION'						=> $this->u_action,
			'U_RTNG_PAGE_SIMPLE'			=> $simple_page_url,

			'RTNG_NAME'						=> $metadata_manager->get_metadata('display-name'),
			'RTNG_EXT_VER'					=> $metadata_manager->get_metadata('version'),

			'RTNG_ANTI_TOPICS'				=> $this->config['rtng_anti_topics'],
			'RTNG_PARENTS'					=> (int) $this->config['rtng_parents'],
			'RTNG_ALL_TOPICS'				=> (int) $this->config['rtng_all_topics'],
			'RTNG_MIN_TOPIC_LEVEL_OPTIONS'	=> $this->ctrl_common->select_struct((int) $this->config['rtng_min_topic_level'], [
				'POST'						=> 0,
				'POST_STICKY'				=> 1,
				'ANNOUNCEMENTS'				=> 2,
				'GLOBAL_ANNOUNCEMENT'		=> 3,
			]),
			'RTNG_SIMPLE_TOPICS_QTY'		=> (int) $this->config['rtng_simple_topics_qty'],
			'RTNG_SIMPLE_PAGE_QTY'			=> (int) $this->config['rtng_simple_page_qty'],
		]);

		// Read guest account settings as default and setting template vars
		$user_data		= $this->ctrl_common->get_rtng_user_data(ANONYMOUS);
		$template_vars	= $this->ctrl_common->get_user_set_template_vars(ANONYMOUS, $user_data);
		unset($template_vars['TOGGLECTRL_RTNG']);
		$this->template->assign_vars($template_vars);
	}

	/**
	 * Store vars to config table
	 */
	protected function set_vars_config()
	{
		$this->config->set('rtng_all_topics', $this->request->variable('rtng_all_topics', 0));
		$this->config->set('rtng_min_topic_level', $this->request->variable('rtng_min_topic_level', 0));
		$this->config->set('rtng_parents', $this->request->variable('rtng_parents', 0));
		$this->config->set('rtng_simple_topics_qty', $this->request->variable('rtng_simple_topics_qty', 5));
		$this->config->set('rtng_simple_page_qty', $this->request->variable('rtng_simple_page_qty', 3));

		// Variable should be a string ("1234,2457,3928").
		$rtng_anti_topics = $this->request->variable('rtng_anti_topics', '');
		$rtng_anti_topics = str_replace(' ', '', $rtng_anti_topics);
		$pattern = '/^\d+(,\d+)*$/';

		if (preg_match($pattern, $rtng_anti_topics))
		{
			$this->config->set('rtng_anti_topics', $rtng_anti_topics);
		}
		else
		{
			$this->config->set('rtng_anti_topics', '0');
		}
	}

	/**
	 * Upate settings in user table
	 *
	 * @param  bool $all_user	Store data to all user when true
	 */
	protected function set_vars_usertable($all_user)
	{
		$sql_ary = [
			'user_rtng_enable'		 		 => (int) $this->request->variable('user_rtng_enable', 0),
			'user_rtng_sort_start_time'		 => (int) $this->request->variable('user_rtng_sort_start_time', 0),
			'user_rtng_unread_only'			 => (int) $this->request->variable('user_rtng_unread_only', 0),
			'user_rtng_location'			 => $this->request->variable('user_rtng_location', 'RTNG_TOP'),
			'user_rtng_disp_last_post'		 => (int) $this->request->variable('user_rtng_disp_last_post', 0),
			'user_rtng_disp_first_unrd_post' => (int) $this->request->variable('user_rtng_disp_first_unrd_post', 0),
			'user_rtng_index_topics_qty'	 => (int) $this->request->variable('user_rtng_index_topics_qty', 5),
			'user_rtng_index_page_qty'		 => (int) $this->request->variable('user_rtng_index_page_qty', 3),
			'user_rtng_separate_topics_qty'	 => (int) $this->request->variable('user_rtng_separate_topics_qty', 10),
			'user_rtng_separate_page_qty'	 => (int) $this->request->variable('user_rtng_separate_page_qty', 3),
		];
		$sql_where = $all_user ? '' : ' WHERE user_id = ' . ANONYMOUS;

		$sql = 'UPDATE ' . USERS_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . $sql_where;

		$this->db->sql_query($sql);
	}
}
