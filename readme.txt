=== WordPressIgniter ===

Contributors: dynamodan
Donate link: http://www.dynamodan.com/donate/
Tags: web application framework, model-view-controller, MVC framework, MVC, framework, CodeIgniter, theme development, plugin development
Requires at least: 3.3
Tested up to: 3.9.1
Stable tag: 1.4
License GPLv2

== Description ==

A WordPress plugin that integrates CodeIgniter

Why a CodeIgniter/Wordpress integration plugin?

1. I (along with many others) like CodeIgniter for it's features such as ActiveRecord and MVC disciplines, plus I have a ton of cool libs and stuff in CodeIgniter that I would like to use in the WordPress environment

2. I'm getting tired of building custom user, login, and session management systems for CodeIgniter. WordPress has them already.

3. I'm getting tired of building custom look and feel and templates for CodeIgniter, WordPress already is that, and has thousands of themes available.

4. I'm getting tired of building custom feature X for CodeIgniter, when it may already exist in WordPress either natively or as a plugin.

Why did I create this plugin, even though some others already exist? Because I wanted to:

1. easily integrate CodeIgniter without invading its core very much, or optionally, not at all.

2. show CodeIgniter output in a page, preserving all WordPress menu structure, template structure etc.

3. not require template editing, so that templates can be easily swapped out and the CodeIgniter itegration still work.

4. allow customizing of the CodeIgniter APPPATH and BASEPATH variables, so that the system and application folders can be put somewhere outside the plugins folder (or docroot altogether for that matter)

5. handle segmented urls in CodeIgniter fashion, including paths that aren't valid in WordPress (i.e. via WordPress 404 hooks), and dispatch to appropriate CodeIgniter controller functions.

== Installation ==

We'll assume that you already have a working WordPress blog.

1. If you don't already have CodeIgniter, learn what it is here: http://ellislab.com/codeigniter
and then install it on your web site.

2. Create a page, and give it a meaningful title. Note that it must be a "page", not a post.  Insert the [wordpressigniter] shortcode anywhere in the body. (This will cause CodeIgniter to replace the entire contents of the page.)

3. Install this plugin to your WordPress instance on your web site, and activate it.
	(for the most up-to-date bleeding edge version, get it from github: https://github.com/dynamodan/wordpressigniter


4. Go to the WordPressIgniter settings, and set the CodeIgniter Path setting to point to the folder containing CodeIgniter's index.php front controller file.

5. Optionally, set your own custom APPPATH and BASEPATH constants to point to custom paths for the system and application folders, if you choose to put them in another place besides the CodeIgniter defaults.

6. Visit the page you set in step #1.  You should see the standard CodeIgniter welcome content, or your CodeIgniter project's output if you already have one that you are using with this plugin.  There may be some troubleshooting messages instead.

7. Go to the WordPressIgniter settings, and optionally check the "Trigger with [wordpressigniter] shortcode in posts, too" checkbox.  This enables you to use a blog post containing the [wordpressigniter] shortcode to integrate CodeIgniter as well.  Note that the [wordpressigniter] shortcode will show in the list views, so I recommend putting it after a "Read More" tag.

== Tips ==

- If your CodeIgniter controller sets the $this->content['page_title'], such as `$this->content['page_title'] = 'Blast off!'; `
then this plugin will set the template-rendered title to "Blast off!" via a WordPress API registered 'the_title' filter hook.
- If you choose to tick the "CodeIgniter grabs all SEO urls" checkbox, beware that CodeIgniter will return its own 404 page (along
with http header!) on any non-root urls, i.e. permalinks.  This behaviour can be set within CodeIgniter by adjusting the routes.php
file to point to a valid controller, like this: `$route['404_override'] = 'welcome';`

== TODO ==

(I don't know if these are even possible, or I might have already done them)

- provide a way to make CodeIgniter automatically use WordPress' database settings, from within the plugin (yes I know I could intrude CodeIgniter core to do this, but it's what I wanted to avoid)
- provide a mechanism to instantiate CodeIgniter *only* on the overridden page, rather than all frontend urls.

== Changelog ==

= 1.4 =
* fixed a bug where WordPress was throwing 404's for nearly all CodeIgniter urls

= 1.3 =
* implemented [wordpressigniter] shortcode, which will deprecate the Page Override setting, as it was quite buggy and had some limitations.

= 1.2 =
* fixed revision number in this readme file

= 1.1 =
* fixed parameter bug caused by CodeIgniter erasing the $_GET variable
* fixed some bugs caused by setting $current_user, now we set this in our own $CI_USER variable
* added a feature to allow comma-separated page titles that match for inserting CodeIgniter ouput

= 1.0 =
* initial release
