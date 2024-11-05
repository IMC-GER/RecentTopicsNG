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
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\user */
	protected $user;

	/** @var imcger\recenttopicsng\core\rtng_functions */
	protected $rtng_functions;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/**
	 * Constructor
	 */
	public function __construct
	(
		\phpbb\user $user,
		\imcger\recenttopicsng\core\rtng_functions $rtng_functions,
		\phpbb\template\template $template,
		\phpbb\controller\helper $helper,
		\phpbb\language\language $language,
		\phpbb\auth\auth $auth
	)
	{
		$this->user				= $user;
		$this->rtng_functions	= $rtng_functions;
		$this->template			= $template;
		$this->helper			= $helper;
		$this->language			= $language;
		$this->auth				= $auth;
	}

	/**
	 * Get subscribed events
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.page_header'				=> 'set_template_vars',
			'core.index_modify_page_title'	=> 'display_rt',
			'core.permissions'				=> 'add_permission',
		];
	}

	/**
	 * Set template vars and load language
	 */
	public function set_template_vars()
	{
		$this->template->assign_vars([
			'U_RTNG_PAGE_SEPARATE'  => $this->helper->route('imcger_recenttopicsng_page_controller', ['page' => 'separate']),
			'S_RTNG_LINK_IN_NAVBAR' => $this->auth->acl_get('u_rtng_view') && $this->user->data['user_rtng_enable'] && $this->user->data['user_rtng_location'] == 'RTNG_SEPARATE',
		]);

		$this->language->add_lang('rtng_common', 'imcger/recenttopicsng');
	}

	/**
	 * The main magic
	 */
	public function display_rt()
	{
		if ($this->user->data['user_rtng_enable'] && $this->auth->acl_get('u_rtng_view'))
		{
			$this->rtng_functions->display_recent_topics();
		}
	}

	/**
	 * Add permissions
	 */
	public function add_permission($event)
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
}
