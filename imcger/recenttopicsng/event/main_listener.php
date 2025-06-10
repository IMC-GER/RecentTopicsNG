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

class main_listener implements EventSubscriberInterface
{
	protected object $rtng_functions;
	protected object $template;
	protected object $helper;
	protected object $language;
	protected object $auth;
	protected object $ctrl_common;
	private array $user_setting;

	public function __construct
	(
		\imcger\recenttopicsng\core\rtng_functions $rtng_functions,
		\phpbb\template\template $template,
		\phpbb\controller\helper $helper,
		\phpbb\language\language $language,
		\phpbb\auth\auth $auth,
		\imcger\recenttopicsng\controller\controller_common $controller_common
	)
	{
		$this->rtng_functions	= $rtng_functions;
		$this->template			= $template;
		$this->helper			= $helper;
		$this->language			= $language;
		$this->auth				= $auth;
		$this->ctrl_common		= $controller_common;

		$this->user_setting = $this->ctrl_common->get_user_setting();
	}

	public static function getSubscribedEvents(): array
	{
		return [
			'core.user_setup_after'				 => 'user_setup_after',
			'core.page_header'					 => 'set_template_vars',
			'core.index_modify_page_title'		 => 'display_rt',
			'core.permissions'					 => 'add_permission',
			'core.viewonline_overwrite_location' => 'viewonline_overwrite_location',
		];
	}

	public function user_setup_after(): void
	{
		$this->language->add_lang('rtng_common', 'imcger/recenttopicsng');
	}

	public function set_template_vars(): void
	{
		$this->template->assign_vars([
			'U_RTNG_PAGE_SEPARATE'  => $this->helper->route('imcger_recenttopicsng_page_controller', ['page' => 'separate']),
			'S_RTNG_LINK_IN_NAVBAR' => $this->auth->acl_get('u_rtng_view') && $this->user_setting['user_rtng_enable'] && $this->user_setting['user_rtng_location'] == 'RTNG_SEPARATE',
		]);
	}

	public function display_rt(): void
	{
		if ($this->user_setting['user_rtng_enable'] && $this->auth->acl_get('u_rtng_view'))
		{
			$this->rtng_functions->display_recent_topics();
		}
	}

	public function add_permission(object $event): void
	{
		$permissions = $event['permissions'];
		$categories = $event['categories'];
		$categories['rtng'] = 'ACL_CAT_RTNG';
		$permissions['u_rtng_view']					= ['lang' => 'ACL_U_RTNG_VIEW',					'cat' => 'rtng'];
		$permissions['u_rtng_enable']				= ['lang' => 'ACL_U_RTNG_ENABLE',				'cat' => 'rtng'];
		$permissions['u_rtng_location']				= ['lang' => 'ACL_U_RTNG_LOCATION',				'cat' => 'rtng'];
		$permissions['u_rtng_sort_start_time']		= ['lang' => 'ACL_U_RTNG_SORT_START_TIME',		'cat' => 'rtng'];
		$permissions['u_rtng_unread_only']			= ['lang' => 'ACL_U_RTNG_UNREAD_ONLY',			'cat' => 'rtng'];
		$permissions['u_rtng_disp_last_post']		= ['lang' => 'ACL_U_RTNG_DISP_LAST_POST',		'cat' => 'rtng'];
		$permissions['u_rtng_disp_first_unrd_post']	= ['lang' => 'ACL_U_RTNG_DISP_FIRST_UNRD_POST',	'cat' => 'rtng'];
		$permissions['u_rtng_index_topics_qty']		= ['lang' => 'ACL_U_RTNG_INDEX_TOPICS_QTY',		'cat' => 'rtng'];
		$permissions['u_rtng_index_page_qty']		= ['lang' => 'ACL_U_RTNG_INDEX_PAGE_QTY',		'cat' => 'rtng'];
		$permissions['u_rtng_separate_topics_qty']	= ['lang' => 'ACL_U_RTNG_SEPARATE_TOPICS_QTY',	'cat' => 'rtng'];
		$permissions['u_rtng_separate_page_qty']	= ['lang' => 'ACL_U_RTNG_SEPARATE_PAGE_QTY',	'cat' => 'rtng'];
		$event['permissions'] = $permissions;
		$event['categories'] = $categories;
	}

	/**
	 * Overwrite the location's name and URL, which are displayed in the "Who is online" list
	 */
	public function viewonline_overwrite_location(object $event): void
	{
		if (strpos($event['row']['session_page'], 'rtng/') !== false)
		{
			$session_page_parts = explode('/', $event['row']['session_page']);
			$site = end($session_page_parts);

			$event['location']		= $this->language->lang('RTNG_READ_' . strtoupper($site));
			$event['location_url']	= $this->helper->route('imcger_recenttopicsng_page_controller', ['page' => $site]);
		}
	}
}
