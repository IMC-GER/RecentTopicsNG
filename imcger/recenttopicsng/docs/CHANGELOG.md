## Changelog Recent Topics NG V1.0.0
This is a non-exhaustive (but still near complete) changelog for Recent Topics NG 1.x including release candidate versions.

#### Changes since 2.2.15-pl18 (22/02/2025)
  - [Change] Changed ***Version changed to 1.0.*** Since RTNG is an independent project, there is no reason to continue the RT version number.
  - [Change] Changed vendor and package name to `ìmcger/recenttopcsng`.
  - [Change] Changed repository folder structure.
  - [Change] Changed php max version to 8.4.
  - [Change] Existing migration has been completely renewed into DB changes (`s_1_0_0.php`) and other changes (`v_1_0_0.php`).
  - [Feature] Current macro `select()` adopted from Extension Manager Plus 2.1.0 Beta.
  - [Feature] Existing `common.select()` calls adapted to the new macro version.
  - [Feature] New class `controller_common` for general helper functions for controllers/listeners.
  - [Feature] Function `select_struct()` adopted from Extension Manager Plus 2.1.0 Beta.
  - [Feature] Existing select arrays converted to the new function `select_struct()`.
  - [Feature] Added pattern and placeholder to rtng_anti_topics input.
  - [Change] Added set `rtng_anti_topics` to `0` if the input field was empty.
  - [Change] Added rtng settings in user administration for guest account.
  - [Change] Added methode to set number of topics in `rtng_functions.php`.
  - [Change] Added methode to set number of pages in `rtng_functions.php`.
  - [Change] Added helper functions to `controller_common.php`.
  - [Change] plausibility check of the `rtng_anti_topics` variable.
  - [Change] SQL query in core changed from string to array.
  - [Change] Changed some filenames.
  - [Change] Rename `contrib` folder in `docs`.
  - [Change] ChangeLog has been revised.
  - [Change] Texts in the language files have been revised.
  - [Change] Rename variable names in language files, template files, ACP, UCP and core.
  - [Change] Added template vars to switch in template between first post, first unread post and last post.
  - [Change] Modified template `topbottom.html` to display the first post, the first unread post or the last post.
  - [Change] Modified template `body_side.html` to display the first post, the first unread post or the last post.
  - [Change] Split `listener.php` into `main_listen.php` and `acp_listener.php`.
  - [Change] Optimize code for support extension `\phpbb\collapsiblecategories`.
  - [Feature] Copyright notice for the author of the language files added to the ACP template.
  - [Feature] User settings added to the user administration panel in ACP.
  - [Feature] DB query extended to include all information required to display the link to the first unread post.
  - [Feature] Added template vars to show all info for the first unread post.
  - [Feature] Added user settings for Simple and Separate page display.
  - [Feature] Added a user preference to toggle the topic heading between the first post, the last post, or the first unread post.
  - [Feature] Added template file for user settings in UCP, ACP and user administration.
  - [Feature] Added methode in core to set number of topics and number of sites.
  - [Feature] Added helper functions to optimize code in the core, listeners and controller.
  - [Delete] Removed the donation section from the ACP.
  - [Delete] Removed unused template variables in function `display_recent_topics()`.
  - [Delete] Redundant data rows in the config table has been removed. Use settings from guest as template for new user.
  - [Delete] Event `paybas.recenttopics.topictitle_remove_re` has been removed.
  - [Delete] Event `paybas.recenttopics.modify_topictitle has` been removed.
  - [Delete] Function `topictitle_remove_re()` has been removed.
  - [Delete] Function `is_listening()` has been removed.
  - [Delete] Removed constructor comments.
  - [Fixed] Small style errors in "View on the side" Fixeded; errors in template and CSS. [Report from Kirk (phpBB.de)]
  - [Fixed] Declaration of the array $rowset within a while loop in `gettopiclist()`.

#### Changes since 2.2.15-pl14 (13/03/2024)
  - [Change] Permission management:
    - A separate category “Recent Topics” was created for the permissions. The “Misc” category is no longer used.
    - Adjusted language files and removed the "Recent Topics:" preFixed.
  - [Change] The previous temporary project name "Recent Topics (fork by IMC & LukeWCS)" has been changed to the final name ***"Recent Topics NG"***.
    - composer.json adjusted accordingly.
    - The new preFixed for variable names and other identifiers is now RTNG. Used for the first time with the new permissions category.
  - [Change] LukeWCSphpBBConfirmBox 1.4.3:
    - Code optimization.
  - [Change] Other changes in composer.json:
    - Removed version check for phpBB.com because it doesn't make sense with the fork. At a later date we will add a new version check for RTNG.
    - Description reduced to the essentials. Version information does not belong there, as there are dedicated places for it.
  - [Change] Miscellaneous:
    - Reduced README.md to the minimum and added current information.
  - [Fixed]  No storage of user data during registration if the board e-mail is deactivated.
  - [Delete] Remove support for:
    - nickvergessen/newspage
    - part3/topicpreFixedes
    - imkingdavid/preFixeded
  - [Change] Change the minimum value for the number of topics in the settings.
  - [Change] With the Twig macros number() and text(), some HTML attributes were generated unnecessarily if their associated optional parameters were not specified. Generation now depends on the existence of the optional parameters. In addition, all optional attributes were noted one below the other, which made the macros clearer. The macros were adopted from LFWWH 2.2.0.
  - [Change] Code syntax of the core events adjusted.

#### Changes since 2.2.15-pl12 (13/12/2023)
  - [Change] works also with php v8.3
  - [Change] Confirmbox for security question in ACP before overwriting user settings toLukeWCSphpBBConfirmBox 1.4.0
  - [Change] Adjusted language variables
  - [Feature] Compatible with Toggle Control. Administrators can decide centrally whether radio buttons, checkboxes or toggles should be used for yes/no switches.

#### Changes since 2.2.15-pl10 (04/11/2023)
  - [Fixed] Don't set default userdata for guest
  - [Change] Security question in ACP to LukeWCSphpBBConfirmBox, which combines all functions and properties in a single object.

#### Changes since 2.2.15-pl9 (25/02/2023)
  - [Change] Display Recent Topics link in NavBar only when "Separate page only" is selected
  - [Change] Minor code changes in core and page controller

#### Changes since 2.2.15 (19/02/2023)
  - [Change] Change extension name to temporary project name ***"Recent Topics (fork by IMC & LukeWCS)"***.
  - [Change] Extensive code revisions (PHP and TWig). Consideration of the phpBB "Extension Validation Policy"
  - [Fixed] Crash when setting "Show all pages" with PHP 8.x
  - [Fixed] When new users registering, the default settings of the ACP are not taken over
  - [Fixed] With guests the page selection always jumps to button 1 with setting "Show all pages"
  - [Fixed] With guests all posts are marked as unread with setting "Show only unread topics" as user default setting
  - [Fixed] Special page with Simple-Header not accessible. (New link: app.php/rt/simple)
  - [Fixed] Migration reduced to the bare minimum, hoping to Fixed the sporadic uninstall bug.
  - [Fixed] Error messages in ACP module when cURL is not available in server context. (See "Removed: Version check")
  - [Delete] Remove languages not supported by us
  - [Delete] Remove styles not supported by us and obsolete styles
  - [Delete] Remove version check (retrieval from phpBB foreign page via cURL)
  - [Feature] Display RecentTopics on a serarate page (different settings from ACP "hard coded" in page_controller.php possible)
  - [Feature] Security question in ACP before overwriting user settings
  - [Change] Links are now generated in shortened form, which was introduced in phpBB 3.3.5. This eliminates the forum & topic parameters for post links and the forum parameter for topic links.
  - [Change] phpBB min. 3.3.5, PHP max. 8.2
