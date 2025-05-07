moodle-local_staticpage
=======================

Changes
-------

### v4.5-r2

* 2025-05-06 - Tests: Fix Behat failures due to MDL-67683, resolves #76

### v4.5-r1

* 2024-10-14 - Upgrade: Adopt changes from MDL-82183 and use new \core\output\html_writer
* 2024-10-14 - Upgrade: Adopt changes from MDL-81960 and use new \core\url class
* 2024-10-14 - Upgrade: Adopt changes from MDL-81818 to remove old bootstrap classes
* 2024-10-07 - Prepare compatibility for Moodle 4.5.

### v4.4-r1

* 2024-08-24 - Development: Rename master branch to main, please update your clones.
* 2024-08-20 - Upgrade: Update Bootstrap classes for Moodle 4.4.
* 2024-08-20 - Prepare compatibility for Moodle 4.4.

### v4.3-r2

* 2024-08-11 - Add section for scheduled tasks to README
* 2024-08-11 - Updated Moodle Plugin CI to latest upstream recommendations
* 2024-08-11 - Preserve <link> tags in head of static page document by moving it to the Moodle <head> element, credits to Peter Keijsers.
* 2024-08-11 - Added static page view even, credits to Jeroen de Bruijn.
* 2024-01-08 - Improve the installation and usage of language packs in Behat tests.

### v4.3-r1

* 2023-10-20 - Prepare compatibility for Moodle 4.3.

### v4.2-r1

* 2023-10-19 - Fix Behat tests which broke on Moodle 4.2.
* 2023-09-01 - Prepare compatibility for Moodle 4.2.

### v4.1-r2

* 2023-10-14 - Add automated release to moodle.org/plugins
* 2023-10-14 - Make codechecker happy again
* 2023-10-10 - Updated Moodle Plugin CI to latest upstream recommendations
* 2023-04-30 - Tests: Updated Moodle Plugin CI to use PHP 8.1 and Postgres 13 from Moodle 4.1 on.

### v4.1-r1

* 2023-01-21 - Prepare compatibility for Moodle 4.1.
* 2022-11-28 - Updated Moodle Plugin CI to latest upstream recommendations

### v4.0-r1

* 2022-07-12 - Improve lib.php, solves #62.
* 2022-07-12 - Remove the 'documentnavbarsource' setting as static pages do not have a navbar anymore on Moodle 4.0.
* 2022-07-12 - Fix Behat tests which broke with Moodle 4.0.
* 2022-07-12 - Prepare compatibility for Moodle 4.0.

### v3.11-r3

* 2022-07-11 - Bugfix: Static page was crippled if the HTML code did not contain a plain body tag, solves #64 and several older issues.
* 2022-07-11 - Add links to README to the language pack, solves #61
* 2022-07-10 - Add Visual checks section to UPGRADE.md

### v3.11-r2

* 2022-06-26 - Make codechecker happy again
* 2022-06-26 - Updated Moodle Plugin CI to latest upstream recommendations
* 2022-06-26 - Add UPGRADE.md as internal upgrade documentation
* 2022-06-26 - Update maintainers and copyrights in README.md.

### v3.11-r1

* 2021-12-08 - Prepare compatibility for Moodle 3.11.

### v3.10-r2

* 2021-08-28 - Feature: Add a body class which contains the page name of the static page shown
* 2021-08-28 - Bugfix: Fix incorrect setting defaults when installing the plugin - Thanks to Davo Smith
* 2021-08-28 - Replace the deprecated print_error() function with a Moodle exception
* 2021-08-28 - Feature: Allow non-admins to manage static page documents by introducing the local/staticpage:managedocuments capability
* 2021-02-05 - Move Moodle Plugin CI from Travis CI to Github actions

### v3.10-r1

* 2021-01-09 - Change Bootstrap labels to badges to comply with Bootstrap 4 standards
* 2021-01-09 - Fix a small language pack flaw for the admin setting page.
* 2021-01-09 - Prepare compatibility for Moodle 3.10.
* 2021-01-06 - Change in Moodle release support:
               For the time being, this plugin is maintained for the most recent LTS release of Moodle as well as the most recent major release of Moodle.
               Bugfixes are backported to the LTS release. However, new features and improvements are not necessarily backported to the LTS release.
* 2021-01-06 - Improvement: Declare which major stable version of Moodle this plugin supports (see MDL-59562 for details).

### v3.9-r1

* 2020-11-25 - Improvement: Improve require_once calls which require the plugin's library - Credits to Antoni Bertran.
* 2020-11-25 - Prepare compatibility for Moodle 3.9.

### v3.8-r1

* 2020-02-14 - Prepare compatibility for Moodle 3.8.
* 2019-12-18 - Improved behat test for fileupload because MDL-60975 was fixed.
               PLEASE NOTE: For all scenarios to pass, the Moodle version 3.7.3+ (Build: 20191212) is needed.

### v3.7-r2

* 2019-12-12 - Bugfix: List of static pages link broken when moodle is installed in subdirectory - Credits to wuzhuoqing.
* 2019-10-24 - Improvement: Add @_file_upload tag to fix failing behat tests.

### v3.7-r1

* 2019-09-27 - Added behat tests.
* 2019-09-23 - Add admin setting to check availability on list of static pages or not - Credits to Arief Wibowo.
* 2019-08-05 - Prepare compatibility for Moodle 3.7.

### v3.6-r1

* 2019-01-22 - Check compatibility for Moodle 3.6, no functionality change.
* 2018-12-05 - Changed travis.yml due to upstream changes.

### v3.5-r2

* 2018-09-26 - Bugfix: Forcing apache rewrite wasn't possible for Moodle installations in subdirectories.

### v3.5-r1

* 2018-06-29 - Check compatibility for Moodle 3.5, no functionality change.

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
