wordpressigniter
================

A WordPress plugin that integrates CodeIgniter

Instructions:

We'll assume that you already have a working WordPress blog.

1. If you don't already have CodeIgniter, learn what it is here: http://ellislab.com/codeigniter
and then install it on your web site.

2. Create a page, and give it a meaningful title. Note that it must be a "page", not a post.

3. Install this plugin to your WordPress instance on your web site, and activate it.

4. Go to the WordPressIgniter(tm) settings, and set the Page Override to the title of the page you created in step #1.  It must match verbatim.

5. Set the CodeIgniter Path setting to point to the folder containing CodeIgniter's index.php front controller file.

6. Optionally, set your own custom APPPATH and BASEPATH constants to point to custom paths for the system and application folders, if you choose to put them in another place besides the CodeIgniter defaults.

7. Visit the page you set in step #1.  You should see the standard CodeIgniter welcome content, or your CodeIgniter project's output if you already have one that you are using with this plugin.  There may be some troubleshooting messages instead.

TIPS:

- If your CodeIgniter controller sets the $this->content['page_title'], such as `$this->content['page_title'] = 'Blast off!'; ` then this plugin will set the template-rendered title to "Blast off!" via a WordPress API registered 'the_title' filter hook.
- Just so you know, I'm setting the $current_user to a global, so that CodeIgniter can check if it's being run as a logged in user etc.  If this bothers you or causes your site any security issues, please don't complain to me, just don't use this plugin.

TODO:

(I don't know if these are even possible, or I would have already done them)

- provide a way to make CodeIgniter automatically use WordPress' database settings, from within the plugin.
- provide a mechanism to instantiate CodeIgniter *only* on the overridden page, rather than all frontend urls.
