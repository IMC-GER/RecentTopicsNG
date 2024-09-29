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
 * Class recenttopics_info
 *
 * @package paybas\recenttopics\acp
 */
class recenttopics_info
{
	/**
	 * @return array
	 */
	public function module()
	{
		return [
			'filename'	=> '\paybas\recenttopics\acp\recenttopics_module',
			'title'		=> 'RECENT_TOPICS',
			'modes'		=> [
				'recenttopics_config' => [
					'title'	=> 'RT_CONFIG',
					'auth'	=> 'ext_paybas/recenttopics && acl_a_board',
					'cat'	=> ['RECENT_TOPICS', ],
				],
			]
		];
	}
}
