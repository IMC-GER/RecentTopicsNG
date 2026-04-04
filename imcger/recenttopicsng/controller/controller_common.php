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

class controller_common
{
	protected array $rtng_user_data;

	public function __construct
	(
		protected \phpbb\user $user,
		protected \phpbb\auth\auth $auth,
		protected \phpbb\db\driver\driver_interface $db,
		protected \phpbb\config\config $config,
		protected \phpbb\extension\manager $ext_manager,
	)
	{
		$this->set_rtng_user_data($user->data);
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
			$user_auth		= new \phpbb\auth\auth();
			$auth_userdata	= $user_auth->obtain_user_data($user_id);
			$user_auth->acl($auth_userdata);
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
					'RTNG_FIRST_POST'	=> 0,
					'RTNG_LAST_POST'	=> 1,
				]);
		}

		if (($user_auth->acl_get('u_rtng_disp_first_unrd_post') || $uaec) && ($this->config['rtng_load_first_unrd_post'] && $this->config['load_db_lastread']))
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
			$template_vars['S_RTNG_SHOW'] = true;
		}

		unset($user_auth);

		return $template_vars;
	}

	/**
	 * Store user data in cache array
	 */
	public function set_rtng_user_data(array $user_data): bool
	{
		if (empty($user_data['user_id']))
		{
			return false;
		}

		$this->rtng_user_data[$user_data['user_id']] = [];

		foreach ($user_data as $key => $value)
		{
			if (str_starts_with($key, 'user_rtng'))
			{
				$this->rtng_user_data[$user_data['user_id']][$key] = $value;
			}
		}

		return true;
	}

	/**
	 * Returns the RTNG user data.
	 * Cache user data in an array
	 */
	public function get_rtng_user_data(int $user_id = ANONYMOUS): array
	{
		if (isset($this->rtng_user_data[$user_id]))
		{
			return $this->rtng_user_data[$user_id];
		}

		$sql_array = [
			'SELECT'    => 'user_rtng_enable, user_rtng_sort_start_time, user_rtng_unread_only,
							user_rtng_location, user_rtng_disp_last_post, user_rtng_disp_first_unrd_post,
							user_rtng_index_topics_qty, user_rtng_index_page_qty,
							user_rtng_separate_topics_qty, user_rtng_separate_page_qty',
			'FROM'      => [USERS_TABLE => ''],
			'WHERE'     => 'user_id = ' . (int) $user_id,
		];

		$sql		= $this->db->sql_build_query('SELECT', $sql_array);
		$cache_time = $user_id == ANONYMOUS ? 3600 : 0;
		$result		= $this->db->sql_query($sql, $cache_time);
		$this->rtng_user_data[$user_id] = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $this->rtng_user_data[$user_id];
	}

	/**
	 * Returns the RTNG user settings depending on the user permission.
	 */
	public function get_user_setting(int $user_id = 0): array
	{
		$user_id	  = $user_id ?: $this->user->data['user_id'];
		$default_data = $this->get_rtng_user_data(ANONYMOUS);

		if ($user_id == ANONYMOUS)
		{
			return $default_data;
		}

		$user_data = $this->get_rtng_user_data($user_id);

		if ($user_id != $this->user->data['user_id'])
		{
			$user_auth		= new \phpbb\auth\auth();
			$auth_userdata	= $user_auth->obtain_user_data($user_id);
			$user_auth->acl($auth_userdata);
		}
		else
		{
			$user_auth = $this->auth;
		}

		foreach ($default_data as $key => $value)
		{
			if ($user_auth->acl_get('u_' . substr($key, 5)))
			{
				$default_data[$key] = $user_data[$key];
			}
		}

		unset($user_auth);

		return $default_data;
	}

	/**
	 * Returns the RTNG composer data.
	 */
	public function get_rtng_composer_data(): array
	{
		return $this->ext_manager->create_extension_metadata_manager('imcger/recenttopicsng')->get_metadata('all');
	}
}
