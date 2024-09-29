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

class page_controller
{
	/** @var template */
	protected $template;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var language */
	protected $language;

	/** @var recenttopics */
	protected $rtng_functions;

	/** @var string phpBB root path	*/
	protected $phpbb_root_path;

	/** @var string PHP extension */
	protected $phpEx;

	/**
	 * page constructor.
	 *
	 * @param \phpbb\template\template				 		$template
	 * @param \phpbb\config\config							$config
	 * @param \phpbb\controller\helper						$helper
	 * @param \phpbb\language\language 						$language
	 * @param \paybas\recenttopics\core\recenttopics		$functions
	 * @param \Symfony\Component\HttpFoundation\Response	$response
	 * @param												$phpbb_root_path
	 * @param												$phpEx
	 */
	public function __construct
	(
		\phpbb\template\template $template,
		\phpbb\config\config $config,
		\phpbb\controller\helper $helper,
		\phpbb\language\language $language,
		\paybas\recenttopics\core\recenttopics $functions,
		$phpbb_root_path,
		$phpEx
	)
	{
		$this->template			= $template;
		$this->config			= $config;
		$this->helper			= $helper;
		$this->language			= $language;
		$this->rtng_functions		= $functions;
		$this->phpbb_root_path	= $phpbb_root_path;
		$this->phpEx			= $phpEx;
	}

	/**
	 * Display the page app.php/rt/{page}
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 * @access public
	 */
	public function display($page)
	{
		$this->language->add_lang('info_acp_recenttopics', 'paybas/recenttopics');
		$title = $this->language->lang('RTNG_TITLE');

		switch ($page)
		{
			// Displays ResentTopics in a simple page for further use
			case 'simple':
				// Topics per page, 0 use default settings
				$this->rtng_functions->topics_per_page = 0;

				// Numbers of pages, 0 use default settings
				$this->rtng_functions->topics_page_number = 0;

				// Set template
				$rt_page  = "@paybas_recenttopics/recenttopics_body_simple.html";

				if (isset($this->config['rt_index']) && $this->config['rt_index'])
				{
					$this->rtng_functions->display_recent_topics();
				}
			break;

			// Displays ResentTopics in a separate page
			case 'separate':
				// Topics per page, 0 use default settings
				$this->rtng_functions->topics_per_page = 0;

				// Numbers of pages, 0 use default settings
				$this->rtng_functions->topics_page_number = 0;

				// Set template
				$rt_page  = "@paybas_recenttopics/recenttopics_body_separate.html";

				if (isset($this->config['rt_index']) && $this->config['rt_index'])
				{
					// Generate jumpbox
					if (!function_exists('make_jumpbox'))
					{
						include($this->phpbb_root_path . 'includes/functions_content.' . $this->phpEx);
					}
					make_jumpbox(append_sid($this->phpbb_root_path . 'viewforum.' . $this->phpEx));

					// Generate link in NavBar
					$this->template->assign_block_vars('navlinks', [
						'BREADCRUMB_NAME'	=> $title,
						'U_BREADCRUMB'		=> $this->helper->route('paybas_recenttopics_page_controller', ['page' => 'separate']),
					]);

					$this->rtng_functions->display_recent_topics();
				}
			break;

			// Displays the start page of phpBB
			default:
				redirect($this->phpbb_root_path . 'index.' . $this->phpEx);
			break;
		}

		return $this->helper->render($rt_page, $title);
	}
}
