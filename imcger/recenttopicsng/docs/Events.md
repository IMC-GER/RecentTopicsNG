imcger.recenttopicsng.modify_topics_list
===
* Description: Event to modify the topics list data before we start the display loop
* Placement: imcger\recenttopicsng\core\rtng_functions\display_recent_topics
* Since: 3.0.0.(2.0.1)
* known listeners:  /
* Arguments:
  - @var   array   topic_list     Array of all the topic IDs
  - @var   array   rowset         The full topics list array

imcger.recenttopicsng.modify_tpl_ary
===
* Description: Modify the topic data before it is assigned to the template
* Placement: imcger\recenttopicsng\core\rtng_functions\display_recent_topics
* Since: 3.0.0.(2.0.0)
* known listeners:  /
* Arguments:
  - @var   array   row            Array with topic data
  - @var   array   tpl_ary        Template block array with topic data

imcger.recenttopicsng.sql_pull_topics_list
===
* Description: Event to modify the SQL query before the allowed topics list data is retrieved
* Placement: imcger\recenttopicsng\core\rtng_functions\gettopiclist
* known listeners:  /
* Since: 3.0.0.(2.0.4)
* Arguments:
 - @var   array    sql_array      The SQL array
-----------
recenttopics_mchat_side
===
* Location: \ext\imcger\recenttopicsng\styles\all\template\event\index_body_markforums_after.html
* Purpose: Injection point for Mchat under Recent topics in Side mode.
* Since: 2.2.3

viewforum_forum_title_before
===
* Location: \ext\imcger\recenttopicsng\styles\all\template\rtng_body_separate.html
* Purpose: Add content directly before the forum title on the View forum screen
* Since: 2.2.15-pl9

viewforum_forum_title_after
===
* Location: \ext\imcger\recenttopicsng\styles\all\template\rtng_body_separate.html
* Purpose: Add content directly after the forum title on the View forum screen
* Since: 2.2.15-pl9

viewforum_body_topic_row_before
===
* Location: \ext\imcger\recenttopicsng\styles\all\template\rtng_body_separate.html
* Purpose: Add content before the topic list item.
* Since: 2.2.15-pl9

topiclist_row_prepend
===
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_side.html
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content into topic rows (inside the elements containing topic titles)
* Since: 2.2.0-rc2

topiclist_row_append
===
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_side.html
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content into topic rows (inside the elements containing topic titles)
* Since: 2.2.0-rc2

viewforum_body_last_post_author_username_prepend
===
* Location: 
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_side.html
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Prepend information to last post author username of member
* Since: 2.2.9

viewforum_body_last_post_author_username_append
===
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_side.html
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Append information to last post author username of member
* Since: 2.2.9
