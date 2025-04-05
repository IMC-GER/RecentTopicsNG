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

var RecentTopicsNG = {};

(function ($) {	// IIFE start

'use strict';

class LukeWCSphpBBConfirmBox {
/*
* phpBB ConfirmBox class for checkboxes and yes/no radio buttons - v1.5.1
* @copyright (c) 2023, LukeWCS, https://www.wcsaga.org
* @license GNU General Public License, version 2 (GPL-2.0-only)
*/
	constructor(submitSelector, animDuration = 0) {
		let _this = this;
		this.$submitObject	= $(submitSelector);
		this.$formObject	= this.$submitObject.parents('form');
		this.animDuration	= animDuration;

		this.$formObject.find('div.lukewcs_confirmbox').each(function () {
			$('input[name="' + $(this).attr('data-name') + '"]').on('change', _this.#Show);
			$(this).find('input[type="button"]')				.on('click'	, _this.#Button);
		});
		this.$formObject										.on('reset'	, _this.HideAll);
	}

	#Show = (e) => {
		const $elementObject	= $('input[name="' + e.target.name + '"]');
		const $confirmBoxObject	= $('div.lukewcs_confirmbox[data-name="' + e.target.name + '"]');

		if ($elementObject.prop('checked') != $confirmBoxObject.attr('data-default')) {
			this.#changeBoxState($elementObject, $confirmBoxObject, true);
		}
	}

	#Button = (e) => {
		const elementName		= $(e.target).parents('div.lukewcs_confirmbox').attr('data-name');
		const $elementObject	= $('input[name="' + elementName + '"]');
		const $confirmBoxObject	= $('div.lukewcs_confirmbox[data-name="' + elementName + '"]');
		const elementType		= $elementObject.attr('type');

		if (e.target.name == 'lukewcs_confirmbox_no') {
			if (elementType == 'checkbox') {
				$elementObject.prop('checked', $confirmBoxObject.attr('data-default'));
			} else if (elementType == 'radio') {
				$elementObject.filter('[value="' + ($confirmBoxObject.attr('data-default') ? '1' : '0') + '"]').prop('checked', true);
			}
		}
		this.#changeBoxState($elementObject, $confirmBoxObject, null);
	}

	HideAll = () => {
		const $elementObject	= this.$formObject.find('input.lukewcs_confirmbox_active');
		const $confirmBoxObject	= this.$formObject.find('div.lukewcs_confirmbox');

		this.#changeBoxState($elementObject, $confirmBoxObject, false);
	}

	#changeBoxState = ($elementObject, $confirmBoxObject, showBox) => {
		$elementObject		.prop('disabled', !!showBox);
		$elementObject		.toggleClass('lukewcs_confirmbox_active', !!showBox);
		$confirmBoxObject	[showBox ? 'show' : 'hide'](this.animDuration);
		this.$submitObject	.prop('disabled', showBox ?? this.$formObject.find('input.lukewcs_confirmbox_active').length);
	}
}

// Register events

$(window).ready(function() {
	RecentTopicsNG.ConfirmBox = new LukeWCSphpBBConfirmBox('input[name="submit"]', 300);
});

})(jQuery);	// IIFE end
