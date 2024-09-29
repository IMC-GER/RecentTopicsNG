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

namespace paybas\recenttopics\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class acp_listener implements EventSubscriberInterface
{
	/** @var \phpbb\request\request */
	protected $request;

	/**
	 * acp_listener constructor.
	 *
	 * @param \phpbb\request\request				 $request
	 */
	public function __construct
	(
		\phpbb\request\request $request
	)
	{
		$this->request		= $request;
	}

	/**
	 * Get subscribed events
	 *
	 * @return array
	 * @static
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.acp_manage_forums_request_data'    => 'acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data' => 'acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form'    => 'acp_manage_forums_display_form',
		];
	}

	/**
	 * Submit form (add/update)
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function acp_manage_forums_request_data($event)
	{
		$array = $event['forum_data'];
		$array['forum_recent_topics'] = $this->request->variable('forum_recent_topics', 1);
		$event['forum_data'] = $array;
	}

	/**
	 * Default settings for new forums
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function acp_manage_forums_initialise_data($event)
	{
		if ($event['action'] == 'add')
		{
			$array = $event['forum_data'];
			$array['forum_recent_topics'] = '1';
			$event['forum_data'] = $array;
		}
	}

	/**
	 * ACP forums template output
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function acp_manage_forums_display_form($event)
	{
		$array = $event['template_data'];
		$array['RECENT_TOPICS'] = $event['forum_data']['forum_recent_topics'];
		$event['template_data'] = $array;
	}
}
