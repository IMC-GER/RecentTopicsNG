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

namespace paybas\recenttopics\acp;

/**
 * Class recenttopics_module
 *
 * @package paybas\recenttopics\acp
 */
class recenttopics_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	/**
	 * @param $id
	 * @param $mode
	 * @throws \Exception
	 *
	 */
	public function main($id, $mode)
	{
		global $phpbb_container;

		// Add ACP lang file
		$language = $phpbb_container->get('language');

		switch ($mode)
		{
			case 'recenttopics_config':
				// Get an instance of the admin controller
				$admin_controller = $phpbb_container->get('paybas.recenttopics.admin.controller');

				// Make the $u_action url available in the admin controller
				$admin_controller->set_page_url($this->u_action);

				// Load a template from adm/style for our ACP page
				$this->tpl_name = 'acp_recenttopics';

				// Set the page title for our ACP page
				$this->page_title = $language->lang('RECENT_TOPICS');

				// Load the display options handle in the admin controller
				$admin_controller->display_options();
			break;
		}
	}
}
