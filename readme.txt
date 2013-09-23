=== WordPressIgniter ===

Contributors: dynamodan
Donate link: http://www.dynamodan.com/donate/
Tags: web application framework, model-view-controller, MVC framework, MVC, framework, CodeIgniter, theme development, plugin development
Requires at least: 3.6
Tested up to: 3.6.1
Stable tag: 1.0
License GPLv2

== Description ==

A WordPress plugin that integrates CodeIgniter

Why a CodeIgniter/Wordpress integration plugin?
- I (along with many others) like CodeIgniter for it's features such as ActiveRecord and MVC disciplines.
- I'm getting tired of building custom user, login, and session management systems for CodeIgniter. WordPress has them already.
- I'm getting tired of building custom look and feel and templates for CodeIgniter, WordPress already is that, and has thousands of themes available.
- I'm getting tired of building custom feature X for CodeIgniter, when it may already exist in WordPress either natively or as a plugin.

Why did I create this plugin, even though some others already exist? Because I wanted to:
- easily integrate CodeIgniter without invading its core very much, or optionally, not at all.
- show CodeIgniter output in a page, preserving all WordPress menu structure, template structure etc.
- not require template editing, so that templates can be easily swapped out and the CodeIgniter itegration still work.
- allow customizing of the CodeIgniter APPPATH and BASEPATH variables, so that the system and application folders can be put somewhere outside the plugins folder (or docroot altogether for that matter)


== Installation ==

We'll assume that you already have a working WordPress blog.

1. If you don't already have CodeIgniter, learn what it is here: http://ellislab.com/codeigniter
and then install it on your web site.

2. Create a page, and give it a meaningful title. Note that it must be a "page", not a post.

3. Install this plugin to your WordPress instance on your web site, and activate it.
	(for the most up-to-date bleeding edge version, get it from github: https://github.com/dynamodan/wordpressigniter

4. Go to the WordPressIgniter settings, and set the Page Override to the title of the page you created in step #1.  It must match verbatim.

5. Set the CodeIgniter Path setting to point to the folder containing CodeIgniter's index.php front controller file.

6. Optionally, set your own custom APPPATH and BASEPATH constants to point to custom paths for the system and application folders, if you choose to put them in another place besides the CodeIgniter defaults.

7. Visit the page you set in step #1.  You should see the standard CodeIgniter welcome content, or your CodeIgniter project's output if you already have one that you are using with this plugin.  There may be some troubleshooting messages instead.

== Tips ==

- If your CodeIgniter controller sets the $this->content['page_title'], such as `$this->content['page_title'] = 'Blast off!'; `
then this plugin will set the template-rendered title to "Blast off!" via a WordPress API registered 'the_title' filter hook.
- Just so you know, I'm setting the $current_user to a global, so that CodeIgniter can check if it's being run as a logged in user etc.  If this bothers you or causes your site any security issues, please don't complain to me, just don't use this plugin.

== TODO ==

(I don't know if these are even possible, or I might have already done them)

- provide a way to make CodeIgniter automatically use WordPress' database settings, from within the plugin (yes I know I could intrude CodeIgniter core to do this, but it's what I wanted to avoid)
- provide a mechanism to instantiate CodeIgniter *only* on the overridden page, rather than all frontend urls.
