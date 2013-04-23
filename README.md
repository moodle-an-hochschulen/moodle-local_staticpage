moodle-local_staticpage
=======================
Moodle plugin which displays static information pages which exist outside any course, imprint or faq pages for example, complete with Moodle navigation and theme.


Requirements
============
This plugin requires Moodle 2.4+


Changes
=======
2013-04-23 - Fix problems on windows systems
2013-04-22 - Bugfix: Language switcher didn't work anymore on a static page
2013-03-22 - Bugfix: Static page wouldn't show even if everything was configured correctly; Please notice that apache rewrite rule has changed
2013-03-18 - Code cleanup according to moodle codechecker
2013-02-18 - Check compatibility for Moodle 2.4, fix language string names to comply with language string name convention
2013-01-21 - Fix a flaw in README file regarding filename examples
2013-01-21 - Fix a flaw in README file regarding Apache mod_rewrite
2013-01-21 - Migrate plugin settings from config.php to a settings page within Moodle
2012-09-26 - Replace deprecated get_context_instance function
2012-06-25 - Update version.php for Moodle 2.3
2012-06-01 - Initial version


Installation
============
Install the plugin like any other plugin to folder
/local/staticpage

See http://docs.moodle.org/24/en/Installing_plugins for details on installing Moodle plugins


Usage & Settings
================
The local_staticpage plugin is designed to fetch a static HTML document from disk, enrich it with Moodle navigation and theme and deliver it as a standard Moodle page which exists outside any course. After installing local_staticpage, the plugin should be configured.
To configure the plugin and its behaviour, please visit Plugins -> Local plugins -> Static pages.

There, you find three sections:

1. Document directory
---------------------
In this section, you define the directory where the document files are. local_staticpage takes every file in this directory with a .html filename extension, takes the file's name and content and creates a static page out of it.

Example:
The document directory /var/www/files/moodledata/staticpage contains the files foo.bar and faq.html. local_staticpage looks at the directory and finds two files. File foo.bar is ignored by local_staticpage because it doesn't have the right filename extension. File faq.html will be served as static page with the page name "faq".

After finding a file with a .html filename extension, local_staticpage takes a second look at the filename to see if it can find another filename extension. If there is one, it is used as language of the static page.

Example:
The document directory /var/www/files/moodledata/staticpage contains the files imprint.en.html, impressum.de.html and faq.html. local_staticpage looks at the filename extensions and serves the static pages as follows:
File imprint.en.html will be served as static page with the page name "imprint", but only when the current language of the user is english.
File impressum.de.html will be served as static page with the page name "impressum", but only when the current language of the user is german.
File faq.html will be served as static page with the page name "faq" regardless of the current language of the user.

local_staticpage doesn't know anything about connections with regard to contents between document files. If you want to serve static pages which are translated into multiple languages and which should be switchable with the Moodle language switcher, you are welcome to create symbolic links in your document directory. This has been tested on Unix-like servers, see http://en.wikipedia.org/wiki/Symbolic_link#POSIX_and_Unix-like_operating_systems for details.

Example:
-rw-r-----.  1 root   apache 1700 Jan  9 12:20 impressum.de.html
-rw-r-----.  1 root   apache   15 Jan 23 22:16 impressum.en.html -> imprint.en.html
-rw-r-----.  1 root   apache   17 Jan 23 22:15 imprint.de.html -> impressum.de.html
-rw-r-----.  1 root   apache 1658 Jan  9 12:20 imprint.en.html
What you see here is a directory listing with two documents and two symbolic links. local_staticpage will serve the imprint page in the language of the user, regardless of the pagename with which it has been called.


2. Force Apache mod_rewrite
---------------------------
With this setting, you can configure local_staticpage to only serve static pages on a clean URL, using Apache's mod_rewrite module. See "Apache mod_rewrite" section below for details.


3. Document list
----------------
As soon as you have configured the document directory setting and saved your settings, this list shows all files which have been found in the document directory and their URLs.


Create documents
================
Now, you have to create the html documents and put them in the document directory.
For each static page you want to serve, create a HTML document in the document directory, named as <pagename>.html (for all languages) or <pagename>.<language>.html (for only one language)

As the local_staticpage's HTML reader (DOM parser) is quite dumb, there is a proposed structure for the html documents:

<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Imprint</title>
</head>
<body>
        <h1>Imprint</h1>
        [Your content goes here]
</body>
</html>

local_staticpage will use the first <h1> tag as heading, title and breadcrumb of the resulting Moodle page.
If there is no <h1> tag, the heading, title and breadcrumb of the resulting Moodle page will be set to the pagename derived from the document filename.

Writing your HTML documents, you could omit the whole <head> section, but:
- The <meta> tag is neccessary if you want to use UTF-8 characters in your html document, otherwise they will become crippled when the document is parsed by local_staticpage.
- The <title> tag is useful when you want to use the html document in any other way, but local_staticpage will ignore it completely.

Please create one html document for every language you want to support. Moodle multilanguage tags are not supported in your HTML code.

If you want to include images into your static page, please do yourself a favour and link to them with absolute URLs, not relative URLs.


Apache mod_rewrite
==================

Using mod_rewrite
-----------------
local_staticpage is able to use Apache's mod_rewrite module to provide static pages on a clean and understandable URL.

Please add the following to your Apache configuration or your .htaccess file in the Moodle directory:

RewriteEngine On
RewriteRule ^/static/(.*)\.html$ /local/staticpage/view.php?page=$1&%{QUERY_STRING} [L]

Now, the static pages from the above example are available on
http://www.yourmoodle.com/static/imprint.html
http://www.yourmoodle.com/static/impressum.html
http://www.yourmoodle.com/static/faq.html

You can now create links to these URLs in a Moodle HTML Block, in your Moodle theme footer and so on.


Not using mod_rewrite
---------------------
If you don't want or are unable to use Apache's mod_rewrite, local_staticpage will still work.

The static pages from the above example are available on
http://www.yourmoodle.com/local/staticpage/view.php?page=imprint
http://www.yourmoodle.com/local/staticpage/view.php?page=impressum
http://www.yourmoodle.com/local/staticpage/view.php?page=faq

These URLs aren't as catchy as with mod_rewrite, but they work in exactly the same manner.

You can now create links to these URLs in a Moodle HTML Block, in your Moodle theme footer and so on.


Theme
=====
The local_staticpage plugin uses the "standard" pagelayout of your theme by default for creating the Moodle pages. For most themes, this works well.

If you want to style static pages in any special way, you could extend your theme with a "staticpage" pagelayout. local_staticpage will use this pagelayout as soon as it exists in your /theme/<yourtheme>/config.php:

$THEME->layouts = array(
	[...]
	'staticpage' => array(
		'file' => 'general.php',
		'regions' => array('side-pre'),
		'defaultregion' => 'side-pre',
		'options' => array('langmenu'=>true)
	);

With this pagelayout, you could use a CSS cascade like this to style static pages content in some special way:

body.pagelayout-staticpage ... { }


Security considerations
=======================
local_staticpage does NOT check the static HTML documents for any malicious code, neither for malicious HTML code which will be delivered directly to the user's browser, nor for malicious PHP code which could break DOM parsing when processing the HTML document on the server.
Therefore, please make sure that your HTML code is well-formed and that only authorized and briefed users have write access to the document directory.


Further information
===================
local_staticpage is found in the Moodle Plugins repository: http://moodle.org/plugins/view.php?plugin=local_staticpage

Report a bug or suggest an improvement: https://github.com/abias/moodle-local_staticpage/issues


Moodle release support
======================
Due to limited ressources, local_staticpage is only maintained for the most recent major release of Moodle. However, previous versions of this plugin which work in legacy major releases of Moodle are still available as-is without any further updates in the Moodle Plugins repository.

There may be several weeks after a new major release of Moodle has been published until I can do a compatibility check and fix problems if necessary. If you encounter problems with a new major release of Moodle - or can confirm that local_staticpage still works with a new major relase - please let me know on https://github.com/abias/moodle-local_staticpage/issues


Right-to-left support
=====================
This plugin has not been tested with Moodle's support for right-to-left (RTL) languages.
If you want to use this plugin with a RTL language and it doesn't work as-is, you are free to send me a pull request on
github with modifications.


Copyright
=========
Alexander Bias, University of Ulm
