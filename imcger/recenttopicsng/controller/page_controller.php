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
	public function __construct
	(
		protected \phpbb\config\config $config,
		protected \phpbb\template\template $template,
		protected \phpbb\controller\helper $helper,
		protected \phpbb\language\language $language,
		protected \phpbb\auth\auth $auth,
		protected \imcger\recenttopicsng\core\rtng_functions $rtng_functions,
		protected \imcger\recenttopicsng\controller\controller_common $ctrl_common,
		protected $phpbb_root_path,
		protected $phpEx,
	)
	{
	}

	/**
	 * Display the page app.php/rtng/{page}
	 */
	public function display(string $page): object
	{
		$this->language->add_lang('rtng_common', 'imcger/recenttopicsng');
		$title = $this->language->lang('RTNG_DESIG');

		$user_setting = $this->ctrl_common->get_user_setting();

		// Redirect to index site when user has no permisson
		if (!($user_setting['user_rtng_enable'] && $this->auth->acl_get('u_rtng_view')))
		{
			redirect($this->phpbb_root_path . 'index.' . $this->phpEx);
		}

		switch ($page)
		{
			// Displays ResentTopics NG in a simple page for further use
			case 'simple':
				// Set the number of pages and topics
				$this->rtng_functions->set_topics_per_page((int) $this->config['rtng_simple_topics_qty']);
				$this->rtng_functions->set_topics_page_number((int) $this->config['rtng_simple_page_qty']);

				// Set template
				$rt_page = "@imcger_recenttopicsng/rtng_body_simple.html";

				$this->rtng_functions->display_recent_topics();

			break;

			// Displays ResentTopics NG in a separate page
			case 'separate':
				// Set the number of pages and topics
				$this->rtng_functions->set_topics_per_page((int) $user_setting['user_rtng_separate_topics_qty']);
				$this->rtng_functions->set_topics_page_number((int) $user_setting['user_rtng_separate_page_qty']);

				// Set template
				$rt_page = "@imcger_recenttopicsng/rtng_body_separate.html";

				// Generate jumpbox
				if (!function_exists('make_jumpbox'))
				{
					include($this->phpbb_root_path . 'includes/functions_content.' . $this->phpEx);
				}
				make_jumpbox(append_sid($this->phpbb_root_path . 'viewforum.' . $this->phpEx));

				// Generate link in NavBar
				$navlinks	= [];
				$navlinks[] = [
					'BREADCRUMB_NAME'	=> $this->language->lang('RTNG_DESIG'),
					'U_BREADCRUMB'		=> $this->helper->route('imcger_recenttopicsng_page_controller', ['page' => 'separate']),
				];

				$this->template->assign_vars([
					'navlinks'			=> $navlinks,
					'U_CANONICAL'		=> $this->helper->route('imcger_recenttopicsng_page_controller', ['page' => 'separate'], true, false, \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL),
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
