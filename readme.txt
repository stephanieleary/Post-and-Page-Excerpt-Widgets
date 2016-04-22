=== Post and Page Excerpt Widgets ===
Contributors: sillybean
Tags: widgets, excerpts
Donate link: http://stephanieleary.com/code/wordpress/post-and-page-excerpt-widgets/
Requires at least: 2.8
Tested up to: 4.5
Stable tag: 2.2
Text Domain: post-excerpt-widget


Creates widgets that display excerpts from posts or pages in the sidebar.  

== Description ==

Creates widgets that display excerpts from posts or pages in the sidebar. You may use 'more' links and/or link the widget title to the post or page.  Requires <a href="http://blog.ftwr.co.uk/wordpress/page-excerpt/">Page Excerpt</a> or <a href="http://www.laptoptips.ca/projects/wordpress-excerpt-editor/">Excerpt Editor</a> for page excerpts. Supports <a href="http://robsnotebook.com/the-excerpt-reloaded/">the_excerpt Reloaded</a> and <a href="http://sparepencil.com/code/advanced-excerpt/">Advanced Excerpt</a>.

Now on <a href="https://github.com/sillybean/Post-and-Page-Excerpt-Widgets">GitHub</a>.

== Installation ==

1. Upload the plugin directory to `/wp-content/plugins/` 
1. Activate the plugin through the 'Plugins' menu in WordPress

Go to Design &rarr; Widgets to add widgets to your sidebar in widget-ready themes.

== Screenshots ==

1. The widget manager, with the extra options available when used in conjunction with the_excerpt Reloaded.

== Changelog ==

= 2.2 =
* Total refactoring and update.
* Internationalization.
* <a href="https://github.com/sillybean/Post-and-Page-Excerpt-Widgets">GitHub</a>.
= 2.1 =
* Bug fixed: 'more' text not showing up in some cases
* Bug fixed: excerpt & 'more' link showing the wrong page when using more than one page widget
* Thanks to Lynne for pointing out bugs!
= 2.0 =
* Rewritten to use new widget API in WP 2.8
* Improved loop handling
* New per-widget controls for the_excerpt Reloaded options
* Built-in upgrading of widgets from the 1.x plugin