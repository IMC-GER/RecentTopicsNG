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

namespace imcger\recenttopicsng\core;

class rtng_functions
{
	protected object $auth;
	protected object $config;
	protected object $language;
	protected object $cache;
	protected object $content_visibility;
	protected object $db;
	protected object $dispatcher;
	protected object $pagination;
	protected object $request;
	protected object $template;
	protected object $user;
	protected string $root_path;
	protected string $phpEx;
	protected object $ctrl_common;
	private array $user_setting;
	private int $topics_per_page;
	private int $topics_page_number;

	public function __construct
	(
		\phpbb\auth\auth $auth,
		\phpbb\cache\service $cache,
		\phpbb\config\config $config,
		\phpbb\language\language $language,
		\phpbb\content_visibility $content_visibility,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\event\dispatcher_interface $dispatcher,
		\phpbb\pagination $pagination,
		\phpbb\request\request_interface $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		$root_path,
		$phpEx,
		\imcger\recenttopicsng\controller\controller_common $controller_common
	)
	{
		$this->auth					= $auth;
		$this->cache				= $cache;
		$this->config				= $config;
		$this->language				= $language;
		$this->content_visibility	= $content_visibility;
		$this->db					= $db;
		$this->dispatcher			= $dispatcher;
		$this->pagination			= $pagination;
		$this->request				= $request;
		$this->template				= $template;
		$this->user					= $user;
		$this->root_path			= $root_path;
		$this->phpEx				= $phpEx;
		$this->ctrl_common			= $controller_common;

		$this->topics_per_page		= 0;
		$this->topics_page_number	= 0;

		$this->user_setting = $this->ctrl_common->get_user_setting();
	}

	/**
	 * Set number of recent topics per page
	 */
	public function set_topics_per_page(int $topics_number): bool
	{
		if ($topics_number > 0)
		{
			$this->topics_per_page = $topics_number;
			return true;
		}

		return false;
	}

	/**
	 * Set the number of pages for recent topics
	 */
	public function set_topics_page_number(int $page_number): bool
	{
		if ($page_number > 0)
		{
			$this->topics_page_number = $page_number;
			return true;
		}

		return false;
	}

	/**
	 * Display recent topics
	 */
	public function display_recent_topics(string $tpl_loopname = 'rtng'): void
	{
		// can view rtng ?
		if (!($this->user_setting['user_rtng_enable'] && $this->auth->acl_get('u_rtng_view')))
		{
			return;
		}

		if (!function_exists('topic_status'))
		{
			include($this->root_path . 'includes/functions_display.' . $this->phpEx);
		}

		$rtng_start = $this->request->variable($tpl_loopname . '_start', 0);

		$excluded_topics = explode(',', $this->config['rtng_anti_topics']);

		$min_topic_level = $this->config['rtng_min_topic_level'];

		$forum_id_list = $this->getforumlist();

		// No forums to display
		if (count($forum_id_list) == 0)
		{
			return;
		}

		// When not 0, they set by page controller
		if (!$this->topics_per_page)
		{
			$this->topics_per_page = $this->user_setting['user_rtng_index_topics_qty'];
		}

		// When not 0, they set by page controller
		if (!$this->topics_page_number)
		{
			$this->topics_page_number = $this->user_setting['user_rtng_index_page_qty'];
		}

		// limit number of pages to be shown
		// compute as product of topics per page and max number of pages.
		if ((int) $this->config['rtng_all_topics'] == 0)
		{
			$total_topics_limit = $this->topics_per_page * $this->topics_page_number;
		}
		else
		{
			$sql_array = $this->get_allowed_topics_sql($excluded_topics, $min_topic_level, $forum_id_list);
			$count_sql_array = $sql_array;
			$count_sql_array['SELECT'] = 'COUNT(t.topic_id) as topic_count';
			unset($count_sql_array['ORDER_BY']);
			$sql = $this->db->sql_build_query('SELECT', $count_sql_array);

			$result = $this->db->sql_query($sql);
			$total_topics_limit = (int) $this->db->sql_fetchfield('topic_count');
			$this->db->sql_freeresult($result);
		}

		// These variables are defined in the gettopiclist() function.
		$obtain_icons = false;
		$forums		  = [];
		$topic_list	  = [];

		// When 0 there are no topics to display
		if ($total_topics_limit < 1)
		{
			return;
		}

		$topics_count = $this->gettopiclist($obtain_icons, $forums, $rtng_start, $total_topics_limit, $excluded_topics, $forum_id_list, $topic_list);

		// Return if there are no topics available to display.
		if (count($topic_list) == 0)
		{
			return;
		}

		// Grab icons
		$icons = [];
		if ($obtain_icons)
		{
			$icons = $this->cache->obtain_icons();
		}

		// Get the topic tracking data
		$topic_tracking_info = [];
		foreach ($forums as $forum_id => $forum)
		{
			if ($this->config['load_db_lastread'] && $this->user->data['is_registered'] && !$this->config['rtng_load_first_unrd_post'])
			{
				$topic_tracking_info[$forum_id] = get_topic_tracking($forum_id, $forum['topic_list'], $forum['rowset'], [$forum_id => $forum['mark_time']]);
			}
			else if ($this->config['load_anon_lastread'] || ($this->user->data['is_registered'] && !$this->config['load_db_lastread']))
			{
				$topic_tracking_info[$forum_id] = get_complete_topic_tracking($forum_id, $forum['topic_list']);
			}
		}

		//load language
		$this->language->add_lang('rtng_common', 'imcger/recenttopicsng');
		$this->template->assign_vars([
				'RTNG_TOPICS_COUNT'		 => $this->language->lang('RTNG_TOPICS_COUNT', (int) $topics_count),
				'RTNG_SORT_START_TIME'	 => $this->user_setting['user_rtng_sort_start_time'],
				'S_RTNG_LOCATION_TOP'	 => $this->user_setting['user_rtng_location'] == 'RTNG_TOP',
				'S_RTNG_LOCATION_BOTTOM' => $this->user_setting['user_rtng_location'] == 'RTNG_BOTTOM',
				'S_RTNG_LOCATION_SIDE'	 => $this->user_setting['user_rtng_location'] == 'RTNG_SIDE',
				strtoupper($tpl_loopname) . '_DISPLAY' => true,
			]
		);

		$this->fill_template($icons, $tpl_loopname, $topic_tracking_info, $topics_count, $topic_list, $total_topics_limit, $rtng_start);
	}

	/**
	 * Get the forums we take our topics from
	 */
	private function getforumlist(): array
	{
		// Get the allowed forums
		$forum_ary = [];
		$forum_read_ary = $this->auth->acl_getf('f_read');

		foreach ($forum_read_ary as $forum_id => $allowed)
		{
			if ($allowed['f_read'])
			{
				$forum_ary[] = (int) $forum_id;
			}
		}

		$forum_ids = array_unique($forum_ary);

		if (count($forum_ids) > 1)
		{
			$sql_array = [
				'SELECT'    => 'forum_id',
				'FROM'      => [FORUMS_TABLE => '', ],
				'WHERE'     =>  $this->db->sql_in_set('forum_id', $forum_ids) . '
							AND forum_rtng_disp = 1',
			];

			$sql    = $this->db->sql_build_query('SELECT', $sql_array);
			$result = $this->db->sql_query($sql);

			$rows = $this->db->sql_fetchrowset($result);
			$forum_ids_disp = array_column($rows, 'forum_id');

			$this->db->sql_freeresult($result);

			return array_unique($forum_ids_disp);
		}
		else
		{
			return [];
		}
	}

	/**
	 * Get the topic list
	 */
	private function gettopiclist(bool &$obtain_icons, array &$forums, int $rtng_start, int $total_topics_limit, array $excluded_topics, array $forum_id_list, array &$topic_list): int
	{
		$rtng_start		 = max(0, $rtng_start);
		$topics_count	 = 0;
		$min_topic_level = $this->config['rtng_min_topic_level'];

		// Either use the phpBB core function to get unread topics, or the custom function for default behavior
		if ($this->user_setting['user_rtng_unread_only'] && $this->user->data['is_registered'])
		{
			// Get unread topics
			$sql_extra	   = ' AND ' . $this->db->sql_in_set('t.topic_id', $excluded_topics, true);
			$sql_extra	  .= ' AND ' . $this->content_visibility->get_forums_visibility_sql('topic', $forum_id_list, $table_alias = 't.');
			$sql_extra	  .= ' AND t.topic_status <> ' . ITEM_MOVED;
			$unread_topics = get_unread_topics(false, $sql_extra, '', $total_topics_limit);

			$topics_count = count($unread_topics);
			$rtng_start	  = min($topics_count - 1 , $rtng_start);

			$topic_list = array_slice(array_keys($unread_topics), $rtng_start, $this->topics_per_page);
		}
		else
		{
			// Get allowed topics
			$sql_array = $this->get_allowed_topics_sql($excluded_topics, $min_topic_level, $forum_id_list);
			$count_sql_array = $sql_array;
			$count_sql_array['SELECT'] = 'COUNT(t.topic_id) as topic_count';
			unset($count_sql_array['ORDER_BY']);
			$sql = $this->db->sql_build_query('SELECT', $count_sql_array);

			$result = $this->db->sql_query($sql);
			$topics_count = (int) $this->db->sql_fetchfield('topic_count');
			$this->db->sql_freeresult($result);

			//load topics list
			$sql = $this->db->sql_build_query('SELECT', $sql_array);

			$rtng_start	  = min($total_topics_limit - 1, $rtng_start);
			$total_select = ($rtng_start + $this->topics_per_page) > $total_topics_limit ? ($this->topics_per_page - ($rtng_start % $this->topics_per_page)) : $this->topics_per_page;
			$result = $this->db->sql_query_limit($sql, $total_select, $rtng_start);

			// Return 0 topics to display
			if ($result == false)
			{
				return 0;
			}

			$rowset = [];

			while ($row = $this->db->sql_fetchrow($result))
			{
				$topic_list[] = $row['topic_id'];

				$rowset[$row['topic_id']] = $row;

				if (!isset($forums[$row['forum_id']]) && $this->user->data['is_registered'] && $this->config['load_db_lastread'])
				{
					$forums[$row['forum_id']]['mark_time'] = $row['f_mark_time'];
				}
				$forums[$row['forum_id']]['topic_list'][] = $row['topic_id'];
				$forums[$row['forum_id']]['rowset'][$row['topic_id']] = & $rowset[$row['topic_id']];

				if ($row['icon_id'])
				{
					$obtain_icons = true;
				}
			}

			$this->db->sql_freeresult($result);
		}

		// Return number of total topics counts to display
		return min($topics_count, $total_topics_limit);
	}

	/**
	 * Custom function to get allowed topics
	 * Used for anon access or when unread topics is not requested
	 */
	private function get_allowed_topics_sql(array $excluded_topics, int $min_topic_level, array $forum_id_list): array
	{
		$sort_topics = $this->user_setting['user_rtng_sort_start_time'] ? 'topic_time' : 'topic_last_post_time';

		// Get the allowed topics
		$sql_array = [
			'SELECT'    => 't.forum_id, t.topic_id, t.topic_type, t.icon_id, tp.topic_posted, tt.mark_time, ft.mark_time as f_mark_time, t.' . $sort_topics . ' as sortcr ',
			'FROM'      => [TOPICS_TABLE => 't'],
			'LEFT_JOIN' => [
				[
					'FROM' => [TOPICS_TRACK_TABLE => 'tt', ],
					'ON'   => 'tt.topic_id = t.topic_id AND tt.user_id = ' . (int) $this->user->data['user_id'],
				],
				[
					'FROM' => [FORUMS_TRACK_TABLE => 'ft', ],
					'ON'   => 'ft.forum_id = t.forum_id AND ft.user_id = ' . (int) $this->user->data['user_id'],
				],
				[
					'FROM' => [TOPICS_POSTED_TABLE => 'tp', ],
					'ON' => 'tp.topic_id = t.topic_id AND tp.user_id = ' . (int) $this->user->data['user_id'],
				],
			],
			'WHERE'     => $this->db->sql_in_set('t.topic_id', $excluded_topics, true) . '
					AND t.topic_status <> ' . ITEM_MOVED . '
					AND ' . $this->content_visibility->get_forums_visibility_sql('topic', $forum_id_list, $table_alias = 't.'),
			'ORDER_BY'  => 't.' . $sort_topics . ' DESC',
		];

		// Check if we want all topics, or only stickies/announcements/globals
		if ($min_topic_level > 0)
		{
			$sql_array['WHERE'] .= ' AND t.topic_type >= ' . (int) $min_topic_level;
		}

		/**
		 * Event to modify the SQL query before the allowed topics list data is retrieved
		 *
		 * @event imcger.recenttopicsng.sql_pull_topics_list
		 * @var	array	sql_array	The SQL array
		 * @since 1.0.0
		 */
		$vars = ['sql_array'];
		extract($this->dispatcher->trigger_event('imcger.recenttopicsng.sql_pull_topics_list', compact($vars)));

		return $sql_array;
	}

	/**
	 * Get username details for placing into templates.
	 */
	private function getusernamestrings(array $row): array
	{
		$topic_author				= get_username_string('username', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']);
		$topic_author_color			= get_username_string('colour', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']);
		$topic_author_full			= get_username_string('full', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']);
		$u_topic_author				= get_username_string('profile', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']);
		$last_post_author			= get_username_string('username', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']);
		$last_post_author_colour	= get_username_string('colour', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']);
		$last_post_author_full		= get_username_string('full', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']);
		$u_last_post_author			= get_username_string('profile', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']);
		return [$topic_author, $topic_author_color, $topic_author_full, $u_topic_author, $last_post_author, $last_post_author_colour, $last_post_author_full, $u_last_post_author];
	}

	/**
	 * Pull the data of the requested
	 */
	private function get_topics_sql(array $topic_list): array
	{
		$sort_topics = $this->user_setting['user_rtng_sort_start_time'] ? 'topic_time' : 'topic_last_post_time';

		$sql_array = [
			'SELECT'    => 't.*, f.forum_name, tp.topic_posted',
			'FROM'      => [TOPICS_TABLE => 't', ],
			'LEFT_JOIN' => [
				[
					'FROM' => [FORUMS_TABLE => 'f', ],
					'ON'   => 'f.forum_id = t.forum_id',
				],
				[
					'FROM' => [TOPICS_POSTED_TABLE => 'tp', ],
					'ON' => 'tp.topic_id = t.topic_id AND tp.user_id = ' . (int) $this->user->data['user_id'],
				],
			],
			'WHERE'     => $this->db->sql_in_set('t.topic_id', $topic_list),
			'ORDER_BY'  => 't.' . $sort_topics . ' DESC',
		];

		if ($this->config['rtng_parents'])
		{
			$sql_array['SELECT'] .= ', f.parent_id, f.forum_parents, f.left_id, f.right_id';
		}

		/**
		 * Event to modify the SQL query before the topics data is retrieved
		 *
		 * @event imcger.recenttopicsng.sql_pull_topics_data
		 * @var	array	sql_array	The SQL array
		 * @since 1.0.0
		 */
		$vars = ['sql_array'];
		extract($this->dispatcher->trigger_event('imcger.recenttopicsng.sql_pull_topics_data', compact($vars)));

		$sql    = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, $this->topics_per_page);

		$rowset = $this->db->sql_fetchrowset($result);

		$this->db->sql_freeresult($result);
		return $rowset;
	}

	/**
	 * Set template vars
	 */
	private function fill_template(array $icons, string $tpl_loopname, array $topic_tracking_info, int $topics_count, array $topic_list, int $total_topics_limit, int $rtng_start): void
	{
		// get topics from db
		$rowset = $this->get_topics_sql($topic_list);
		$topic_icons = [];

		// if topics returned by DB
		if (count($rowset))
		{
			/**
			 * Event to modify the topics list data before we start the display loop
			 *
			 * @event imcger.recenttopicsng.modify_topics_list
			 * @var	array	topic_list	Array of all the topic IDs
			 * @var	array	rowset		The full topics list array
			 * @since 1.0.0
			 */
			$vars = ['topic_list', 'rowset'];
			extract($this->dispatcher->trigger_event('imcger.recenttopicsng.modify_topics_list', compact($vars)));

			foreach ($rowset as $row)
			{
				$first_unread	= [];
				$topic_id		= $row['topic_id'];
				$forum_id		= $row['forum_id'];
				$unread_topic	= false;
				$replies		= $this->content_visibility->get_count('topic_posts', $row, $forum_id) - 1;
				$s_type_switch_test = ($row['topic_type'] == POST_ANNOUNCE || $row['topic_type'] == POST_GLOBAL) ? 1 : 0;
				$disp_topic_title	= $this->user_setting['user_rtng_disp_last_post'] ? 'last_post' : 'first_post';

				if ($this->user->data['is_registered'] && $this->config['load_db_lastread'] && $this->config['rtng_load_first_unrd_post'])
				{
					// Get author, posttime, id and title of first unread post in topic
					$sql_array = [
						'SELECT'	=> 'p.poster_id, u.username, u.user_colour, p.post_id, p.post_subject, p.post_time',
						'FROM'		=> [POSTS_TABLE => 'p',	],
						'LEFT_JOIN' => [
							[
								'FROM' => [TOPICS_TABLE => 't', ],
								'ON'   => "t.topic_id = p.topic_id",
							],
							[
								'FROM' => [TOPICS_TRACK_TABLE => 'tt', ],
								'ON'   => "tt.user_id = {$this->user->data['user_id']}
										AND tt.topic_id = p.topic_id",
							],
							[
								'FROM' => [FORUMS_TRACK_TABLE => 'ft', ],
								'ON'   => "ft.user_id = {$this->user->data['user_id']}
										AND ft.forum_id = t.forum_id",
							],
							[
								'FROM' => [USERS_TABLE => 'u', ],
								'ON'   => 'u.user_id = p.poster_id',
							],
						],
						'WHERE'		=> "p.topic_id = $topic_id
									AND p.post_time > COALESCE(tt.mark_time, ft.mark_time, {$this->user->data['user_lastmark']}, 0)
									AND p.forum_id = $forum_id",
						'ORDER_BY'	=> 'p.post_time ASC, p.post_id ASC',
					];

					$sql = $this->db->sql_build_query('SELECT', $sql_array);
					$result = $this->db->sql_query_limit($sql, 1);
					$first_unread = $this->db->sql_fetchrow($result);
					$this->db->sql_freeresult($result);

					$unread_topic = !empty($first_unread['post_id']);

					if ($this->user_setting['user_rtng_disp_first_unrd_post'] && $unread_topic)
					{
						$disp_topic_title = 'first_unread_post';

						$first_unread_post_author		= get_username_string('username', $first_unread['poster_id'], $first_unread['username'], $first_unread['user_colour']);
						$first_unread_post_author_color	= get_username_string('colour', $first_unread['poster_id'], $first_unread['username'], $first_unread['user_colour']);
						$first_unread_post_author_full	= get_username_string('full', $first_unread['poster_id'], $first_unread['username'], $first_unread['user_colour']);
						$first_unread_post_time			= $this->user->format_date($first_unread['post_time']);
						$u_first_unread_post_author		= get_username_string('profile', $first_unread['poster_id'], $first_unread['username'], $first_unread['user_colour']);
					}
				}
				else
				{
					$unread_topic = isset($topic_tracking_info[$forum_id][$row['topic_id']]) && ($row['topic_last_post_time'] > $topic_tracking_info[$forum_id][$row['topic_id']]);
				}

				$row['topic_first_unread_post_id']		 = $first_unread['post_id'] ?? '';
				$row['topic_first_unread_poster_id']	 = $first_unread['poster_id'] ?? '';
				$row['topic_first_unread_poster_name']	 = $first_unread['username'] ?? '';
				$row['topic_first_unread_poster_colour'] = $first_unread['user_colour'] ?? '';
				$row['topic_first_unread_post_subject']	 = $first_unread['post_subject'] ?? '';
				$row['topic_first_unread_post_time']	 = $first_unread['post_time'] ?? '';

				$view_topic_url				= append_sid("{$this->root_path}viewtopic.$this->phpEx", 't=' . $topic_id);
				$view_last_post_url			= append_sid("{$this->root_path}viewtopic.$this->phpEx", 'p=' . $row['topic_last_post_id'] . '#p' . $row['topic_last_post_id']);
				$view_first_unread_post_url	= !empty($first_unread['post_id']) ? append_sid("{$this->root_path}viewtopic.$this->phpEx", 'p=' . $first_unread['post_id'] . '#p' . $first_unread['post_id']) : '';
				$view_report_url			= append_sid("{$this->root_path}mcp.$this->phpEx", 'i=reports&amp;mode=reports&amp;t=' . $topic_id, true, $this->user->session_id);
				$view_forum_url				= append_sid("{$this->root_path}viewforum.$this->phpEx", 'f=' . $forum_id);
				$topic_unapproved			= ($row['topic_visibility'] == ITEM_UNAPPROVED && $this->auth->acl_get('m_approve', $forum_id));
				$posts_unapproved			= ($row['topic_visibility'] == ITEM_APPROVED && $row['topic_posts_unapproved'] && $this->auth->acl_get('m_approve', $forum_id));
				$u_mcp_queue				= ($topic_unapproved || $posts_unapproved) ? append_sid("{$this->root_path}mcp.$this->phpEx", 'i=queue&amp;mode=' . ($topic_unapproved ? 'approve_details' : 'unapproved_posts') . "&amp;t=$topic_id", true, $this->user->session_id) : '';
				$s_type_switch				= ($row['topic_type'] == POST_ANNOUNCE || $row['topic_type'] == POST_GLOBAL) ? 1 : 0;

				if (!empty($icons[$row['icon_id']]))
				{
					$topic_icons[] = $topic_id;
				}

				if ($this->user_setting['user_rtng_unread_only'] && $this->user->data['is_registered'])
				{
					$unread_topic = true;
				}

				// Get folder img, topic status/type related information
				$folder_img = $folder_alt = $topic_type = '';
				topic_status($row, $replies, $unread_topic, $folder_img, $folder_alt, $topic_type);

				list($topic_author, $topic_author_color, $topic_author_full, $u_topic_author, $last_post_author, $last_post_author_colour, $last_post_author_full, $u_last_post_author) = $this->getusernamestrings($row);

				//load language
				$this->language->add_lang('rtng_common', 'imcger/recenttopicsng');
				$tpl_ary = [
					'FORUM_ID'							=> $forum_id,
					'TOPIC_ID'							=> $topic_id,
					'TOPIC_AUTHOR'						=> $topic_author,
					'TOPIC_AUTHOR_COLOUR'				=> $topic_author_color,
					'TOPIC_AUTHOR_FULL'					=> $topic_author_full,
					'U_TOPIC_AUTHOR'					=> $u_topic_author,
					'FIRST_POST_TIME'					=> $this->user->format_date($row['topic_time']),
					'FIRST_UNREAD_POST_AUTHOR'			=> $first_unread_post_author ?? '',
					'FIRST_UNREAD_POST_AUTHOR_COLOUR'	=> $first_unread_post_author_color ?? '',
					'FIRST_UNREAD_POST_AUTHOR_FULL'		=> $first_unread_post_author_full ?? '',
					'U_FIRST_UNREAD_POST_AUTHOR'		=> $u_first_unread_post_author ?? '',
					'FIRST_UNREAD_POST_SUBJECT'			=> censor_text($row['topic_first_unread_post_subject']),
					'FIRST_UNREAD_POST_TIME'			=> $first_unread_post_time ?? '',
					'LAST_POST_SUBJECT'					=> censor_text($row['topic_last_post_subject']),
					'LAST_POST_TIME'					=> $this->user->format_date($row['topic_last_post_time']),
					'LAST_VIEW_TIME'					=> $this->user->format_date($row['topic_last_view_time']),
					'LAST_POST_AUTHOR'					=> $last_post_author,
					'LAST_POST_AUTHOR_COLOUR'			=> $last_post_author_colour,
					'LAST_POST_AUTHOR_FULL'				=> $last_post_author_full,
					'U_LAST_POST_AUTHOR'				=> $u_last_post_author,
					'REPLIES'							=> $replies,
					'VIEWS'								=> $row['topic_views'],
					'TOPIC_TITLE'						=> censor_text($row['topic_title']),
					'FORUM_NAME'						=> $row['forum_name'],
					'TOPIC_TYPE'						=> $topic_type,
					'TOPIC_IMG_STYLE'					=> $folder_img,
					'TOPIC_FOLDER_IMG'			=> $this->user->img($folder_img, $folder_alt),
					'TOPIC_FOLDER_IMG_ALT'		=> $this->language->lang($folder_alt),
					'TOPIC_ICON_IMG'			=> (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['img'] : '',
					'TOPIC_ICON_IMG_WIDTH'		=> (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['width'] : '',
					'TOPIC_ICON_IMG_HEIGHT'		=> (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['height'] : '',
					'ATTACH_ICON_IMG'			=> ($this->auth->acl_get('u_download') && $this->auth->acl_get('f_download', $forum_id) && $row['topic_attachment']) ? $this->user->img('icon_topic_attach', $this->language->lang('TOTAL_ATTACHMENTS')) : '',
					'UNAPPROVED_IMG'			=> ($topic_unapproved || $posts_unapproved) ? $this->user->img('icon_topic_unapproved', $topic_unapproved ? 'TOPIC_UNAPPROVED' : 'POSTS_UNAPPROVED') : '',
					'REPORTED_IMG'				=> ($row['topic_reported'] && $this->auth->acl_get('m_report', $forum_id)) ? $this->user->img('icon_topic_reported', 'TOPIC_REPORTED') : '',
					'S_HAS_POLL'				=> (bool) $row['poll_start'],
					'S_TOPIC_TYPE'				=> $row['topic_type'],
					'S_UNREAD_TOPIC'			=> $unread_topic,
					'S_DISP_FIRST_UNREAD_POST'	=> $disp_topic_title == 'first_unread_post',
					'S_DISP_LAST_POST'			=> $disp_topic_title == 'last_post',
					'S_DISP_FIRST_POST'			=> $disp_topic_title == 'first_post',
					'S_TOPIC_REPORTED'			=> $row['topic_reported'] && $this->auth->acl_get('m_report', $forum_id),
					'S_TOPIC_UNAPPROVED'		=> $topic_unapproved,
					'S_POSTS_UNAPPROVED'		=> $posts_unapproved,
					'S_POST_ANNOUNCE'			=> $row['topic_type'] == POST_ANNOUNCE,
					'S_POST_GLOBAL'				=> $row['topic_type'] == POST_GLOBAL,
					'S_POST_STICKY'				=> $row['topic_type'] == POST_STICKY,
					'S_TOPIC_LOCKED'			=> $row['topic_status'] == ITEM_LOCKED,
					'S_TOPIC_MOVED'				=> $row['topic_status'] == ITEM_MOVED,
					'S_TOPIC_TYPE_SWITCH'		=> ($s_type_switch == $s_type_switch_test) ? -1 : $s_type_switch_test,
					'U_NEWEST_POST'				=> $view_topic_url . '&amp;view=unread#unread',
					'U_LAST_POST'				=> $view_last_post_url,
					'U_FIRST_UNREAD_POST'		=> $view_first_unread_post_url,
					'U_VIEW_TOPIC'				=> $view_topic_url,
					'U_VIEW_FORUM'				=> $view_forum_url,
					'U_MCP_REPORT'				=> $view_report_url,
					'U_MCP_QUEUE'				=> $u_mcp_queue,
				];

				/**
				 * Modify the topic data before it is assigned to the template
				 *
				 * @event imcger.recenttopicsng.modify_tpl_ary
				 * @var	string  disp_topic_title	Post in Topic title. first, last or first unread post
				 * @var	array	row					Array with topic data
				 * @var	array	tpl_ary				Template block array with topic data
				 * @since 1.0.0
				 * @changed 1.1.0 Variables added. $disp_topic_title and properties of the first unread post in $row
				 */
				$vars = ['disp_topic_title', 'row', 'tpl_ary'];
				extract($this->dispatcher->trigger_event('imcger.recenttopicsng.modify_tpl_ary', compact($vars)));

				$this->template->assign_block_vars($tpl_loopname, $tpl_ary);
				$this->pagination->generate_template_pagination($view_topic_url, $tpl_loopname . '.pagination', 'start', $replies + 1, $this->config['posts_per_page'], 1, true, true);

				if ($this->config['rtng_parents'])
				{
					$forum_parents = get_forum_parents($row);
					foreach ($forum_parents as $parent_id => $data)
					{
						$this->template->assign_block_vars(
							$tpl_loopname . '.parent_forums', [
								'FORUM_ID'		=> $parent_id,
								'FORUM_NAME'	=> $data[0],
								'U_VIEW_FORUM'	=> append_sid("{$this->root_path}viewforum.$this->phpEx", 'f=' . $parent_id),
							]
						);
					}
				}
			} // end rowsset

			// Get URL-parameters for pagination
			$url_params		= explode('&', $this->user->page['query_string']);
			$append_params	= [];

			foreach ($url_params as $param)
			{
				if (!$param)
				{
					continue;
				}

				if (strpos($param, '=') === false)
				{
					// Fix MSSTI Advanced BBCode MOD
					$append_params[$param] = '1';
					continue;
				}

				list($name, $value) = explode('=', $param);

				if ($name != $tpl_loopname . '_start')
				{
					$append_params[$name] = $value;
				}
			}

			$pagination_url = append_sid($this->root_path . $this->user->page['page_name'], $append_params);
			$this->pagination->generate_template_pagination($pagination_url, 'pagination',
				$tpl_loopname . '_start', $topics_count, $this->topics_per_page, max(0, min((int) $rtng_start, $total_topics_limit)));

			$this->template->assign_vars([
				'S_RTNG_TOPIC_ICONS' => (bool) $topic_icons,
			]);
		} // topics found
	}
}
