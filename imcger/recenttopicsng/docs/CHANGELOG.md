### Changelog

#### 3.0.0 (05/11/2024)
  - [CHG] Changed repository folder structure.
  - [CHG] Changed vendor and package name to `ìmcger/recenttopcsng`
  - [CHG] Migration has been completely renewed.
  - [CHG] Changed some filenames.
  - [CHG] Rename `contrib` folder in `docs`.
  - [CHG] ChangeLog has been revised.
  - [CHG] Texts in the language files have been revised.
  - [CHG] Rename variable names in language files, template files, ACP, UCP and core.
  - [CHG] The display conditions for the settings in the UCP have been revised.
  - [CHG] The conditions for displaying recent topics on the index page have been revised.
  - [CHG] Added template vars to switch in template between first post, first unread post and last post.
  - [CHG] Modified template topbottom.html to display the first post, the first unread post or the last post.
  - [CHG] Modified template body_side.html to display the first post, the first unread post or the last post.
  - [CHG] Split listener.php into main_listen.php and acp_listener.php.
  - [CHG] Optimize code for support extension `\phpbb\collapsiblecategories`.
  - [ADD] Copyright for the author of the language files added to the ACP template.
  - [ADD] User settings added to the user administration panel in ACP.
  - [ADD] DB query extended to include all information required to display the link to the first unread post.
  - [ADD] Added template vars to show all info for the first unread post.
  - [ADD] Added user settings for Simple and Separate page display.
  - [ADD] Added a user preference to toggle the topic heading between the first post, the last post, or the first unread post.
  - [ADD] Added template file for user settings in UCP, ACP and user administration.
  - [DEL] Removed the donation section from the ACP.
  - [DEL] Removed unused template variables in function display_recent_topics().
  - [DEL] Redundant data rows in the config table has been removed. Use settings from guest as template for new user.
  - [DEL] Event paybas.recenttopics.topictitle_remove_re has been removed.
  - [DEL] Event paybas.recenttopics.modify_topictitle has been removed.
  - [DEL] Function topictitle_remove_re() has been removed.
  - [DEL] Function is_listening() has been removed.
  - [DEL] Removed constructor comments.

#### 2.2.15-pl18 (13/03/2024)
  - [CHG] Permission management:
    - A separate category “Recent Topics” was created for the permissions. The “Misc” category is no longer used.
    - Adjusted language files and removed the "Recent Topics:" prefix.
  - [CHG] The previous temporary project name "Recent Topics (fork by IMC & LukeWCS)" has been changed to the final name ***"Recent Topics NG"***.
    - composer.json adjusted accordingly.
    - The new prefix for variable names and other identifiers is now RTNG. Used for the first time with the new permissions category.
  - [CHG] LukeWCSphpBBConfirmBox 1.4.3:
    - Code optimization.
  - [CHG] Other changes in composer.json:
    - Removed version check for phpBB.com because it doesn't make sense with the fork. At a later date we will add a new version check for RTNG.
    - Description reduced to the essentials. Version information does not belong there, as there are dedicated places for it.
  - [CHG] Miscellaneous:
    - Reduced README.md to the minimum and added current information.
  - [FIX]  No storage of user data during registration if the board e-mail is deactivated.
  - [DEL] Remove support for:
    - nickvergessen/newspage
    - part3/topicprefixes
    - imkingdavid/prefixed
  - [CHG] Change the minimum value for the number of topics in the settings.
  - [CHG] With the Twig macros number() and text(), some HTML attributes were generated unnecessarily if their associated optional parameters were not specified. Generation now depends on the existence of the optional parameters. In addition, all optional attributes were noted one below the other, which made the macros clearer. The macros were adopted from LFWWH 2.2.0.
  - [CHG] Code syntax of the core events adjusted.

#### 2.2.15-pl12 (13/12/2023)
  - [CHG] works also with php v8.3
  - [CHG] Confirmbox for security question in ACP before overwriting user settings toLukeWCSphpBBConfirmBox 1.4.0
  - [CHG] Adjusted language variables
  - [ADD] Compatible with Toggle Control. Administrators can decide centrally whether radio buttons, checkboxes or toggles should be used for yes/no switches.

#### 2.2.15-pl12 (04/11/2023)
  - [FIX] Don't set default userdata for guest
  - [CHG] Security question in ACP to LukeWCSphpBBConfirmBox, which combines all functions and properties in a single object.

#### 2.2.15-pl10 (25/02/2023)
  - [CHG] Display Recent Topics link in NavBar only when "Separate page only" is selected
  - [CHG] Minor code changes in core and page controller

#### 2.2.15-pl9 (19/02/2023)
  - [CHG] Change extension name to temporary project name ***"Recent Topics (fork by IMC & LukeWCS)"***.
  - [CHG] Extensive code revisions (PHP and TWig). Consideration of the phpBB "Extension Validation Policy"
  - [FIX] Crash when setting "Show all pages" with PHP 8.x
  - [FIX] When new users registering, the default settings of the ACP are not taken over
  - [FIX] With guests the page selection always jumps to button 1 with setting "Show all pages"
  - [FIX] With guests all posts are marked as unread with setting "Show only unread topics" as user default setting
  - [FIX] Special page with Simple-Header not accessible. (New link: app.php/rt/simple)
  - [FIX] Migration reduced to the bare minimum, hoping to fix the sporadic uninstall bug.
  - [FIX] Error messages in ACP module when cURL is not available in server context. (See "Removed: Version check")
  - [DEL] Remove languages not supported by us
  - [DEL] Remove styles not supported by us and obsolete styles
  - [DEL] Remove version check (retrieval from phpBB foreign page via cURL)
  - [ADD] Display RecentTopics on a serarate page (different settings from ACP "hard coded" in page_controller.php possible)
  - [ADD] Security question in ACP before overwriting user settings
  - [CHG] Links are now generated in shortened form, which was introduced in phpBB 3.3.5. This eliminates the forum & topic parameters for post links and the forum parameter for topic links.
  - [CHG] phpBB min. 3.3.5, PHP max. 8.2

#### 2.2.15 (05/04/2021)
  - [FIX] #142 Compatible with PHP 8 
  
#### 2.2.14 (01/08/2020)
  - [FIX] align prosilver display of Post Order to pbWow to display first post. 
  - [FIX] Add topic_posted table so the image of "_mine" displays.
    
#### 2.2.13 (29/06/2020)
  - [FIX] better description for RT_PAGE_NUMBER_EXP
  - [FIX] code improvements
  - [FIX] #125 page selector was always on page 1 when show all pages was acivated. 

#### 2.2.12 (28/03/2020)
  - [FIX] #123 Reset user settings issue
  - [NEW] Ukrainian Language for Recent Topics, by Phobos-7
  
#### 2.2.11 (21/03/2020)
  - [CHG] #120 don't show "Re: " in front of last reply text, new php event topictitle_remove_re
  - [CHG] #119 Fetching last page doesnt work to enable "all" pages 
  
#### 2.2.10 (21/03/2020)
  - [CHG] #120 don't show "Re: " in front of last reply text. 
  
#### 2.2.9 (15/03/2020)
  - [CHG] support for style we_clearblue 3.2.9
  - [CHG] support for style pbwow 3.2.9  
  - [CHG] #115 in rightpane view, last author name is also shown 
  - [FIX] #116 topic link and text now refer to last reply, or else the topic post.  
  - [CHG] add php 7.2 to travis tests
  - [FIX] code fixes: use sql_build_array, cast int on request_var, don't use http_exception class
  - [CHG] compatible with Collapsible Categories v2
  - [CHG] Add 2 phpBB core template events viewforum_body_last_post_author_username_prepend + viewforum_body_last_post_author_username_append

#### 2.2.8 (12/08/2018)
  - [FIX] #82 special page fix
  - [FIX] #108 poll layout issue
  - [FIX] #107 registration issue
  - [FIX] #109 make curl https compatible

#### 2.2.7 (25/02/2018) 
  - [NEW] #82 recent topics is now available on a special page http://url/app.php/rt
  - [CHG] now uses phpbb 3.2 language class
  - [CHG] #77 moved setting for number of recent topics per page to ucp
  - [FIX] fix potential nullreference error in $start
  - [FIX] languge nl, de (missing variables)
  - [ADD] language ar (@alhitary), ru (@SiavaRu), pt (@borgesjoaquim)
   
#### 2.2.6 (28/10/2017) 
  - [NEW] #43 Supports Collapsable categories extension for prosilver.   
  - [CHG] #76 Switched to Twig syntax
  - [FIX] #72, #19 clickable topic icon
  - [FIX] #75 remove quoted integer value 
  
#### 2.2.5 (30/09/2017) 
  - [FIX] #68 fixed unescaped line in acp
  - [FIX] #67 fixed overflow links in paging for unread and alltopics mode.

#### 2.2.4 (18/09/2017)
  - [FIX] Fix bug with pagination (Tatiana5)

#### 2.2.3 (17/09/2017)
  - [FIX] Add template event handler for recenttopics_mchat_side in index_body_markforums_after.html event. 

#### 2.2.2 (16/09/2017)
  - [FIX] Corrected confusing ACP labels regarding the number of pages.
  - [FIX] Fixed the number of pages limitation option in ACP. Now the number of recent topic pages can be limited. 
  - [FIX] Fixed the override the maximum number of pages shown ACP checkbox.
  - [FIX] Jump to page fix. If a user selects a page outside the range, the last page will be displayed.
  - [FIX] removed double pagination in horizontal view
  - [FIX] u_rt_view, rt_unreadonly was still present in database after full uninstall.
  - [FIX] Fixed an error in PostgreSQL/Mssql installation (usage of from_unixtime) 
  - [NEW] Added placeholder feature if no new Topics.
  - [NEW] Added support for pbWoW 3.2 style
  - [NEW] Added Paypal donation link 
  - [NEW] Added version checking in ACP page

#### 2.2.1 (14/06/2017)
  - [UPD] add paging limit

#### 2.2.0 (22/01/2017)
  - [UPD] Update for phpbb 3.2.0 
  - [FIX] #20 Adjust 3.2 pagination css 

#### 2.1.3 (22/01/2017)
  - [UPD] new language files ar/es/es-x-tu/pt/ru
  - [FIX] fixed placement post status-icons. parent li should be relative.  
  - [FIX] #17 #18 fixed permission issues
  - [NEW] Added a user preference resetbutton in ACP.
  - [CHG] 2.1.3 migration resets all positions to top.  
  - [NEW] add support for pre:fixed extension from imkingdavid. using a new event paybas.recenttopics.modify_topictitle, and fallback if no listener was found
  
#### 2.1.2 (22/10/2016)
  - [FIX] Permission u_rt_view changed to registered users
  - [FIX] Migration file Recent topics 2.0.0 fixed. as of 3.1.10, root path must exist in module.exists. see PHPBB3-14703 and commit https://github.com/phpbb/phpbb/commit/5eb493fa86
  - [NEW] Czech translation by @R3gi

#### 2.1.1 (03/07/2016)
  - [FIX] sideview responsive css not working in prosilver
  - [CHG] merged small & wide html
  - [CHG] removed H2 from recent topics window header in prosilver  
  - [CHG] changed default recent topics location to the right side
     
#### 2.1.0 (19/06/2016)
  - [NEW] alternative locations changed to 3-option dropdown to enable 3 display locations (top, bottom, right side) which depends on events, and isn’t hardcoded in the style. 
  - [NEW] Removed custom code for pbWoW & pbTech as all styles now follow the same standard.     
  - [CHG] ACP topic level changed to pulldown menu. 
  - [FIX] Other improvements and fixes.
  - [CHG] Feature release, so version number increased.
  - [CHG] Language added: Portuguese. 
  - [DEL] Languages I couldn't complete were removed. please submit your language packs.       
     
#### 2.0.6 (12/03/2016)
  - [NEW] Croatian translation (Ancica) 
  - [NEW] Hungarian translation (aszilagyi)
  - [NEW] Estonian translation (phpBBeesti)
  - [NEW] Arabic translation (Alhitary)  
  - [NEW] Turkish translation (edipdincer)   
  - [NEW] Italian translation (Mauron)     
  - [FIX] Danish translation (jensz12)       
  - [UPD] French translation (Galixte)      
  - [FIX] #17 Mark topics as read when using the forum AJAX function, fixes issue (Kasimi)
  - [UPD] Japanese translation (momo-i)     
  - [FIX] #1 topic icons show also when logged out 
  - [NEW] compatible with Topic Prefix Extension from Stathis
