moodle-local_staticpage
=======================

Moodle plugin which displays static information pages which exist outside any course, imprint or faq pages for example, complete with Moodle navigation and theme.


Requirements
------------

This plugin requires Moodle 3.2+


Installation
------------

Install the plugin like any other plugin to folder
/local/staticpage

See http://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins


Usage & Settings
----------------

The local_staticpage plugin is designed to deliver static HTML documents, enriched with Moodle navigation and theme as a standard Moodle page which exist outside any course. After installing local_staticpage, the plugin has to be configured.
To configure the plugin and its behaviour, please visit Site administration -> Static Pages.

There, you find multiple settings pages:

### 1. Documents

On this page, you upload the document files you want to serve as static pages. The filepicker accepts files with .html filename extensions. For each static page you want to serve, upload a HTML document, named as [pagename].html. local_staticpage then uses this filename as pagename.

Example:
You upload a file named faq.html. This file will be served as static page with the page name "faq".

Valid filenames:
Please note that not all symbols which are allowed in the filenames in the filepicker are supported / suitable for pagenames.
Please make sure that your filenames only contain lowercase alphanumeric characters and the - (hypen) and _ (underscore) symbols.
Please note that the filepicker on this settings page does not only allow you to upload .html files but also to upload .htm files due to the way the Moodle filepicker is built internally. local_staticpage does its best to change the suffix of a .htm file to .html after you save the settings page.

### 2. Settings

On this page, you can configure several aspects of local_staticpage's behaviour.

#### 2.1. Data source of document title

By default, local_staticpage will use the first `<h1>` tag as document title, document heading and breadcrumb item title of the resulting static page.
In this section, you can change this behaviour to using the first `<title>` tag for each of these.

Please note that if local_staticpage doesn't find the configured (`<h1>` or `<title>`) tag, it will derive the document title from the document filename.

#### 2.2. Force Apache mod_rewrite

With this setting, you can configure local_staticpage to only serve static pages on a clean URL, using Apache's mod_rewrite module. See "Apache mod_rewrite" section below for details.

#### 2.3. Force login

With this setting, you can configure local_staticpage to only serve static pages to logged in users or also to service static pages non-logged in visitors.

This behaviour can be set specifically for static pages or can be set to respect Moodle's global forcelogin ($CFG->forcelogin) setting.

#### 2.4. Process Content

In this section, you can configure if Moodle filters should be processed when serving a static page's content. You can use local_staticpage completely without multilanguage or filter support. But when you need multilanguage or filter support, you can set this setting to yes and make use in your static page files of any Moodle filter which is enabled on system level. Please see https://docs.moodle.org/en/Filters or https://docs.moodle.org/en/Multi-language_content_filter for details.

In this section, you can also configure if the static page's HTML code should be cleaned. If this setting is set to yes, local_staticpage will use a Moodle library function to remove unclean HTML code and special tags like `<iframe>`. If this setting is set to no, local_staticpage will trust the HTML code in your static page's file and will just pass it on to the browser.

### 3. List of static pages

On this page, there is a list which shows all static pages which have been uploaded into the static pages document area and their URLs.

Additionally, the page list checks each static page if a browser is actually able to download and view it. If this isn't possible, the static page is marked with an error message in the page list.


Creating static page documents
------------------------------

As local_staticpage's HTML reader (DOM parser) is quite dumb, there is a proposed structure for the html documents:

`<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Imprint</title>
</head>
<body>
        <h1>Imprint</h1>
        [Your content goes here]
</body>
</html>`


Please note that the `<meta>` tag is neccessary if you want to use UTF-8 characters in your html document, otherwise they will become crippled when the document is parsed by local_staticpage.


Styling static pages
--------------------

If you want to style your static page with CSS in any special way, you can include a `<style>` tag into the `<head>` section of your HTML document. The content of this style tag will be inserted into Moodle's HTML head.


Adding images to static pages
-----------------------------

If you want to include images into your static page, you cannot just upload them in local_staticpage's filepicker. You have to upload them somewhere else. local_staticpage is not capable of hosting / serving image files. Linking to image files, please do yourself a favour and link to them with absolute URLs, not relative URLs.


Apache mod_rewrite
------------------

### Using mod_rewrite

local_staticpage is able to use Apache's mod_rewrite module to provide static pages on a clean and understandable URL.

If you are running Moodle in the root of your webserver, please add the following to your Apache configuration or your .htaccess file in the Moodle directory:

RewriteEngine On
RewriteRule ^static/(.*)\.html$ /local/staticpage/view.php?page=$1&%{QUERY_STRING} [L]

However, in some Apache configurations the following rule will work (without the leading slash - for details, please refer to http://httpd.apache.org/docs/current/mod/mod_rewrite.html#rewriterule):

RewriteEngine On
RewriteRule ^static/(.*)\.html$ /local/staticpage/view.php?page=$1&%{QUERY_STRING} [L]

Now, the static pages are available on
http://www.yourmoodle.com/static/[pagename].html


If you are running Moodle in a subdirectory on your webserver, please add the following to your Apache configuration or your .htaccess file in the Moodle directory:

RewriteEngine On
RewriteRule ^yoursubdirectory/static/(.*)\.html$ /yoursubdirectory/local/staticpage/view.php?page=$1&%{QUERY_STRING} [L]

However, in some Apache configurations the following rule will work (without the leading slash - for details, please refer to http://httpd.apache.org/docs/current/mod/mod_rewrite.html#rewriterule):

RewriteEngine On
RewriteRule ^yoursubdirectory/static/(.*)\.html$ /yoursubdirectory/local/staticpage/view.php?page=$1&%{QUERY_STRING} [L]

Now, the static pages are available on
http://www.yourmoodle.com/yoursubdirectory/static/[pagename].html


You can now create links to these URLs in a Moodle HTML Block, in your Moodle theme footer and so on.


### Not using mod_rewrite

If you don't want or are unable to use Apache's mod_rewrite, local_staticpage will still work.

The static pages are then available on
http://www.yourmoodle.com/local/staticpage/view.php?page=[pagename]

These URLs aren't as catchy as with mod_rewrite, but they work in exactly the same manner.

Please note:
Here, you have to omit the ".html" extension in the pagename.
The URL http://www.yourmoodle.com/local/staticpage/view.php?page=[pagename].html won't work.
This has technical reasons as the pagename parameter is cleaned to only contain alphanumeric characters and the - (hypen) and _ (underscore) symbols, but not period characters.

You can now create links to these URLs in a Moodle HTML Block, in your Moodle theme footer and so on.


Theme
-----

The local_staticpage plugin uses the "standard" pagelayout of your theme by default for creating the Moodle pages. For most themes, this works well.

If you want to style static pages in any special way, you could use a CSS cascade to style static pages content in some special way:

If you are using Apache mod_rewrite URLs, you can use this CSS selector:
body.path-static ... { }

If you are not using Apache mod_rewrite URLs, you can use this CSS selector:
body.path-local-staticpage ... { }


Add blocks to static pages
--------------------------

The local_staticpage plugin was not intended to show blocks on the static pages. However, it is possible. You have to enable page editing somewhere else in Moodle (on your MyMoodle page or on a course page, for example) and go to your static page. Now you see the standard "Add block" menu and can add blocks to the static page. Additionally, if you click on the block's gear icon, you can control if the block is shown only on the static page the block was added to or on all static pages.


Security considerations
-----------------------

Apart from the option to clean HTML code which you can set to yes, local_staticpage does NOT check the static HTML documents for any malicious code, neither for malicious HTML code which will be delivered directly to the user's browser, nor for malicious PHP code which could break DOM parsing when processing the HTML document on the server.

Therefore, please make sure that your HTML code is well-formed and that only authorized and briefed users upload static page documents on local_staticpage's settings page.


Upgrading from previous versions
--------------------------------

On 2016-02-10, we changed the way the local_staticpage plugin works fundamentally. Until then, there was a documents directory within the Moodledata directory on disk which kept the static page document files. Now, as you know, these files are placed in a filearea within Moodle.

For admins upgrading from a version before this change to a recent version of the plugin, it is important to know:

* Within the plugin upgrade process, the static page document files are copied automatically to the new filearea within Moodle. After the plugin has been upgraded, you can delete the legacy documents directory manually.
* In previous versions, there was a multilanguage support for static pages which worked on the static page document's filename. This mechanism is not there anymore. If you were using this multilanguage feature, you have to rebuild your static pages with Moodle's multilanguage filter (see above) placing all language into one static page document as well as remove the language identification code from the filenames.
* In previous versions, there was a document list on the plugin's settings page which listed all documents which were found in the document's directory. This list was there to check that Moodle recognizes all static page documents on disk. As the static page documents are now located within Moodle and thus there aren't any problems with reading files from disk anymore, this list was removed.


Motivation for this plugin
--------------------------

We have seen Moodle installations where there was a need for displaying static information like an imprint, a faq or a contact page and this information couldn't be added everything to the frontpage. As Moodle doesn't have a "page" concept, admins started to create courses, place their information within these courses, open guest access to the course and link to this course from HTML blocks or the custom menu.

We thought that this course overhead doesn't make sense, so we created local_staticpage. It is not meant as a fully featured content management solution, especially as you have to work with raw HTML, but it is quite handy for experienced admins for creating some few static pages within Moodle.


Further information
-------------------

local_staticpage is found in the Moodle Plugins repository: https://moodle.org/plugins/view/local_staticpage

Report a bug or suggest an improvement: https://github.com/moodleuulm/moodle-local_staticpage/issues


Moodle release support
----------------------

Due to limited resources, local_staticpage is only maintained for the most recent major release of Moodle. However, previous versions of this plugin which work in legacy major releases of Moodle are still available as-is without any further updates in the Moodle Plugins repository.

There may be several weeks after a new major release of Moodle has been published until we can do a compatibility check and fix problems if necessary. If you encounter problems with a new major release of Moodle - or can confirm that local_staticpage still works with a new major relase - please let us know on https://github.com/moodleuulm/moodle-local_staticpage/issues


Right-to-left support
---------------------

This plugin has not been tested with Moodle's support for right-to-left (RTL) languages.
If you want to use this plugin with a RTL language and it doesn't work as-is, you are free to send us a pull request on
github with modifications.


PHP7 Support
------------

Since Moodle 3.0, Moodle core basically supports PHP7.
Please note that PHP7 support is on our roadmap for this plugin, but it has not yet been thoroughly tested for PHP7 support and we are still running it in production on PHP5.
If you encounter any success or failure with this plugin and PHP7, please let us know.


Copyright
---------

Ulm University
kiz - Media Department
Team Web & Teaching Support
Alexander Bias
