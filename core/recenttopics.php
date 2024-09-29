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

namespace paybas\recenttopics\core;

/**
 * Class recenttopics
 *
 * @package paybas\recenttopics\core
 */
class recenttopics
{
	/**
	 * @var auth
	 */
	protected $auth;

	/**
	 * @var config
	 */
	protected $config;

	/**
	 * @var language
	 */
	protected $language;

	/**
	 * @var \phpbb\cache\service
	 */
	protected $cache;

	/**
	 * @var content_visibility
	 */
	protected $content_visibility;

	/**
	 * @var driver_interface
	 */
	protected $db;

	/**
	 * @var dispatcher_interface
	 */
	protected $dispatcher;

	/**
	 * @var pagination
	 */
	protected $pagination;

	/**
	 * @var request_interface
	 */
	protected $request;

	/**
	 * @var template
	 */
	protected $template;

	/**
	 * @var \phpbb\user
	 */
	protected $user;

	/**
	 * @var string phpBB root path
	 */
	protected $root_path;

	/**
	 * @var string PHP extension
	 */
	protected $phpEx;

	/**
	 * array of allowable forum id's
	 *
	 * @var array
	 */
	private $forum_ids;

	/**
	 * array of topics to show
	 *
	 * @var array
	 */
	private $topic_list;

	/**
	 * @var int
	 */
	private $unread_only;

	/**
	 * show a forum icon ?
	 *
	 * @var boolean
	 */
	private $obtain_icons;

	/**
	 * forum objects we need
	 *
	 * @var array
	 */
	private $forums;

	/**
	 * @var Collapsable
	 */
	private $collapsable_categories;

	/**
	 * @var int
	 */
	private $rtng_start;

	/**
	 * @var int
	 */
	public $topics_per_page;

	/**
	 * @var int
	 */
	private $total_topics_limit;

	/**
	 * @var int
	 */
	private $sort_topics;

	/**
	 * @var int
	 */
	private $display_parent_forums;

	/**
	 * Block location
	 *
	 * @var string
	 */
	private $location;

	/**
	 * @var int
	 */
	private $icons;

	/**
	 * @var string
	 */
	private $excluded_topics;

	/**
	 * @var int
	 */
	public $topics_page_number;

	/**
	 * recenttopics constructor.
	 *
	 * @param \phpbb\auth\auth                                    $auth
	 * @param \phpbb\cache\service                                $cache
	 * @param \phpbb\config\config                                $config
	 * @param \phpbb\language\language                            $language
	 * @param \phpbb\content_visibility                           $content_visibility
	 * @param \phpbb\db\driver\driver_interface                   $db
	 * @param \phpbb\event\dispatcher_interface                   $dispatcher
	 * @param \phpbb\pagination                                   $pagination
	 * @param \phpbb\request\request_interface                    $request
	 * @param \phpbb\template\template                            $template
	 * @param \phpbb\user                                         $user
	 * @param                                                     $root_path
	 * @param                                                     $phpEx
	 * @param \phpbb\collapsiblecategories\operator\operator|NULL $collapsable_categories
	 */
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
		?\phpbb\collapsiblecategories\operator\operator $collapsable_categories = null
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
		$this->collapsable_categories = $collapsable_categories;

		$this->topics_per_page		= 0;
		$this->total_topics_limit	= 0;
		$this->topics_page_number	= 0;
	}

	/**
	 * @param string $tpl_loopname
	 */
	public function display_recent_topics($tpl_loopname = 'rtng'): void
	{
		// can view rtng ?
		if ($this->auth->acl_get('u_rt_view') == '0')
		{
			return;
		}

		// if user can enable recent topics and it is not enabled then return
		if ($this->auth->acl_get('u_rt_enable') && !$this->user->data['user_rt_enable'])
		{
			return;
		}

		if (!function_exists('topic_status'))
		{
			include($this->root_path . 'includes/functions_display.' . $this->phpEx);
		}

		// support for phpbb collapsable categories extension
		if (isset($this->collapsable_categories))
		{
			$fid = 'fid_rtng'; // can be any unique string to identify the collapsible element of your extension.
			$this->template->assign_vars([
				'S_EXT_COLCAT_HIDDEN'       => $this->collapsable_categories->is_collapsed($fid),
				'U_EXT_COLCAT_COLLAPSE_URL' => $this->collapsable_categories->get_collapsible_link($fid),
			]);
		}

		//display parent forums
		$this->display_parent_forums = $this->config['rt_parents'];

		//rt block location
		$this->location = $this->config['rt_location'];

		// if user can set location and it is set then use the preference
		if ($this->auth->acl_get('u_rt_location'))
		{
			$this->location = $this->user->data['user_rt_location'];
		}

		$this->unread_only = $this->config['rt_unread_only'];
		if ($this->auth->acl_get('u_rt_unread_only'))
		{
			$this->unread_only = $this->user->data['user_rt_unread_only'];
		}

		$this->rtng_start = $this->request->variable($tpl_loopname . '_start', 0);

		// set # topics shown per page
		if ($this->topics_per_page == 0)
		{
			$this->topics_per_page = (int) $this->config['rt_number'];
			if ($this->auth->acl_get('u_rt_number'))
			{
				$this->topics_per_page = (int) $this->user->data['user_rt_number'];
			}
		}

		$this->excluded_topics = explode(',', $this->config['rt_anti_topics']);
		$min_topic_level = $this->config['rt_min_topic_level'];

		$this->getforumlist();

		// No forums to display
		if (count($this->forum_ids) == 0)
		{
			return;
		}

		// limit number of pages to be shown
		// compute as product of topics per page and max number of pages.
		if ($this->topics_page_number)
		{
			// Set in page controller
			$this->total_topics_limit = $this->topics_per_page * $this->topics_page_number;
		}
		else if ((int) $this->config['rt_page_number'] == 0)
		{
			$this->total_topics_limit = $this->topics_per_page * (int) $this->config['rt_page_numbermax'];
		}
		else
		{
			$sql_array = $this->get_allowed_topics_sql($this->excluded_topics, $min_topic_level);
			$count_sql_array = $sql_array;
			$count_sql_array['SELECT'] = 'COUNT(t.topic_id) as topic_count';
			unset($count_sql_array['ORDER_BY']);
			$sql = $this->db->sql_build_query('SELECT', $count_sql_array);

			$result = $this->db->sql_query($sql);
			$this->total_topics_limit = (int) $this->db->sql_fetchfield('topic_count');
			$this->db->sql_freeresult($result);
		}

		$this->sort_topics = $this->config['rt_sort_start_time'] ? 'topic_time' : 'topic_last_post_time';

		// if user can set recent topic sorting order and it is set then use the preference
		if ($this->auth->acl_get('u_rt_sort_start_time'))
		{
			$this->sort_topics = $this->user->data['user_rt_sort_start_time'] ? 'topic_time' : 'topic_last_post_time';
		}

		$topics_count = $this->gettopiclist();

		if (count($this->topic_list) == 0)
		{
			return;
		}
		// If topics to display

		// Grab icons
		$this->icons = [];
		if ($this->obtain_icons)
		{
			$this->icons = $this->cache->obtain_icons();
		}

		// Borrowed from search.php
		$topic_tracking_info = [];
		foreach ($this->forums as $forum_id => $forum)
		{
			if ($this->user->data['is_registered'] && $this->config['load_db_lastread'])
			{
				$topic_tracking_info[$forum_id] = get_topic_tracking($forum_id, $forum['topic_list'], $forum['rowset'], [$forum_id => $forum['mark_time']], $forum_id ? false : $forum['topic_list']);
			}
			else if ($this->config['load_anon_lastread'] || $this->user->data['is_registered'])
			{
				$tracking_topics = $this->request->variable($this->config['cookie_name'] . '_track', '', true, \phpbb\request\request_interface::COOKIE);
				$tracking_topics = $tracking_topics ? tracking_unserialize($tracking_topics) : [];

				$topic_tracking_info[$forum_id] = get_complete_topic_tracking($forum_id, $forum['topic_list'], $forum_id ? false : $forum['topic_list']);

				if (!$this->user->data['is_registered'])
				{
					if (isset($tracking_topics['l']))
					{
						$this->user->data['user_lastmark'] =  ( (int) base_convert($tracking_topics['l'], 36, 10) + (int) $this->config['board_startdate']);
					}
					else
					{
						$this->user->data['user_lastmark'] = 0;
					}
				}
			}
		}

		//load language
		$this->language->add_lang('recenttopics', 'paybas/recenttopics');
		$this->template->assign_vars([
				'RTNG_TOPICS_COUNT'		 => $this->language->lang('RTNG_TOPICS_COUNT', (int) $topics_count),
				'RTNG_SORT_START_TIME'	 => $this->sort_topics === 'topic_time',
				'S_RTNG_LOCATION_TOP'	 => $this->location == 'RT_TOP',
				'S_RTNG_LOCATION_BOTTOM' => $this->location == 'RT_BOTTOM',
				'S_RTNG_LOCATION_SIDE'	 => $this->location == 'RT_SIDE',
				strtoupper($tpl_loopname) . '_DISPLAY' => true,
			]
		);

		$this->fill_template($tpl_loopname, $topic_tracking_info, $topics_count);
	}

	/**
	 * Get the forums we take our topics from
	 */
	private function getforumlist(): void
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

		$this->forum_ids = array_unique($forum_ary);

		if (count($this->forum_ids) > 1)
		{
			$sql = 'SELECT forum_id
					FROM ' . FORUMS_TABLE . '
					WHERE ' . $this->db->sql_in_set('forum_id', $this->forum_ids) . '
						AND forum_recent_topics = 1';

			$result = $this->db->sql_query($sql);

			$this->forum_ids = [];

			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->forum_ids[] = $row['forum_id'];
			}

			$this->db->sql_freeresult($result);
			$this->forum_ids = array_unique($this->forum_ids);

		}
	}

	/**
	 * Get the topic list
	 *
	 * @return int
	 */
	private function gettopiclist(): int
	{
		$this->rtng_start = max(0, $this->rtng_start);

		if ($this->total_topics_limit > 0)
		{
			$this->rtng_start = min((int) $this->rtng_start, $this->total_topics_limit);
		}

		$this->forums = $this->topic_list = [];
		$topics_count = 0;
		$this->obtain_icons = false;

		$min_topic_level = $this->config['rt_min_topic_level'];

		// Either use the phpBB core function to get unread topics, or the custom function for default behavior
		if ($this->unread_only && $this->user->data['user_id'] != ANONYMOUS)
		{
			// Get unread topics
			$sql_extra	   = ' AND ' . $this->db->sql_in_set('t.topic_id', $this->excluded_topics, true);
			$sql_extra	  .= ' AND ' . $this->content_visibility->get_forums_visibility_sql('topic', $this->forum_ids, $table_alias = 't.');
			$unread_topics = get_unread_topics(false, $sql_extra, '', $this->total_topics_limit);
			$this->rtng_start = min(count($unread_topics) - 1 , (int) $this->rtng_start);

			foreach ($unread_topics as $topic_id => $mark_time)
			{
				$topics_count++;

				if (($topics_count > $this->rtng_start) && ($topics_count <= ($this->rtng_start + $this->topics_per_page)))
				{
					$this->topic_list[] = $topic_id;
				}
			}
		}
		else
		{
			// Get allowed topics
			$sql_array = $this->get_allowed_topics_sql($this->excluded_topics, $min_topic_level);
			$count_sql_array = $sql_array;
			$count_sql_array['SELECT'] = 'COUNT(t.topic_id) as topic_count';
			unset($count_sql_array['ORDER_BY']);
			$sql = $this->db->sql_build_query('SELECT', $count_sql_array);

			$result = $this->db->sql_query($sql);
			$num_rows = (int) $this->db->sql_fetchfield('topic_count');
			$this->db->sql_freeresult($result);

			//load topics list
			$sql = $this->db->sql_build_query('SELECT', $sql_array);

			if ($this->total_topics_limit > 0)
			{
				$result = $this->db->sql_query_limit($sql, $this->total_topics_limit);
			}
			else
			{
				$result = $this->db->sql_query($sql);
			}

			if ($result != null)
			{
				$this->rtng_start = min($num_rows - 1 , $this->rtng_start);
			}
			else
			{
				$this->rtng_start = 0;
			}

			while ($row = $this->db->sql_fetchrow($result))
			{
				$topics_count++;

				if (($topics_count > $this->rtng_start) && ($topics_count <= ($this->rtng_start + $this->topics_per_page)))
				{
					$rowset = [];

					$this->topic_list[] = $row['topic_id'];

					$rowset[$row['topic_id']] = $row;

					if (!isset($this->forums[$row['forum_id']]) && $this->user->data['is_registered'] && $this->config['load_db_lastread'])
					{
						$this->forums[$row['forum_id']]['mark_time'] = $row['f_mark_time'];
					}
					$this->forums[$row['forum_id']]['topic_list'][] = $row['topic_id'];
					$this->forums[$row['forum_id']]['rowset'][$row['topic_id']] = & $rowset[$row['topic_id']];

					if ($row['icon_id'])
					{
						$this->obtain_icons = true;
					}
				}
			}
			$this->db->sql_freeresult($result);
		}
		return $topics_count;
	}

	/**
	 * custom function to get allowed topics
	 * used for anon access or when unread topics is not requested
	 * @param $excluded_topics
	 * @param $min_topic_level
	 * @return array
	 */
	private function get_allowed_topics_sql($excluded_topics, $min_topic_level): array
	{
		// Get the allowed topics
		$sql_array = [
			'SELECT'    => 't.forum_id, t.topic_id, t.topic_type, t.icon_id, tp.topic_posted, tt.mark_time, ft.mark_time as f_mark_time, t.' . $this->sort_topics . ' as sortcr ',
			'FROM'      => [TOPICS_TABLE => 't'],
			'LEFT_JOIN' => [
				[
					'FROM' => [TOPICS_TRACK_TABLE => 'tt'],
					'ON'   => 'tt.topic_id = t.topic_id AND tt.user_id = ' . (int) $this->user->data['user_id'],
				],
				[
					'FROM' => [FORUMS_TRACK_TABLE => 'ft'],
					'ON'   => 'ft.forum_id = t.forum_id AND ft.user_id = ' . (int) $this->user->data['user_id'],
				],
				[
					'FROM' => [TOPICS_POSTED_TABLE => 'tp'],
					'ON' => 'tp.topic_id = t.topic_id AND tp.user_id = ' . (int) $this->user->data['user_id'],
				],
			],
			'WHERE'     => $this->db->sql_in_set('t.topic_id', $excluded_topics, true) . '
					AND t.topic_status <> ' . ITEM_MOVED . '
					AND ' . $this->content_visibility->get_forums_visibility_sql('topic', $this->forum_ids, $table_alias = 't.'),
			'ORDER_BY'  => 't.' . $this->sort_topics . ' DESC',
		];

		// Check if we want all topics, or only stickies/announcements/globals
		if ($min_topic_level > 0)
		{
			$sql_array['WHERE'] .= ' AND t.topic_type >= ' . (int) $min_topic_level;
		}

		/**
		 * Event to modify the SQL query before the allowed topics list data is retrieved
		 *
		 * @event paybas.recenttopics.sql_pull_topics_list
		 * @var	array	sql_array	The SQL array
		 * @since 2.0.4
		 */
		$vars = ['sql_array'];
		extract($this->dispatcher->trigger_event('paybas.recenttopics.sql_pull_topics_list', compact($vars)));

		return $sql_array;
	}

	/**
	 * @param $row
	 * @return array
	 */
	private function getusernamestrings($row): array
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
	 * pull the data of the requested topics
	 * @return array
	 */
	private function get_topics_sql (): array
	{
		$sql_array = [
			'SELECT'    => 't.*, f.forum_name, tp.topic_posted',
			'FROM'      => [TOPICS_TABLE => 't'],
			'LEFT_JOIN' => [
				[
					'FROM' => [FORUMS_TABLE => 'f'],
					'ON'   => 'f.forum_id = t.forum_id',
				],
				[
					'FROM' => [TOPICS_POSTED_TABLE => 'tp'],
					'ON' => 'tp.topic_id = t.topic_id AND tp.user_id = ' . (int) $this->user->data['user_id'],
				],
			],
			'WHERE'     => $this->db->sql_in_set('t.topic_id', $this->topic_list),
			'ORDER_BY'  => 't.' . $this->sort_topics . ' DESC',
		];

		if ($this->display_parent_forums)
		{
			$sql_array['SELECT'] .= ', f.parent_id, f.forum_parents, f.left_id, f.right_id';
		}

		/**
		 * Event to modify the SQL query before the topics data is retrieved
		 *
		 * @event paybas.recenttopics.sql_pull_topics_data
		 * @var	array	sql_array	The SQL array
		 * @since 2.0.0
		 */
		$vars = ['sql_array'];
		extract($this->dispatcher->trigger_event('paybas.recenttopics.sql_pull_topics_data', compact($vars)));

		$sql    = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, $this->topics_per_page);
		$rowset = [];

		while ($row = $this->db->sql_fetchrow($result))
		{
			$rowset[] = $row;
		}

		$this->db->sql_freeresult($result);
		return $rowset;
	}

	/**
	 * @param       $tpl_loopname
	 * @param       $topic_tracking_info
	 * @param int   $topics_count
	 */
	private function fill_template ($tpl_loopname, $topic_tracking_info, int $topics_count): void
	{
		// get topics from db
		$rowset = $this->get_topics_sql();
		$topic_icons = [];

		// if topics returned by DB
		if (count($rowset))
		{
			$topic_list = $this->topic_list;

			/**
			 * Event to modify the topics list data before we start the display loop
			 *
			 * @event paybas.recenttopics.modify_topics_list
			 * @var	array	topic_list	Array of all the topic IDs
			 * @var	array	rowset		The full topics list array
			 * @since 2.0.1
			 */
			$vars = ['topic_list', 'rowset'];
			extract($this->dispatcher->trigger_event('paybas.recenttopics.modify_topics_list', compact($vars)));

			$this->topic_list = $topic_list;

			foreach ($rowset as $row)
			{
				$topic_id = $row['topic_id'];
				$forum_id = $row['forum_id'];
				$s_type_switch_test = ($row['topic_type'] == POST_ANNOUNCE || $row['topic_type'] == POST_GLOBAL) ? 1 : 0;
				$replies            = $this->content_visibility->get_count('topic_posts', $row, $forum_id) - 1;

				if ($row['topic_status'] == ITEM_MOVED)
				{
					$topic_id = $row['topic_moved_id'];
					$unread_topic = false;
				}
				else
				{
					$unread_topic = (isset($topic_tracking_info[$forum_id][$row['topic_id']]) && $row['topic_last_post_time'] > $topic_tracking_info[$forum_id][$row['topic_id']]) ? true : false;
				}

				// Get folder img, topic status/type related information
				$folder_img = $folder_alt = $topic_type = '';

				if ($this->unread_only)
				{
					topic_status($row, $replies, true, $folder_img, $folder_alt, $topic_type);
					$unread_topic = ($this->user->data['user_id'] != ANONYMOUS) ?? false;
				}
				else
				{
					if (isset($topic_tracking_info[$forum_id][$row['topic_id']]) && $row['topic_last_post_time'] > $topic_tracking_info[$forum_id][$row['topic_id']])
					{
						topic_status($row, $replies, true, $folder_img, $folder_alt, $topic_type);
					}
					else
					{
						topic_status($row, $replies, false, $folder_img, $folder_alt, $topic_type);
					}

					if (isset($topic_tracking_info[$forum_id][$row['topic_id']]) && $row['topic_last_post_time'] > $topic_tracking_info[$forum_id][$row['topic_id']])
					{
						$unread_topic = true;
					}
					else
					{
						$unread_topic = false;
					}
				}

				$first_unread = [];

				if ($unread_topic)
				{
					// Get id and title of first unread post in topic
					$sql_array = [
						'SELECT'	=> 'p.post_id, p.post_subject',
						'FROM'		=> [
								POSTS_TABLE => 'p',
						],
						'LEFT_JOIN' => [
							[
								'FROM' => [TOPICS_TRACK_TABLE => 'tt'],
								'ON'   => "tt.user_id = {$this->user->data['user_id']}
										AND tt.topic_id = $topic_id",
							],
						],
						'WHERE'		=> "p.topic_id = $topic_id
								AND p.post_time > COALESCE(tt.mark_time, 0)",
						'ORDER_BY'	=> 'p.post_time ASC, p.post_id ASC',
					];
					$sql = $this->db->sql_build_query('SELECT', $sql_array);
					$result = $this->db->sql_query_limit($sql, 1);
					$first_unread = $this->db->sql_fetchrow($result);
					$this->db->sql_freeresult($result);
				}

				$view_topic_url				= append_sid("{$this->root_path}viewtopic.$this->phpEx", 't=' . $topic_id);
				$view_last_post_url			= append_sid("{$this->root_path}viewtopic.$this->phpEx", 'p=' . $row['topic_last_post_id'] . '#p' . $row['topic_last_post_id']);
				$view_first_unread_post_url	= !empty($first_unread['post_id']) ? append_sid("{$this->root_path}viewtopic.$this->phpEx", 'p=' . $first_unread['post_id'] . '#p' . $first_unread['post_id']) : '';
				$view_report_url			= append_sid("{$this->root_path}mcp.$this->phpEx", 'i=reports&amp;mode=reports&amp;t=' . $topic_id, true, $this->user->session_id);
				$view_forum_url				= append_sid("{$this->root_path}viewforum.$this->phpEx", 'f=' . $forum_id);
				$topic_unapproved	= ($row['topic_visibility'] == ITEM_UNAPPROVED && $this->auth->acl_get('m_approve', $forum_id));
				$posts_unapproved	= ($row['topic_visibility'] == ITEM_APPROVED && $row['topic_posts_unapproved'] && $this->auth->acl_get('m_approve', $forum_id));
				$u_mcp_queue		= ($topic_unapproved || $posts_unapproved) ? append_sid("{$this->root_path}mcp.$this->phpEx", 'i=queue&amp;mode=' . ($topic_unapproved ? 'approve_details' : 'unapproved_posts') . "&amp;t=$topic_id", true, $this->user->session_id) : '';
				$s_type_switch		= ($row['topic_type'] == POST_ANNOUNCE || $row['topic_type'] == POST_GLOBAL) ? 1 : 0;

				if (!empty($this->icons[$row['icon_id']]))
				{
					$topic_icons[] = $topic_id;
				}

				topic_status($row, $replies, $unread_topic, $folder_img, $folder_alt, $topic_type);

				list($topic_author, $topic_author_color, $topic_author_full, $u_topic_author, $last_post_author, $last_post_author_colour, $last_post_author_full, $u_last_post_author) = $this->getusernamestrings($row);

				//load language
				$this->language->add_lang('recenttopics', 'paybas/recenttopics');
				$tpl_ary = [
					'FORUM_ID'					=> $forum_id,
					'TOPIC_ID'					=> $topic_id,
					'TOPIC_AUTHOR'				=> $topic_author,
					'TOPIC_AUTHOR_COLOUR'		=> $topic_author_color,
					'TOPIC_AUTHOR_FULL'			=> $topic_author_full,
					'U_TOPIC_AUTHOR'			=> $u_topic_author,
					'FIRST_POST_TIME'			=> $this->user->format_date($row['topic_time']),
					'FIRST_UNREAD_POST_SUBJECT'	=> censor_text(!empty($first_unread['post_subject']) ? $first_unread['post_subject'] : ''),
					'LAST_POST_SUBJECT'			=> censor_text($row['topic_last_post_subject']),
					'LAST_POST_TIME'			=> $this->user->format_date($row['topic_last_post_time']),
					'LAST_VIEW_TIME'			=> $this->user->format_date($row['topic_last_view_time']),
					'LAST_POST_AUTHOR'			=> $last_post_author,
					'LAST_POST_AUTHOR_COLOUR'	=> $last_post_author_colour,
					'LAST_POST_AUTHOR_FULL'		=> $last_post_author_full,
					'U_LAST_POST_AUTHOR'		=> $u_last_post_author,
					'REPLIES'					=> $replies,
					'VIEWS'						=> $row['topic_views'],
					'TOPIC_TITLE'				=> censor_text($row['topic_title']),
					'FORUM_NAME'				=> $row['forum_name'],
					'TOPIC_TYPE'				=> $topic_type,
					'TOPIC_IMG_STYLE'			=> $folder_img,
					'TOPIC_FOLDER_IMG'			=> $this->user->img($folder_img, $folder_alt),
					'TOPIC_FOLDER_IMG_ALT'		=> $this->language->lang($folder_alt),
					'TOPIC_ICON_IMG'			=> (!empty($this->icons[$row['icon_id']])) ? $this->icons[$row['icon_id']]['img'] : '',
					'TOPIC_ICON_IMG_WIDTH'		=> (!empty($this->icons[$row['icon_id']])) ? $this->icons[$row['icon_id']]['width'] : '',
					'TOPIC_ICON_IMG_HEIGHT'		=> (!empty($this->icons[$row['icon_id']])) ? $this->icons[$row['icon_id']]['height'] : '',
					'ATTACH_ICON_IMG'			=> ($this->auth->acl_get('u_download') && $this->auth->acl_get('f_download', $forum_id) && $row['topic_attachment']) ? $this->user->img('icon_topic_attach', $this->language->lang('TOTAL_ATTACHMENTS')) : '',
					'UNAPPROVED_IMG'			=> ($topic_unapproved || $posts_unapproved) ? $this->user->img('icon_topic_unapproved', $topic_unapproved ? 'TOPIC_UNAPPROVED' : 'POSTS_UNAPPROVED') : '',
					'REPORTED_IMG'				=> ($row['topic_reported'] && $this->auth->acl_get('m_report', $forum_id)) ? $this->user->img('icon_topic_reported', 'TOPIC_REPORTED') : '',
					'S_HAS_POLL'				=> $row['poll_start'] ? true : false,
					'S_TOPIC_TYPE'				=> $row['topic_type'],
					'S_UNREAD_TOPIC'			=> $unread_topic,
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
				 * @event paybas.recenttopics.modify_tpl_ary
				 * @var	array	row		Array with topic data
				 * @var	array	tpl_ary	Template block array with topic data
				 * @since 2.0.0
				 */
				$vars = ['row', 'tpl_ary'];
				extract($this->dispatcher->trigger_event('paybas.recenttopics.modify_tpl_ary', compact($vars)));

				$this->template->assign_block_vars($tpl_loopname, $tpl_ary);
				$this->pagination->generate_template_pagination($view_topic_url, $tpl_loopname . '.pagination', 'start', $replies + 1, $this->config['posts_per_page'], 1, true, true);

				if ($this->display_parent_forums)
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
				$tpl_loopname . '_start', $topics_count, $this->topics_per_page, max(0, min((int) $this->rtng_start, $this->total_topics_limit)));

			$this->template->assign_vars([
				'S_RTNG_TOPIC_ICONS' => count($topic_icons) ? true : false,
			]);
		} // topics found
	}
}
