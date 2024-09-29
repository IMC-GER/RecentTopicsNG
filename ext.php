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

namespace paybas\recenttopics;

/**
 * Extension class for custom enable/disable/purge actions
 */
class ext extends \phpbb\extension\base
{
	/**
	 * Check whether or not the extension can be enabled.
	 * The current phpBB version should meet or exceed
	 * the minimum version required by this extension:
	 *
	 * @return bool
	 * @access public
	 */
	public function is_enableable()
	{
		$valid_phpbb = phpbb_version_compare(PHPBB_VERSION, '3.3.5', '>=') && phpbb_version_compare(PHPBB_VERSION, '3.4.0', '<');
		$valid_php = phpbb_version_compare(PHP_VERSION, '7.1.3', '>=') && phpbb_version_compare(PHP_VERSION, '8.4.0', '<');

		return ($valid_phpbb && $valid_php);
	}
}
