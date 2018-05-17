moodle-local_staticpage
=======================

Changes
-------

### v3.4-r2

* 2018-05-16 - Implement Privacy API.

### v3.4-r1

* 2017-12-14 - Check compatibility for Moodle 3.4, no functionality change.
* 2017-12-05 - Added Workaround to travis.yml for fixing Behat tests with TravisCI.

### v3.3-r1

* 2017-11-23 - Check compatibility for Moodle 3.3, no functionality change.
* 2017-11-08 - Updated travis.yml to use newer node version for fixing TravisCI error.

### v3.2-r9

* 2017-09-22 - Bugfix: Boost seems not to process filters for our breadcrumb element anymore

### v3.2-r8

* 2017-09-13 - Escape proposed HTML structure and Apache configuration in README
* 2017-09-13 - Escape HTML code in language pack - Credits to Carson Tam

### v3.2-r7

* 2017-05-29 - Add Travis CI support

### v3.2-r6

* 2017-05-05 - Improve README.md
* 2017-02-15 - Minor code improvement, no functionality change

### v3.2-r5

* 2017-01-27 - Automatically rename htm files to html - Credits to Andrew Hancox

### v3.2-r4

* 2017-01-12 - Move Changelog from README.md to CHANGES.md

### v3.2-r3

* 2017-01-12 - Check compatibility for Moodle 3.2, no functionality change
* 2017-01-12 - Re-add the list of available static pages
* 2017-01-11 - Improve README

### v3.2-r2

* Internal release

### v3.2-r1

* Internal release

### v3.1-r2

* 2016-07-21 - Move the plugin's settings page to Site Administration -> Static Pages because this is where it logically belongs to

### v3.1-r1

* 2016-07-19 - Check compatibility for Moodle 3.1, no functionality change

### Changes before v3.1

* 2016-05-09 - Add settings to control if filters are processed and if the HTML code is cleaned on a static page or not
* 2016-04-07 - Add a setting to control if a static page should be shown to visitors or not
* 2016-02-10 - Add a new filearea to save the document files within Moodle - This change might break backwards compatibility in some situations, please read the "Upgrading from previous versions" below
* 2016-02-10 - Change plugin version and release scheme to the scheme promoted by moodle.org, no functionality change
* 2016-01-25 - Improve RewriteRules in README, no functionality change - Credits to Daniel Ruf
* 2016-01-01 - Check compatibility for Moodle 3.0, no functionality change
* 2015-08-18 - Check compatibility for Moodle 2.9, no functionality change
* 2015-01-23 - Check compatibility for Moodle 2.8, no functionality change
* 2014-08-19 - Add possibility to use a `<style>` tag in the HTML code of static page files
* 2014-06-30 - Add plugin's name as prefix to function names
* 2014-06-30 - Check compatibility for Moodle 2.7, no functionality change
* 2014-01-31 - Add description how to add blocks to static pages
* 2014-01-31 - Extend valid URLs to alphanumeric characters (no numbers were allowed before)
* 2014-01-31 - Require login if Moodle is configured to force login - Credits to toby saunders
* 2014-01-31 - Check compatibility for Moodle 2.6, no functionality change
* 2013-10-25 - Remove support for 'staticpage' pagelayout because of problems with Bootstrap-based themes. local_staticpage uses 'standard' pagelayout now. Please check your CSS cascades if you have styled static pages in any special way.
* 2013-09-02 - Check availability of static page directly on settings page, show error if page isn't downloadable
* 2013-09-02 - Add an option to set the data source of document title, document heading and breadcrumb item title
* 2013-08-08 - Improve README
* 2013-07-30 - Transfer Github repository from github.com/abias/... to github.com/moodleuulm/...; Please update your Git paths if necessary
* 2013-07-30 - Check compatibility for Moodle 2.5, no functionality change
* 2013-07-23 - Extend valid URLs with "_-" characters
* 2013-04-23 - Fix problems on windows systems
* 2013-04-22 - Bugfix: Language switcher didn't work anymore on a static page
* 2013-03-22 - Bugfix: Static page wouldn't show even if everything was configured correctly; Please notice that apache rewrite rule has changed
* 2013-03-18 - Code cleanup according to moodle codechecker
* 2013-02-18 - Check compatibility for Moodle 2.4, fix language string names to comply with language string name convention
* 2013-01-21 - Fix a flaw in README file regarding filename examples
* 2013-01-21 - Fix a flaw in README file regarding Apache mod_rewrite
* 2013-01-21 - Migrate plugin settings from config.php to a settings page within Moodle
* 2012-09-26 - Replace deprecated get_context_instance function
* 2012-06-25 - Update version.php for Moodle 2.3
* 2012-06-01 - Initial version
