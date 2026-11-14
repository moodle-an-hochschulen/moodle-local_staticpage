@local @local_staticpage @javascript @_file_upload
# @javascript is only used for the background but cannot be added selectively to it.
Feature: Using static pages
  In order to be able to use the static pages
  As admin
  I need to be able to configure the local_staticpage plugin

  Background:
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/example.html" file to "Documents" filemanager
    And I press "Save changes"
    And I log out

  ################################################################################
  # Setting "Documents": Uploading and managing static page documents
  ################################################################################

  Scenario: Check if the uploaded page is accessible
    When I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=example'>Example Page</a></p>"
    And I press "Save changes"
    And I click on "Example Page" "link"
    Then I should see "Lorem ipsum dolor sit amet"

  Scenario Outline: Check if documents with a .htm, .HTM or .HTML extension are renamed to .html
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/<file>" file to "Documents" filemanager
    And I press "Save changes"
    And I visit "/local/staticpage/view.php?page=<page>"
    Then I should see "This is the <page> test page"

    Examples:
      | file                 | page            |
      | renamehtm.htm        | renamehtm       |
      | renamehtmupper.HTM   | renamehtmupper  |
      | renamehtmlupper.HTML | renamehtmlupper |

  Scenario: Check if the static pages can be managed by non-admins with moodle/site:config
    Given the following "roles" exist:
      | name                       | shortname      |
      | Non-admin with site config | nonadminconfig |
    And I log in as "admin"
    And I set the following system permissions of "Non-admin with site config" role:
      | capability             | permission |
      | moodle/site:config     | Allow      |
      | moodle/site:configview | Allow      |
    And the following "users" exist:
      | username  | firstname | lastname | email                |
      | nonadmin  | Non       | Admin    | nonadmin@example.com |
    And the following "system role assigns" exist:
      | user           | role           | contextlevel |
      | nonadmin       | nonadminconfig | System       |
    And I log out
    When I log in as "nonadmin"
    And I navigate to "Static Pages" in site administration
    Then I should see "example.html" in the "#admin-documents" "css_element"
    And I upload "local/staticpage/tests/fixtures/nonadmin.html" file to "Documents" filemanager
    And I press "Save changes"
    And I log out
    And I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=nonadmin'>Non-Admin Page</a></p>"
    And I press "Save changes"
    And I click on "Non-Admin Page" "link"
    Then I should see "This is the non-admin test page"

  Scenario: Check if the static pages can be managed by non-admins with local/staticpage:managedocuments
    Given the following "roles" exist:
      | name                            | shortname         |
      | Non-admin with manage documents | nonadmindocuments |
    And I log in as "admin"
    And I set the following system permissions of "Non-admin with manage documents" role:
      | capability                       | permission |
      | local/staticpage:managedocuments | Allow      |
      | moodle/site:configview           | Allow      |
    And the following "users" exist:
      | username  | firstname | lastname | email                |
      | nonadmin  | Non       | Admin    | nonadmin@example.com |
    And the following "system role assigns" exist:
      | user           | role              | contextlevel |
      | nonadmin       | nonadmindocuments | System       |
    And I log out
    When I log in as "nonadmin"
    And I navigate to "Static Pages" in site administration
    Then I should see "example.html" in the "#admin-documents" "css_element"
    And I upload "local/staticpage/tests/fixtures/nonadmin.html" file to "Documents" filemanager
    And I press "Save changes"
    And I log out
    And I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=nonadmin'>Non-Admin Page</a></p>"
    And I press "Save changes"
    And I click on "Non-Admin Page" "link"
    Then I should see "This is the non-admin test page"

  ################################################################################
  # Delivery of static pages (independent of any setting)
  ################################################################################

  Scenario: Check if the page name is added as a body class
    When I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=example'>Example Page</a></p>"
    And I press "Save changes"
    And I click on "Example Page" "link"
    Then the "class" attribute of "body" "css_element" should contain "local-staticpage-example"

  Scenario Outline: Check if non-ASCII characters are rendered correctly regardless of the document's charset declaration
    Given the following config values are set as admin:
      | config                | value | plugin           |
      | documenttitlesource   | 2     | local_staticpage |
      | documentheadingsource | 1     | local_staticpage |
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/<page>.html" file to "Documents" filemanager
    And I press "Save changes"
    And I visit "/local/staticpage/view.php?page=<page>"
    Then "//title[contains(text(),'Titel mit Umlauten äöüß')]" "xpath_element" should exist
    And I should see "Überschrift mit Umlauten äöüß" in the ".page-header-headings" "css_element"
    And I should see "Grüße mit Sonderzeichen ® €" in the "#region-main" "css_element"

    Examples:
      | page              |
      | encodingnometa    |
      | encodingbom       |
      | encodingbogusmeta |

  ################################################################################
  # Settings "Data source of document title" and "Data source of document heading"
  ################################################################################

  Scenario: Check if h1 tag is used for the document title
    Given the following config values are set as admin:
      | config              | value | plugin           |
      | documenttitlesource | 1     | local_staticpage |
    When I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=example'>Example Page</a></p>"
    And I press "Save changes"
    And I click on "Example Page" "link"
    Then "//title[contains(text(),'This is a h1 heading')]" "xpath_element" should exist

  Scenario: Check if title tag is used for the document title
    Given the following config values are set as admin:
      | config              | value | plugin           |
      | documenttitlesource | 2     | local_staticpage |
    When I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=example'>Example Page</a></p>"
    And I press "Save changes"
    And I click on "Example Page" "link"
    Then "//title[contains(text(),'This is a title')]" "xpath_element" should exist

  Scenario: Check if h1 tag is used for the document heading
    Given the following config values are set as admin:
      | config                | value | plugin           |
      | documentheadingsource | 1     | local_staticpage |
    When I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=example'>Example Page</a></p>"
    And I press "Save changes"
    And I click on "Example Page" "link"
    Then I should see "This is a h1 heading" in the ".page-header-headings" "css_element"

  Scenario: Check if title tag is used instead of first h1 for the document heading
    Given the following config values are set as admin:
      | config                | value | plugin           |
      | documentheadingsource | 2     | local_staticpage |
    When I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=example'>Example Page</a></p>"
    And I press "Save changes"
    And I click on "Example Page" "link"
    Then I should see "This is a title" in the ".page-header-headings" "css_element"

  ################################################################################
  # Setting "Force login"
  ################################################################################

  Scenario: Check that the page is only shown to users that are logged in.
    Given the following config values are set as admin:
      | config            | value | plugin           |
      | forcelogin        | 1     | local_staticpage |
    And the following config values are set as admin:
      | config            | value                                                                     |
      | auth_instructions | <p><a href="/local/staticpage/view.php?page=example">Example page</a></p> |
    And the following "users" exist:
      | username |
      | student1 |
    When I log in as "student1"
    And I log out
    And I click on "Log in" "link"
    And I click on "Example page" "link"
    Then I should see "You are not logged in"
    And I should not see "Dies ist eine h2 Überschrift"

  ################################################################################
  # Setting "Process filters"
  ################################################################################

  Scenario: Check if multilang filters are processed
    Given the following "language packs" exist:
      | language |
      | de       |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    And the following config values are set as admin:
      | config         | value | plugin           |
      | processfilters | 1     | local_staticpage |
    And the following "users" exist:
     | username | lang |
     | student1 | de   |
    When I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=example'>Example Page</a></p>"
    And I press "Save changes"
    And I log out
    And I log in as "student1"
    And I am on site homepage
    And I click on "Example Page" "link"
    Then I should see "Dies ist eine h2 Überschrift"
    And I should not see "This is a h2 heading"

  # Counter check previous scenario
  Scenario: Check if multilang filters are not processed
    Given the following "language packs" exist:
      | language |
      | de       |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    And the following config values are set as admin:
      | config         | value | plugin           |
      | processfilters | 2     | local_staticpage |
    And the following "users" exist:
      | username | lang |
      | student1 | de   |
    When I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=example'>Example Page</a></p>"
    And I press "Save changes"
    And I log out
    And I log in as "student1"
    And I am on site homepage
    And I click on "Example Page" "link"
    Then I should see "Dies ist eine h2 Überschrift"
    And I should see "This is a h2 heading"

  ################################################################################
  # Setting "Clean HTML code"
  ################################################################################

  Scenario: Check setting "Don't clean HTML code"
    Given the following config values are set as admin:
      | config    | value | plugin           |
      | cleanhtml | 2     | local_staticpage |
    When I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=example'>Example Page</a></p>"
    And I press "Save changes"
    And I click on "Example Page" "link"
    Then "//div[@id='region-main']//div//iframe" "xpath_element" should exist

  Scenario: Check setting "Clean HTML code"
    Given the following config values are set as admin:
      | config    | value | plugin           |
      | cleanhtml | 1     | local_staticpage |
    When I log in as "admin"
    And I am on site homepage
    And I turn editing mode on
    And I add the "Text..." block
    And I set the field "Content" to "<p><a href='/local/staticpage/view.php?page=example'>Example Page</a></p>"
    And I press "Save changes"
    And I click on "Example Page" "link"
    Then "//div[@id='region-main']//div//iframe" "xpath_element" should not exist

  ################################################################################
  # Setting "Use style tag"
  ################################################################################

  Scenario: Check setting "Use style tag"
    Given the following config values are set as admin:
      | config   | value | plugin           |
      | styletag | 1     | local_staticpage |
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/styletest.html" file to "Documents" filemanager
    And I press "Save changes"
    And I visit "/local/staticpage/view.php?page=styletest"
    Then "//head/style[contains(., 'h1.styletest')]" "xpath_element" should exist

  Scenario: Check setting "Don't use style tag"
    Given the following config values are set as admin:
      | config   | value | plugin           |
      | styletag | 2     | local_staticpage |
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/styletest.html" file to "Documents" filemanager
    And I press "Save changes"
    And I visit "/local/staticpage/view.php?page=styletest"
    Then "//head/style[contains(., 'h1.styletest')]" "xpath_element" should not exist
    And I should see "This is the style test page"

  ################################################################################
  # Setting "Use link tags"
  ################################################################################

  Scenario: Check setting "Use link tags as they are"
    # The HTML code is cleaned to verify that this does not affect the link tags.
    Given the following config values are set as admin:
      | config    | value | plugin           |
      | cleanhtml | 1     | local_staticpage |
      | linktags  | 2     | local_staticpage |
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/linktest.html" file to "Documents" filemanager
    And I press "Save changes"
    And I visit "/local/staticpage/view.php?page=linktest"
    Then "//head/link[@href='linktest-safe.css']" "xpath_element" should exist
    And "//head/link[@href='linktest-onload.css' and @onload]" "xpath_element" should exist
    And "//head/link[@rel='alternate stylesheet' and @href='linktest-alternate.css' and @title='Alternate']" "xpath_element" should exist
    And "//head/link[@href='javascript:void(0)']" "xpath_element" should exist
    And "//head/link[@href='linktest-unknownrel.css']" "xpath_element" should exist
    And "//head/link[@href='linktest-body.css']" "xpath_element" should not exist

  Scenario: Check setting "Use link tags, but clean link tags"
    # The HTML code is not cleaned to verify that this does not affect the link tags.
    Given the following config values are set as admin:
      | config    | value | plugin           |
      | cleanhtml | 2     | local_staticpage |
      | linktags  | 1     | local_staticpage |
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/linktest.html" file to "Documents" filemanager
    And I press "Save changes"
    And I visit "/local/staticpage/view.php?page=linktest"
    Then "//head/link[@rel='stylesheet' and @href='linktest-safe.css' and @media='screen']" "xpath_element" should exist
    And "//head/link[@href='linktest-onload.css']" "xpath_element" should exist
    And "//head/link[@onload]" "xpath_element" should not exist
    And "//head/link[@rel='alternate stylesheet' and @href='linktest-alternate.css' and @title='Alternate']" "xpath_element" should exist
    And "//head/link[@href='javascript:void(0)']" "xpath_element" should not exist
    And "//head/link[@href='linktest-unknownrel.css']" "xpath_element" should not exist
    And "//head/link[@href='linktest-body.css']" "xpath_element" should not exist

  Scenario: Check setting "Don't use link tags"
    Given the following config values are set as admin:
      | config    | value | plugin           |
      | cleanhtml | 2     | local_staticpage |
      | linktags  | 3     | local_staticpage |
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/linktest.html" file to "Documents" filemanager
    And I press "Save changes"
    And I visit "/local/staticpage/view.php?page=linktest"
    Then "//head/link[contains(@href, 'linktest-')]" "xpath_element" should not exist
    And "//head/link[@href='javascript:void(0)']" "xpath_element" should not exist

  ################################################################################
  # Setting "Force Apache mod_rewrite"
  ################################################################################

  # These scenarios only check the redirect which the plugin itself sends to the canonical clean URL.
  # They do not check if the static page is delivered at the clean URL as this depends on the rewrite rule in the
  # webserver configuration which is neither present in the PHP built-in web server nor in a standard Apache webserver.

  Scenario: Check if the static page is delivered at the standard URL if Apache mod_rewrite is not forced
    Given the following config values are set as admin:
      | config        | value | plugin           |
      | apacherewrite | 0     | local_staticpage |
    When I visit "/local/staticpage/view.php?page=example"
    Then the url should match "/local/staticpage/view\.php\?page=example$"
    And I should see "Lorem ipsum dolor sit amet"

  Scenario Outline: Check if the standard URL is redirected to the canonical clean URL if Apache mod_rewrite is forced
    Given the following config values are set as admin:
      | config        | value | plugin           |
      | apacherewrite | 1     | local_staticpage |
    When I visit "<url>"
    Then the url should match "/static/example\.html$"

    Examples:
      | url                                                         |
      | /local/staticpage/view.php?page=example                     |
      | /local/staticpage/view.php?page=example&foo=bar             |
      | /local/staticpage/view.php/static/example.html?page=example |

  ################################################################################
  # Setting "Check availability" and "List of static pages"
  ################################################################################

  # The scenarios with the availability check enabled let the Moodle site make HTTP requests to its own wwwroot while
  # it renders the list of static pages. This requires a webserver which can handle several requests in parallel.
  # The PHP built-in web server which is used by moodle-plugin-ci handles only one request at a time by default, the
  # requests then run into their timeout. Therefore, the GitHub workflow of this plugin sets behat-webserver-workers.
  # The scenarios do not check the availability result of the clean URLs as it depends on the webserver: A standard
  # Apache webserver without the rewrite rule for the clean URLs returns HTTP 404, but the PHP built-in web server falls
  # back to Moodle's index.php for missing files and thus returns the frontpage with HTTP 200.
  # The non-2xx path and the connection error path of the availability check are covered with config values which
  # are only respected on the Behat test site and which let the availability check run against a URL which returns
  # HTTP 404 on every webserver or which refuses the connection.

  Scenario: Check if the availability of the URLs of a document is checked in the list of static pages
    Given the following config values are set as admin:
      | config            | value | plugin           |
      | checkavailability | 1     | local_staticpage |
    When I log in as "admin"
    And I navigate to "List of static pages" in site administration
    Then I should see "example.html" in the "#region-main li[data-filename='example.html'] .card-header" "css_element"
    And I should see "Standard URL" in the "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element"
    And I should see "Available" in the "#region-main li[data-filename='example.html'] [data-urltype='standard'] .badge" "css_element"
    And "#region-main li[data-filename='example.html'] [data-urltype='standard'] .badge.badge-success" "css_element" should exist
    And I should see "/local/staticpage/view.php?page=example" in the "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element"
    And I should see "The static page is available and can be linked to at this URL." in the "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element"
    And I should see "Clean URL" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite']" "css_element"
    And I should see "/static/example.html" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite']" "css_element"

  Scenario: Check if the availability check reports that a login is required if force login is enabled for static pages
    Given the following config values are set as admin:
      | config            | value | plugin           |
      | checkavailability | 1     | local_staticpage |
      | forcelogin        | 1     | local_staticpage |
    When I log in as "admin"
    And I navigate to "List of static pages" in site administration
    # The availability check is sent without a session and is redirected to the login page which returns HTTP 200.
    Then I should see "Login required" in the "#region-main li[data-filename='example.html'] [data-urltype='standard'] .badge" "css_element"
    And "#region-main li[data-filename='example.html'] [data-urltype='standard'] .badge.badge-info" "css_element" should exist
    And "#region-main li[data-filename='example.html'] [data-urltype='standard'] .badge.badge-success" "css_element" should not exist
    And I should see "to logged in users only because force login is enabled for static pages" in the "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element"

  Scenario: Check if a non-2xx response of the availability check does not skip the checks of the remaining URLs
    # The behat_checkavailabilityforcefail config value is only respected on the Behat test site.
    # It lets each availability check run against a URL which returns HTTP 404.
    Given the following config values are set as admin:
      | config                           | value | plugin           |
      | checkavailability                | 1     | local_staticpage |
      | behat_checkavailabilityforcefail | 1     | local_staticpage |
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/styletest.html" file to "Documents" filemanager
    And I press "Save changes"
    And I navigate to "List of static pages" in site administration
    Then I should see "Not available - Non-2xx" in the "#region-main li[data-filename='example.html'] [data-urltype='standard'] .badge" "css_element"
    And "#region-main li[data-filename='example.html'] [data-urltype='standard'] .badge.badge-danger" "css_element" should exist
    And I should see "due to a non-2xx HTTP status code. Perhaps there is something wrong with your webserver configuration." in the "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element"
    And I should see "Not available - Non-2xx" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite'] .badge" "css_element"
    And "#region-main li[data-filename='example.html'] [data-urltype='rewrite'] .badge.badge-warning" "css_element" should exist
    And I should see "due to a non-2xx HTTP status code. Perhaps there is something wrong with your webserver or mod_rewrite configuration." in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite']" "css_element"
    # All remaining URLs are checked as well.
    And I should see "Not available - Non-2xx" in the "#region-main li[data-filename='styletest.html'] [data-urltype='standard'] .badge" "css_element"
    And I should see "Not available - Non-2xx" in the "#region-main li[data-filename='styletest.html'] [data-urltype='rewrite'] .badge" "css_element"
    And I should not see "Not checked" in the "#region-main" "css_element"

  Scenario: Check if a connection error of the availability check skips the checks of the remaining URLs
    # The behat_checkavailabilityforceerror config value is only respected on the Behat test site.
    # It lets each availability check run against a URL which refuses the connection.
    Given the following config values are set as admin:
      | config                            | value | plugin           |
      | checkavailability                 | 1     | local_staticpage |
      | behat_checkavailabilityforceerror | 1     | local_staticpage |
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/styletest.html" file to "Documents" filemanager
    And I press "Save changes"
    And I navigate to "List of static pages" in site administration
    # The standard URL of example.html is the first URL which is checked.
    Then I should see "Not available - Error" in the "#region-main li[data-filename='example.html'] [data-urltype='standard'] .badge" "css_element"
    And "#region-main li[data-filename='example.html'] [data-urltype='standard'] .badge.badge-danger" "css_element" should exist
    And I should see "either because of a connection error or because the webserver responds slower than the configured timeout. Perhaps there is something wrong with your webserver configuration." in the "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element"
    # All remaining URLs are skipped.
    And I should see "Not checked - Skipped" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite'] .badge" "css_element"
    And "#region-main li[data-filename='example.html'] [data-urltype='rewrite'] .badge.badge-secondary" "css_element" should exist
    And I should see "already failed with a connection error or a timeout" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite']" "css_element"
    And I should see "Not checked - Skipped" in the "#region-main li[data-filename='styletest.html'] [data-urltype='standard'] .badge" "css_element"
    And I should see "Not checked - Skipped" in the "#region-main li[data-filename='styletest.html'] [data-urltype='rewrite'] .badge" "css_element"
    And I should see "/local/staticpage/view.php?page=styletest" in the "#region-main li[data-filename='styletest.html'] [data-urltype='standard']" "css_element"
    And I should see "/static/styletest.html" in the "#region-main li[data-filename='styletest.html'] [data-urltype='rewrite']" "css_element"

  Scenario: Check if the URLs of a document are listed with their availability status in the list of static pages if the availability check is disabled
    Given the following config values are set as admin:
      | config            | value | plugin           |
      | checkavailability | 2     | local_staticpage |
    When I log in as "admin"
    And I navigate to "List of static pages" in site administration
    Then I should see "example.html" in the "#region-main li[data-filename='example.html'] .card-header" "css_element"
    And I should see "Standard URL" in the "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element"
    And I should see "Not checked" in the "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element"
    And I should see "/local/staticpage/view.php?page=example" in the "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element"
    And I should see "checking availability is disabled" in the "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element"
    And I should see "Clean URL" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite']" "css_element"
    And I should see "Not checked" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite']" "css_element"
    And I should see "/static/example.html" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite']" "css_element"
    And I should see "checking availability is disabled" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite']" "css_element"
    And I should not see "Invalid pagename" in the "#region-main" "css_element"

  Scenario: Check if only the clean URL of a document is listed in the list of static pages if Apache mod_rewrite is forced
    # The availability check is disabled as it is not needed for this scenario.
    Given the following config values are set as admin:
      | config            | value | plugin           |
      | checkavailability | 2     | local_staticpage |
      | apacherewrite     | 1     | local_staticpage |
    When I log in as "admin"
    And I navigate to "List of static pages" in site administration
    Then "#region-main li[data-filename='example.html'] [data-urltype='standard']" "css_element" should not exist
    And I should not see "Standard URL" in the "#region-main" "css_element"
    And I should not see "/local/staticpage/view.php?page=example" in the "#region-main" "css_element"
    And I should see "Clean URL" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite']" "css_element"
    And I should see "/static/example.html" in the "#region-main li[data-filename='example.html'] [data-urltype='rewrite']" "css_element"

  Scenario Outline: Check if documents with an invalid pagename are marked as invalid in the list of static pages
    # The availability check is disabled as it is not needed for this scenario.
    Given the following config values are set as admin:
      | config            | value | plugin           |
      | checkavailability | 2     | local_staticpage |
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/<page>.html" file to "Documents" filemanager
    And I press "Save changes"
    And I navigate to "List of static pages" in site administration
    Then I should see "Invalid pagename" in the "#region-main li[data-filename='<page>.html']" "css_element"
    And I should see "This pagename contains characters which are not supported in pagenames" in the "#region-main li[data-filename='<page>.html']" "css_element"
    And I should not see "/local/staticpage/view.php?page=<page>" in the "#region-main" "css_element"
    And I should not see "/static/<page>.html" in the "#region-main" "css_element"
    And I should not see "Invalid pagename" in the "#region-main li[data-filename='example.html']" "css_element"
    And I should see "/local/staticpage/view.php?page=example" in the "#region-main li[data-filename='example.html']" "css_element"
    And I should see "/static/example.html" in the "#region-main li[data-filename='example.html']" "css_element"

    Examples:
      | page             |
      | invalid pagename |
      | invalid.pagename |
