moodle-local_staticpage
=======================
Moodle plugin which displays information pages which exist outside any course, imprint or faq pages for example, complete with Moodle navigation and theme.


Requirements
============
This plugin requires Moodle 2.1+


Changes
=======
2012-06-01 - Initial version


Installation
============
Install the plugin like any other plugin to folder
/local/staticpage

See http://docs.moodle.org/22/en/Installing_plugins for details on installing Moodle plugins


Usage & Settings
================
The local_staticpage plugin is designed to fetch a static html document from disk, enrich it with Moodle navigation and theme and deliver it as a standard Moodle page which exists outside any course. To achieve this, the following steps are needed after installing the plugin:

1. Set documents path (optional)
--------------------------------
By setting the optional variable $CFG->staticdocumentspath in config.php, you can set the path where local_staticpage looks for the static html documents.
If the variable is not set, local_staticpage uses $CFG->staticdocumentspath = $CFG->dataroot.'/staticpages/' by default, which means that you have to put your html documents in a "staticpages" subfolder of your moodledata folder.


2. Specify valid documents
--------------------------
By setting the obligatory variable $CFG->staticvaliddocuments in config.php, you specify which documents are available as static pages.

local_staticpage is able to deliver documents depending on the language of the user. Here, you specify which html document will be available on which URL. 
If you just want to support english URL names, this will be a 1:1 mapping.
If you want to support additional URL names in other languages, this will be a n:1 mapping. In this case, just include an additional array element.

Mapping definition:
Array key: URL part which should be valid
Array value: html document which should be delivered

Example in config.php:
$CFG->staticvaliddocuments = array ('impressum' => 'imprint', // URL part which is understandable for german users, mapped to html document "imprint" 
					'imprint' => 'imprint', // URL part which is understandable for english users, mapped to html document "imprint"
					'faq' => 'faq'); // German // URL part which is (hopefully) understandable in all languages, mapped to html document "faq"

In this example, when document "impressum" is called, local_staticpage will look for a html document "imprint".
When document "imprint" is called, local_staticpage will look for a html document "imprint", too.
And when document "faq" is called, local_staticpage will look for a html document "faq".


3. Create documents
-------------------
Now, you have to create the html documents and put them in the documents path. 
For each language which you want to support, create a html document in $CFG->staticdocumentspath, named as <documentname>.<language>.html

As the local_staticpage's DOM parser is quite dumb, there is a proposed structure for the html documents:

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
If there is no <h1> tag, the heading, title and breadcrumb of the resulting Moodle page will be set to the URL part string.

You could omit the whole <head> section, but:
- The <meta> tag is neccessary if you want to use UTF-8 characters in your html document, otherwise they will become crippled when the document is parsed by local_staticpage.
- The <title> tag is useful when you want to use the html document in any other way, but local_staticpage will ignore it completely.

Please create one html document for every language you want to support. Moodle multilanguage tags are not supported in html documents.

Continuing the above example for english and german language, you have to create four documents:
imprint.en.html
imprint.de.html
faq.en.html
faq.de.html

According to the specification of valid documents above, the following documents are delivered:
URL part "imprint" called + User language "en" -> imprint.en.html delivered
URL part "imprint" called + User language "de" -> imprint.de.html delivered
URL part "impressum" called + User language "en" -> imprint.en.html delivered
URL part "impressum" called + User language "de" -> imprint.de.html delivered
URL part "faq" called + User language "en" -> faq.en.html delivered
URL part "faq" called + User language "de" -> faq.de.html delivered


4. Add rewrite rule to Apache webserver
---------------------------------------
local_staticpage uses Apache's mod_rewrite to provide static pages on a clean and understandable URL. 

Please add the following to your Apache configuration or your .htaccess file in the Moodle directory:

RewriteEngine On
RewriteRule ^/static/(.*)\.html /local/staticpage/staticpage.php?page=$1&%{QUERY_STRING} [L]

Now, the static pages from the above example are available on
http://www.yourmoodle.com/static/imprint
http://www.yourmoodle.com/static/impressum
http://www.yourmoodle.com/static/faq

You can now create links to these URLs in a Moodle HTML Block, in your Moodle theme footer and so on.


4a. Use local_staticpage without rewrite rule in Apache webserver (optional)
----------------------------------------------------------------------------
If you don't want or are unable to use Apache's mod_rewrite, please change in /local/staticpage/staticpage.php 

if (strpos($_SERVER['REQUEST_URI'], '/static/') > 0 || strpos($_SERVER['REQUEST_URI'], '/static/') === false)
	die;

to

// if (strpos($_SERVER['REQUEST_URI'], '/static/') > 0 || strpos($_SERVER['REQUEST_URI'], '/static/') === false)
//	die;

Now, the static pages from the above example are available on
http://www.yourmoodle.com/local/staticpage/staticpage.php?page=imprint
http://www.yourmoodle.com/local/staticpage/staticpage.php?page=impressum
http://www.yourmoodle.com/local/staticpage/staticpage.php?page=faq

These URLs aren't as catchy as with mod_rewrite, but they work in exactly the same manner.

You can now create links to these URLs in a Moodle HTML Block, in your Moodle theme footer and so on.


5. Custom pagelayout (optional)
-------------------------------
local_staticpage uses the "standard" pagelayout of your theme by default for creating the Moodle pages.

If you want to style static pages in any special way, you could extend your theme with a "staticpage" pagelayout. 
local_staticpage uses this pagelayout as soon as it exists in your /theme/<yourtheme>/config.php:

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
local_staticpage does NOT check the static html documents for any malicious code, neither malicious html code which will be delivered directly to the user's browser, nor malicious PHP code which could break DOM parsing when processing the html document on the server.
Therefore, please make sure that only authorized and brief users have write access to the html documents folder ($CFG->staticvaliddocuments).


Further information
===================
Report a bug or suggest an improvement: https://github.com/abias/moodle-local_staticpage/issues
