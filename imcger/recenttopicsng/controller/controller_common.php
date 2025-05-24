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

/**
 * Class controller_common
 *
 * @package imcger\recenttopicsng\controller
 */
class controller_common
{
	protected object $user;
	protected object $auth;
	protected object $db;

	public function __construct
	(
		\phpbb\user $user,
		\phpbb\auth\auth $auth,
		\phpbb\db\driver\driver_interface $db
	)
	{
		$this->user = $user;
		$this->auth = $auth;
		$this->db	= $db;
	}

	/*
	 * Creates an array of variables for the SelectBox macro
	 *
	 * The variable $cfg_value is a union type array|string
	 * Not specified for reasons of compatibility with php 7
	 */
	public function select_struct($cfg_value, array $options): array
	{
		$options_tpl = [];

		foreach ($options as $opt_key => $opt_value)
		{
			if (!is_array($opt_value))
			{
				$opt_value = [$opt_value];
			}
			$options_tpl[] = [
				'label'		=> $opt_key,
				'value'		=> $opt_value[0],
				'bold'		=> $opt_value[1] ?? false,
				'selected'	=> is_array($cfg_value) ? in_array($opt_value[0], $cfg_value) : $opt_value[0] == $cfg_value,
			];
		}

		return $options_tpl;
	}

	/**
	 * Returns the RTNG template vars that the user is allowed to change.
	 */
	public function get_user_set_template_vars(int $user_id, array $template_setting): array
	{
		if ($user_id != $this->user->data['user_id'])
		{
			$user_auth	= new \phpbb\auth\auth();
			$userdata	= $user_auth->obtain_user_data($user_id);
			$user_auth->acl($userdata);
		}
		else
		{
			$user_auth = $this->auth;
		}

		// Show settings in user administration for anonymous
		// and extension configuration
		$uaec = $user_id == ANONYMOUS;

		$template_vars = [];

		if (!$user_auth->acl_get('u_rtng_view'))
		{
			return $template_vars;
		}

		if ($user_auth->acl_get('u_rtng_enable') || $uaec)
		{
			$template_vars['RTNG_ENABLE'] = $template_setting['user_rtng_enable'];
		}

		if ($user_auth->acl_get('u_rtng_location') || $uaec)
		{
			$template_vars['RTNG_LOCATION_OPTIONS'] = $this->select_struct($template_setting['user_rtng_location'], [
					'RTNG_TOP'			=> 'RTNG_TOP',
					'RTNG_BOTTOM'		=> 'RTNG_BOTTOM',
					'RTNG_SIDE'			=> 'RTNG_SIDE',
					'RTNG_SEPARATE'		=> 'RTNG_SEPARATE',
				]);
		}

		if ($user_auth->acl_get('u_rtng_sort_start_time') || $uaec)
		{
			$template_vars['RTNG_SORT_START_TIME'] = $template_setting['user_rtng_sort_start_time'];
		}

		if ($user_auth->acl_get('u_rtng_disp_last_post') || $uaec)
		{
			$template_vars['RTNG_DISP_LAST_POST_OPTIONS'] = $this->select_struct((int) $template_setting['user_rtng_disp_last_post'], [
					'RTNG_FIRST_POST'			=> 0,
					'RTNG_LAST_POST'			=> 1,
				]);
		}

		if ($user_auth->acl_get('u_rtng_disp_first_unrd_post') || $uaec)
		{
			$template_vars['RTNG_DISP_FIRST_UNRD_POST'] = $template_setting['user_rtng_disp_first_unrd_post'];
		}

		if ($user_auth->acl_get('u_rtng_unread_only') || $uaec)
		{
			$template_vars['RTNG_UNREAD_ONLY'] = $template_setting['user_rtng_unread_only'];
		}

		if ($user_auth->acl_get('u_rtng_index_topics_qty') || $uaec)
		{
			$template_vars['RTNG_INDEX_TOPICS_QTY'] = $template_setting['user_rtng_index_topics_qty'];
		}

		if ($user_auth->acl_get('u_rtng_index_page_qty') || $uaec)
		{
			$template_vars['RTNG_INDEX_PAGE_QTY'] = $template_setting['user_rtng_index_page_qty'];
		}

		if ($user_auth->acl_get('u_rtng_separate_topics_qty') || $uaec)
		{
			$template_vars['RTNG_SEPARATE_TOPICS_QTY'] = $template_setting['user_rtng_separate_topics_qty'];
		}

		if ($user_auth->acl_get('u_rtng_separate_page_qty') || $uaec)
		{
			$template_vars['RTNG_SEPARATE_PAGE_QTY'] = $template_setting['user_rtng_separate_page_qty'];
		}

		if (count($template_vars))
		{
			$template_vars['S_RTNG_SHOW']	  = true;
			$template_vars['TOGGLECTRL_RTNG'] = 'radio';
		}

		unset($user_auth);

		return $template_vars;
	}

	/**
	 * Returns the RTNG user data.
	 */
	public function get_rtng_user_data(int $user_id = ANONYMOUS): array
	{
		$sql_array = [
			'SELECT'    => 'user_rtng_enable, user_rtng_sort_start_time, user_rtng_unread_only,
							user_rtng_location, user_rtng_disp_last_post, user_rtng_disp_first_unrd_post,
							user_rtng_index_topics_qty, user_rtng_index_page_qty,
							user_rtng_separate_topics_qty, user_rtng_separate_page_qty',
			'FROM'      => [USERS_TABLE => ''],
			'WHERE'     => 'user_id = ' . (int) $user_id,
		];

		$sql    = $this->db->sql_build_query('SELECT', $sql_array);
		$result	= $this->db->sql_query_limit($sql, 1);
		$data	= $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $data;
	}

	/**
	 * Returns the RTNG user settings depending on the user authorizations.
	 */
	public function get_user_setting(?int $user_id = null): array
	{
		$default_data = $this->get_rtng_user_data();

		if (isset($user_id))
		{
			$user_data = $this->get_rtng_user_data($user_id);
		}
		else
		{
			$user_id	= $this->user->data['user_id'];
			$user_data	= $this->user->data;
		}

		if ($user_id == ANONYMOUS)
		{
			return $default_data;
		}

		if ($user_id != $this->user->data['user_id'])
		{
			$user_auth	= new \phpbb\auth\auth();
			$userdata	= $user_auth->obtain_user_data($user_id);
			$user_auth->acl($userdata);
		}
		else
		{
			$user_auth = $this->auth;
		}

		if ($user_auth->acl_get('u_rtng_enable'))
		{
			$default_data['user_rtng_enable'] = $user_data['user_rtng_enable'];
		}

		if ($user_auth->acl_get('u_rtng_sort_start_time'))
		{
			$default_data['user_rtng_sort_start_time'] = $user_data['user_rtng_sort_start_time'];
		}

		if ($user_auth->acl_get('u_rtng_unread_only'))
		{
			$default_data['user_rtng_unread_only'] = $user_data['user_rtng_unread_only'];
		}

		if ($user_auth->acl_get('u_rtng_location'))
		{
			$default_data['user_rtng_location'] = $user_data['user_rtng_location'];
		}

		if ($user_auth->acl_get('u_rtng_disp_last_post'))
		{
			$default_data['user_rtng_disp_last_post'] = $user_data['user_rtng_disp_last_post'];
		}

		if ($user_auth->acl_get('u_rtng_disp_first_unrd_post'))
		{
			$default_data['user_rtng_disp_first_unrd_post'] = $user_data['user_rtng_disp_first_unrd_post'];
		}

		if ($user_auth->acl_get('u_rtng_index_topics_qty'))
		{
			$default_data['user_rtng_index_topics_qty'] = $user_data['user_rtng_index_topics_qty'];
		}

		if ($user_auth->acl_get('u_rtng_index_page_qty'))
		{
			$default_data['user_rtng_index_page_qty'] = $user_data['user_rtng_index_page_qty'];
		}

		if ($user_auth->acl_get('u_rtng_separate_topics_qty'))
		{
			$default_data['user_rtng_separate_topics_qty'] = $user_data['user_rtng_separate_topics_qty'];
		}

		if ($user_auth->acl_get('u_rtng_separate_page_qty'))
		{
			$default_data['user_rtng_separate_page_qty'] = $user_data['user_rtng_separate_page_qty'];
		}

		unset($user_auth);

		return $default_data;
	}
}
