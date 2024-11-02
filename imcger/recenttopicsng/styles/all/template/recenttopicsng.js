/**
 *
 * Recent Topics. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, IMC, https://github.com/IMC-GER / LukeWCS, https://github.com/LukeWCS
 * @copyright (c) 2017, Sajaki, https://www.avathar.be
 * @copyright (c) 2015, PayBas
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

jQuery(
	function($) {
		var phpbbCallback = phpbb.ajaxCallbacks['mark_forums_read'];
		phpbb.addAjaxCallback(
			'mark_forums_read', function(res) {
				phpbbCallback(res);
				phpbb.ajaxCallbacks.mark_topics_read.call(this, res, false);
			}
		);
	}
);
