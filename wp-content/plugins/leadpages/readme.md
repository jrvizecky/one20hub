# Leadpages Connector


A WordPress plugin to easily use your Leadpages pages and Leadboxes inside your WordPress site.

***Change Log***
* 2.1.6.21
    * Removes Carbon library
    * Removes listing cache due to sped up backend

* 2.1.6.20
    * Fixes for duplicate split test options in drop down

* 2.1.6.19
    * Updated pages library

* 2.1.6.18
    * Fix issue with failing to fetch split tests
    * Fix dropdown to correctly select the relevent page
    * General bug fixes

* 2.1.6.17
    * Fix problem with auto-slugs on edit page populating the default slug
    * Fix missing use for mbstring polyfill from helpers

* 2.1.6.16
    * Fix Welcome gate page will not show up if a static page is set for the front page - CJ-518
    * Caching page list from API for 15 minutes with wp transients - CJ-514
    * Added updated date to page listing dropdown
    * Added views and optin counts to page listing dropdown
    * Slug defaults to Leadpages' slug when selected from dropdown
    * Admin UI cleanup

* 2.1.6.15
    * Fix for global leadboxes not respecting the post type they were selected to show up on

* 2.1.6.14
    * Fix for mixed case email address login failure - CJ-508
    * Fix for 404 pages returning 200 ok - CJ-504
    * Fix for refreshing Leadbox dropdown not including copy/paste B3 embed code - CJ-505
    * Polyfill for mbstring library from symfony framework

* 2.1.6.13
    * Fix for plugin css overrides Admin font - CJ-478
    * Retry fetching public html https fails - CJ-479

* 2.1.6.12
    * Fix for list elements with bold text, new lines from DOMDocument
    * Fix for specific Leadbox set on static homepage not displayed

* 2.1.6.11
    * Fix for `<doctype>` getting stripped off by DOMDocument

* 2.1.6.10
    * Fix Split Test listing. Display the parent split test instead of control and variations separately.

* 2.1.6.9
    * Fixed UTF-8 character encoding issue with DOMDocument
    * Updated plugin login screen

* 2.1.6.8
    * Fix for Leadbox shortcode
    * Replace <meta> served-by attribute for v2 plugin usage to report correctly in WP usage metrics

* 2.1.6.7
    * Moved API key check to the wp_loaded hook to avoid race condition of running before loading vendor directory.

* 2.1.6.5
    * Updated Leadpage packages to use Api Key instead of access id or lp token.  WP-7

* 2.1.6.4
    * Updated Leadpage packages to use access id to fix logout issue. WP-7

* 2.1.6.3
    * Removed hourly check for lp token

* 2.1.6.2
    * Updated auth urls to account vs auth
    * Changed cron job to check token to 5 days vs 25 days to allow time to recheck before user is logged out

* 2.1.6.1
   * Updated cron job to check status of users account to ensure that the response code is 200

   and the profiles is a valid index of response
   * Added WordPress cert to all https calls to help mitigate ssl erros

* 2.1.6
    * Added split tests as a page object in the Add Leadpage dropdown

* 2.1.5.6
    * is_front_page returning errors with some sites static front page setups. Silenced errors with @is_front_page

* 2.1.5.5
    * Changed update command for updating from version 1 to version 2 to ensure it runs properly

* 2.1.5.4
    * Changed WelcomeGate to only display on home and front pages

* 2.1.5.3
    * Fixed issue where a exit drag and drop leadbox would not save if a timed
    drag and drop box did not exist.
    * Fixed issue with plugin not showing drag and drop leadboxes

* 2.1.5.2
    *  Fixed issue where radio button for All pages for timed leadboxes
    would not stay checked unless you had all pages for exit leadboxes checked as well.
    * Fixed issue with Leadboxes showing on every page even if you had it marked to show only on posts or pages

* 2.1.5.1
    * Fixed issue with composer.json for Auth component

* 2.1.5
    * Turned on ignore veirfy peer for all curl calls to avoid curl 60 errors...

* 2.1.4.6
    * Fix for page load slowness. Had to store the new page api id for pages created with old plugin
    * Allow pages with customer permalinks of something like /blog/pagename to load a leadpages with just pagename

* 2.1.4.5
    * Fixed issue where WelcomeGate pages would not allow a feed to be viewed
    * Fixed issue where a search page would return a Homepage Leadpage if it was setup.
    * Fix for Auto Draft showing up as url in Leadpage listing in admin
    * Fix for trying to resave an existing page shows a slug already exists error

* 2.1.4.4
    * Drag and Drop Leadboxes in the Global Leadbox section.
    * Split Tested pages cookies have been added to track properly
    * Fix for 404 pages taking over the homepage
    * Removed UUID class that was causing errors with Moontoast\Math
    * Fixed double slashes in page path when upgrading to new plugin

* 2.1.4
    * Updated UI for Leadpages
    * Updated Ui for Leadboxes
    * Updated slug settings to allow for mutli level slugs (parent/child/grandchild)
    * Leadpages now work with all permalink structures including custom
    * Updated to latest version of Leadpages Auth, Pages, and Leadboxes to handle ConnectExpcetion in Guzzle for curl errors

* 2.1.2
     * Added support for 32 bit systems for UUID generation


