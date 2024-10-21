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

namespace paybas\recenttopics\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\user */
	protected $user;

	/** @var recenttopics */
	protected $rt_functions;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var auth */
	protected $auth;

	/**
	 * main_listener constructor.
	 *
	 * @param \phpbb\user							 $user
	 * @param \paybas\recenttopics\core\recenttopics $functions
	 * @param \phpbb\config\config					 $config
	 * @param \phpbb\template\template				 $template
	 * @param \phpbb\controller\helper				 $helper
	 * @param \phpbb\language\language 				 $language
	 * @param \phpbb\auth\auth						 $auth
	 */
	public function __construct
	(
		\phpbb\user $user,
		\paybas\recenttopics\core\recenttopics $functions,
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\controller\helper $helper,
		\phpbb\language\language $language,
		\phpbb\auth\auth $auth
	)
	{
		$this->user			= $user;
		$this->rt_functions	= $functions;
		$this->config		= $config;
		$this->template		= $template;
		$this->helper		= $helper;
		$this->language		= $language;
		$this->auth			= $auth;
	}

	/**
	 * Get subscribed events
	 *
	 * @return array
	 * @static
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.page_header'						 => 'set_template_vars',
			'core.index_modify_page_title'           => 'display_rt',
			'core.permissions'                       => 'add_permission',
		];
	}

	/**
	 * Set template vars and load language
	 *
	 * @return null
	 * @access public
	 */
	public function set_template_vars()
	{
		$this->template->assign_vars([
			'U_RTNG_PAGE_SEPARATE'  => $this->helper->route('paybas_recenttopics_page_controller', ['page' => 'separate']),
			'S_RTNG_LINK_IN_NAVBAR' => $this->auth->acl_get('u_rt_view') && $this->user->data['user_rt_enable'] && $this->user->data['user_rt_location'] == 'RT_SEPARAT',
		]);

		$this->language->add_lang('recenttopics', 'paybas/recenttopics');
	}

	/**
	 * The main magic
	 *
	 * @return null
	 * @access public
	 */
	public function display_rt()
	{
		if (!($this->user->data['user_rt_enable'] && $this->auth->acl_get('u_rt_view')))
		{
			$this->rt_functions->display_recent_topics();
		}
	}

	/**
	 * Add permissions
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$categories = $event['categories'];
		$categories['recenttopics'] = 'ACL_CAT_RTNG';
		$permissions['u_rt_view']				= ['lang' => 'ACL_U_RTNG_VIEW'				, 'cat' => 'recenttopics'];
		$permissions['u_rt_enable']				= ['lang' => 'ACL_U_RTNG_ENABLE'			, 'cat' => 'recenttopics'];
		$permissions['u_rt_location']			= ['lang' => 'ACL_U_RTNG_LOCATION'			, 'cat' => 'recenttopics'];
		$permissions['u_rt_sort_start_time']	= ['lang' => 'ACL_U_RTNG_SORT_START_TIME'	, 'cat' => 'recenttopics'];
		$permissions['u_rt_unread_only']		= ['lang' => 'ACL_U_RTNG_UNREAD_ONLY'		, 'cat' => 'recenttopics'];
		$permissions['u_rt_number']				= ['lang' => 'ACL_U_RTNG_NUMBER'			, 'cat' => 'recenttopics'];
		$event['permissions'] = $permissions;
		$event['categories'] = $categories;
	}
}
