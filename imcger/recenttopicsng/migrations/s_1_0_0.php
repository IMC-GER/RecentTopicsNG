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

namespace imcger\recenttopicsng\migrations;

class s_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists(USERS_TABLE, 'user_rtng_enable');
	}

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v33x\v335'];
	}

	public function update_schema()
	{
		return [
			'add_columns'	 => [
				USERS_TABLE => [
					'user_rtng_enable'					=> ['BOOL', 1],
					'user_rtng_sort_start_time'			=> ['BOOL', 0],
					'user_rtng_unread_only'				=> ['BOOL', 0],
					'user_rtng_disp_last_post'			=> ['BOOL', 0],
					'user_rtng_disp_first_unrd_post'	=> ['BOOL', 0],
					'user_rtng_location'				=> ['VCHAR:15', 'RTNG_TOP'],
					'user_rtng_index_topics_qty'		=> ['UINT', 5],
					'user_rtng_index_page_qty'			=> ['UINT', 3],
					'user_rtng_separate_topics_qty'		=> ['UINT', 10],
					'user_rtng_separate_page_qty'		=> ['UINT', 3],
				],

				FORUMS_TABLE => [
					'forum_rtng_disp' => ['BOOL', 1],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'	  => [
				USERS_TABLE => [
					'user_rtng_enable',
					'user_rtng_sort_start_time',
					'user_rtng_unread_only',
					'user_rtng_disp_last_post',
					'user_rtng_disp_first_unrd_post',
					'user_rtng_location',
					'user_rtng_index_topics_qty',
					'user_rtng_index_page_qty',
					'user_rtng_separate_topics_qty',
					'user_rtng_separate_page_qty',
				],

				FORUMS_TABLE => [
					'forum_rtng_disp',
				],
			],
		];
	}
}
