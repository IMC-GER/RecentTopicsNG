---
## Core Events
---

### `imcger.recenttopicsng.sql_pull_topics_list`
* Description: Event to modify the SQL query before the allowed topics list data is retrieved
* Placement: imcger\recenttopicsng\core\rtng_functions\get_allowed_topics_sql
* Since: 1.0.0
* known listeners:  /
* Arguments:
  - @var   array	sql_array	The SQL array

### `imcger.recenttopicsng.sql_pull_topics_data`
* Description: Event to modify the SQL query before the topics data is retrieved
* Placement: imcger\recenttopicsng\core\rtng_functions\get_topics_sql
* Since: 1.0.0
* known listeners:
  - First & Last avatar (caforum/firstlastavatar)
  - Topic Preview (vse/topicpreview)
* Arguments:
  - @var   array	sql_array	The SQL array

### `imcger.recenttopicsng.modify_topics_list`
* Description: Event to modify the topics list data before we start the display loop
* Placement: imcger\recenttopicsng\core\rtng_functions\fill_template
* Since: 1.0.0
* known listeners:
  - Topic Preview (vse/topicpreview)
* Arguments:
  - @var   array   topic_list     Array of all the topic IDs
  - @var   array   rowset         The full topics list array

### `imcger.recenttopicsng.modify_tpl_ary`
* Description: Modify the topic data before it is assigned to the template
* Placement: imcger\recenttopicsng\core\rtng_functions\fill_template
* Since:   1.0.0
* Changed: 1.1.0 Variables added. $disp_topic_title and properties of the first unread post in $row
* @changed 1.2.0 Variables added. s_type_switch and s_type_switch_test
* known listeners:
  - First & Last avatar (caforum/firstlastavatar)
  - Topic Preview (vse/topicpreview)
* Arguments:
  - @var   string  disp_topic_title Post in Topic title. first, last or first unread post
  - @var   array   row                 Array with topic data
  - @var   array   tpl_ary             Template block array with topic data
  - @var   bool    s_type_switch       Flag indicating if the topic type is [global] announcement
  - @var   bool    s_type_switch_test  Flag indicating if the test topic type is [global] announcement

### `imcger.recenttopicsng.topic_row_after`
* Description: Event after the recent topic data has been assigned to the template
* Placement: imcger\recenttopicsng\core\rtng_functions\fill_template
* @since 1.2.0
* known listeners:  /
* Arguments:
  - @var   array   row            Array with the topic data
  - @var   array   rowset         Array with topics data
  - @var   bool    s_type_switch  Flag indicating if the topic type is [global] announcement
  - @var   int     topic_id       The topic ID
  - @var   array   topic_list     Array with current recent topics page topic ids
  - @var   array   tpl_ary        Template array with topic data

---
## Template Events
---

### `recenttopics_mchat_side`
* Location: \ext\imcger\recenttopicsng\styles\all\template\event\index_body_markforums_after.html
* Purpose: Injection point for Mchat under Recent topics in Side mode.
* Since: 1.0.0

### `viewforum_forum_title_before`
* Location: \ext\imcger\recenttopicsng\styles\all\template\rtng_body_separate.html
* Purpose: Add content directly before the forum title on the View forum screen
* Since: 1.0.0

### `viewforum_forum_title_after`
* Location: \ext\imcger\recenttopicsng\styles\all\template\rtng_body_separate.html
* Purpose: Add content directly after the forum title on the View forum screen
* Since: 1.0.0

### `viewforum_body_topic_row_before`
* Location: \ext\imcger\recenttopicsng\styles\all\template\rtng_body_separate.html
* Purpose: Add content before the topic list item.
* Since: 1.0.0

### `topiclist_row_prepend`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_side.html
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content into topic rows (inside the elements containing topic titles)
* Since: 1.0.0

### `topiclist_row_append`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_side.html
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content into topic rows (inside the elements containing topic titles)
* Since: 1.0.0

### `viewforum_body_last_post_author_username_prepend`
* Location: 
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_side.html
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Prepend information to last post author username of member
* Since: 1.0.0

### `viewforum_body_last_post_author_username_append`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_side.html
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Append information to last post author username of member
* Since: 1.0.0

### `forumlist_body_category_header_before`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content before the header of the category on the forum list.
* Since: 1.1.0

### `forumlist_body_category_header_row_prepend`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_side.html
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content before the header row of the category on the forum list.
* Since: 1.1.0

### `forumlist_body_category_header_row_append`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_side.html
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content after the header row of the category on the forum list.
* Since: 1.1.0

### `viewforum_body_topicrow_row_before`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content before list of topics.
* Since: 1.1.0

### `viewforum_body_topic_row_prepend`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content at the end of the topic list item.
* Since: 1.1.0

### `topiclist_row_topic_title_after`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content into topic rows (after the elements containing the topic titles)
* Since: 1.1.0

### `topiclist_row_topic_by_author_after`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content into topic rows (after the "by topic author" row)
* Since: 1.1.0

### `topiclist_row_prepend`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content into topic rows (inside the elements containing topic titles)
* Since: 1.1.0

### `topiclist_row_topic_by_author_before`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content into topic rows (before the "by topic author" row)
* Since: 1.1.0

### `viewforum_body_topic_row_append`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content at the start of the topic list item.
* Since: 1.1.0

### `viewforum_body_topic_row_after`
* Location:
	+ \ext\imcger\recenttopicsng\styles\all\template\rtng_body_topbottom.html
* Purpose: Add content after the topic list item.
* Since: 1.1.0
