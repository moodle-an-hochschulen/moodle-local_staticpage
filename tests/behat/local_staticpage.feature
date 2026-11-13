@local @local_staticpage @javascript @_file_upload
# @javascript is only used for the background but cannot be added selectively to it.
Feature: Using static pages
  In order to be able to use the static pages
  As admin
  I need to be able to configure the local_staticpage plugin

  Background:
    Given the following config values are set as admin:
      | enablemyhome | 1 |
      | forcelogin   | 0 |
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
  # Setting "Check availability" and "List of static pages"
  ################################################################################

    # This scenario falls into a timeout that might be caused by CURL, so its temporarily commented out.
#  Scenario: Check if the uploaded page is listed under "List of static pages"
#    When I log in as "admin"
#    And I navigate to "List of static pages" in site administration
#    Then I should see "example.html"

  Scenario Outline: Check if documents with an invalid pagename are marked as invalid in the list of static pages
    # The availability check is disabled as it might run into a timeout (see the commented out scenario above).
    Given the following config values are set as admin:
      | config            | value | plugin           |
      | checkavailability | 2     | local_staticpage |
    When I log in as "admin"
    And I navigate to "Static Pages" in site administration
    And I upload "local/staticpage/tests/fixtures/<page>.html" file to "Documents" filemanager
    And I press "Save changes"
    And I navigate to "List of static pages" in site administration
    Then I should see "Invalid pagename" in the "//*[@id='region-main']//li[contains(., '<page>.html')]" "xpath_element"
    And I should see "This pagename contains characters which are not supported in pagenames" in the "//*[@id='region-main']//li[contains(., '<page>.html')]" "xpath_element"
    And I should not see "/local/staticpage/view.php?page=<page>" in the "#region-main" "css_element"
    And I should not see "/static/<page>.html" in the "#region-main" "css_element"
    And I should not see "Invalid pagename" in the "//*[@id='region-main']//li[contains(., 'example.html')]" "xpath_element"
    And I should see "/local/staticpage/view.php?page=example" in the "//*[@id='region-main']//li[contains(., 'example.html')]" "xpath_element"
    And I should see "/static/example.html" in the "//*[@id='region-main']//li[contains(., 'example.html')]" "xpath_element"

    Examples:
      | page             |
      | invalid pagename |
      | invalid.pagename |
