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

namespace imcger\recenttopicsng\acp;

/**
 * Class acp_rtng_module
 */
class acp_rtng_module
{
	public string $page_title;
	public string $tpl_name;
	public string $u_action;

	public function main(string $id, string $mode): void
	{
		global $phpbb_container;

		$language = $phpbb_container->get('language');

		switch ($mode)
		{
			case 'settings':
				// Get an instance of the admin controller
				$admin_controller = $phpbb_container->get('imcger.recenttopicsng.admin.controller');

				// Make the $u_action url available in the admin controller
				$admin_controller->set_page_url($this->u_action);

				// Load a template from adm/style for our ACP page
				$this->tpl_name = 'acp_rtng';

				// Set the page title for our ACP page
				$this->page_title = $language->lang('RTNG_NAME');

				// Load the display options handle in the admin controller
				$admin_controller->display_options();
			break;
		}
	}
}
