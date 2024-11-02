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

class page_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \imcger\recenttopicsng\core\rtng_functions */
	protected $rtng_functions;

	/** @var string phpBB root path	*/
	protected $phpbb_root_path;

	/** @var string PHP extension */
	protected $phpEx;

	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\controller\helper $helper,
		\phpbb\language\language $language,
		\phpbb\user $user,
		\phpbb\auth\auth $auth,
		\imcger\recenttopicsng\core\rtng_functions $rtng_functions,
		$phpbb_root_path,
		$phpEx
	)
	{
		$this->config			= $config;
		$this->template			= $template;
		$this->helper			= $helper;
		$this->language			= $language;
		$this->user				= $user;
		$this->auth				= $auth;
		$this->rtng_functions	= $rtng_functions;
		$this->phpbb_root_path	= $phpbb_root_path;
		$this->phpEx			= $phpEx;
	}

	/**
	 * Display the page app.php/rtng/{page}
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 * @access public
	 */
	public function display($page)
	{
		$this->language->add_lang('rtng_common', 'imcger/recenttopicsng');
		$title = $this->language->lang('RTNG_TITLE');

		// Redirect to index site when user has no permisson
		if (!($this->user->data['user_rtng_enable'] && $this->auth->acl_get('u_rtng_view')))
		{
			redirect($this->phpbb_root_path . 'index.' . $this->phpEx);
		}

		switch ($page)
		{
			// Displays ResentTopics NG in a simple page for further use
			case 'simple':
				// Topics per page, 0 use default settings
				$this->rtng_functions->topics_per_page = $this->config['rtng_simple_topic_qty'];

				// Numbers of pages, 0 use default settings
				$this->rtng_functions->topics_page_number = $this->config['rtng_simple_page_qty'];

				// Set template
				$rt_page  = "@imcger_recenttopicsng/rtng_body_simple.html";

				$this->rtng_functions->display_recent_topics();

			break;

			// Displays ResentTopics NG in a separate page
			case 'separate':
				// Topics per page, 0 use default settings
				$this->rtng_functions->topics_per_page = $this->user->data['user_rtng_separate_topics_qty'];

				// Numbers of pages, 0 use default settings
				$this->rtng_functions->topics_page_number = $this->user->data['user_rtng_separate_page_qty'];

				// Set template
				$rt_page  = "@imcger_recenttopicsng/rtng_body_separate.html";

				// Generate jumpbox
				if (!function_exists('make_jumpbox'))
				{
					include($this->phpbb_root_path . 'includes/functions_content.' . $this->phpEx);
				}
				make_jumpbox(append_sid($this->phpbb_root_path . 'viewforum.' . $this->phpEx));

				// Generate link in NavBar
				$this->template->assign_block_vars('navlinks', [
					'BREADCRUMB_NAME'	=> $this->language->lang('RTNG_TITLE'),
					'U_BREADCRUMB'		=> $this->helper->route('imcger_recenttopicsng_page_controller', ['page' => 'separate']),
				]);

				$this->rtng_functions->display_recent_topics();

			break;

			// Displays the start page of phpBB
			default:
				redirect($this->phpbb_root_path . 'index.' . $this->phpEx);
			break;
		}

		return $this->helper->render($rt_page, $title);
	}
}
