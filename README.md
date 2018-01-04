# Welcome to the iBiology website code repository
This repository represents the theme and plugins that we created to implement custom iBiology content.

## The iBiology Content Plugin: [iBiology Content](wp-content/plugins/ibio-content)
This plugin implements the custom content used on the iBiology site: talks, speakers, playlists and sessions including:  

+ Templates for single and archive for all implemented post types.  These templates pull content into a page, but styling comes from the theme.
+ Rewrite rules for talks to provide friendly URL's
+ JavaScript for handling front-end interactions with content.

More detailed information about the components in this plugin can be found in its folder.

## The iBiology Theme:  [iBiology 2016](wp-content/themes/ibiology)
This Genesis child theme implements the styling of the iBiology site, and functionality including:

+ Breadcrumbs
+ Search results template
+ Faceted "explore" page and filters for facetwp.
+ Category Page customization to show talks rather than blog posts
+ Filters for the Display Posts Shortcode 
 

### Critical Dependencies
Without the following, the site won't work correctly at all.

+ [Advanced Custom Fields (Pro)](https://advancedcustomfields.com) used for setting up the edit screens for talks, videos, etc.  Note: the current set of fields is NOT hardcoded into the plugin or theme.  Instead, this is controlled from within the WordPress admin.
+ [StudioPress Genesis](https://my.studiopress.com/themes/genesis/) theme.  The iBiology theme is a child theme of genesis.  The ibio-content plugin also assumes a genesis child theme is used by the site.
+ [Posts2Posts](https://wordpress.org/plugins/posts-to-posts/) plugin, which is used to create the relationships between speakers <-> talks and playlists <-> talks/sessions

### Other Dependencies
+ [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/), which is used for setting meta descriptions, indirectly affects the excerpts found in search results and on Speaker Pages
+ [SearchWP](https://searchwp.com) for search
+ [FacetWP](https://facetwp.com) for filtering (see the explore page)
+ [S2Member](https://s2member.com/) for managing access to controlled content
+ [Display Posts Shortcode](https://wordpress.org/plugins/display-posts-shortcode/), for creating curated landing pages for various types of content 

## How to set up your own development environment

Clone this repository in what would be the main directory of a WordPress installation.  

Download an archive of the site and unpack WordPress and contents of wp-content on top of the clone repository.

Remove the mu-plugins directory from wp-content, as this contains stuff that's needed from our hosting provider and shouldn't affect a local install. 

Load the live site database, doing what you need to, in order to make sure your local URL works.  (for example, add the siteurl and wordpress url to your wp-config.php file, or change the URL's in the database)
Loading the live site database will provide you with the list of custom fields, as well as lots of content to use. *NOTE*: If you don't load the site database, then you can use [this file](wp-content/plugins/ibio-content/acf-fields.json) to import a more-or-less up to date list of custom fields. Make sure you are using Advanced Custom Fields PRO, or the field import won't work.

*NOTE 2:* If you add new plugins or themes in your local install, make sure you don't accidentally load them into the git repository, by adding them to the [.gitignore](.gitignore) file.  