## List of php events

* Event name :  imcger.recenttopicsng.modify_topics_list
* Description : Event to modify the topics list data before we start the display loop
* Placement : imcger\recenttopicsng\core\rtng_functions\display_recent_topics
* Since : 2.0.1
* known listeners :  /
* Arguments :
  - @var   array    topic_list        Array of all the topic IDs
  - @var   array    rowset            The full topics list array

-----------

* Event name :  imcger.recenttopicsng.modify_tpl_ary
* Description : Modify the topic data before it is assigned to the template
* Placement : imcger\recenttopicsng\core\rtng_functions\display_recent_topics
* Since 2.0.0
* known listeners :  /
* Arguments :
  - @var   array    row            Array with topic data
  - @var   array    tpl_ary        Template block array with topic data

-----------

* Event name :  imcger.recenttopicsng.sql_pull_topics_list
* Description : Event to modify the SQL query before the allowed topics list data is retrieved
* Placement : imcger\recenttopicsng\core\rtng_functions\gettopiclist
* known listeners :  /
* Since 2.0.4
* Arguments :
 - @var   array    sql_array        The SQL array
 
-----------

## List of Template Events

* Event name : recenttopics_mchat_side
* Description : Injection point for Mchat under Recent topics in Side mode.
* Placement : imcger\recenttopicsng\styles\all\template\event\index_body_markforums_after.html
* Since 2.2.3

