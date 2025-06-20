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

class acp_rtng_info
{
	public function module(): array
	{
		return [
			'filename'	=> '\imcger\recenttopicsng\acp\acp_rtng_module',
			'title'		=> 'RTNG_NAME',
			'modes'		=> [
				'settings' => [
					'title'	=> 'RTNG_CONFIG',
					'auth'	=> 'ext_imcger/recenttopicsng && acl_a_board',
					'cat'	=> ['RTNG_NAME', ],
				],
			]
		];
	}
}
