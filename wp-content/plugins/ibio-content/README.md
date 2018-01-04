# iBiology Content Types Plugin

This plugin implements the editing and display of educational iBiology content.  It assumes that it's installed along with some critical dependencies (see below).


## Plugin Map
This list shows the most important files and directories in the plugin.

* [ibio-content.php](ibio-content.php) - Main plugin file, handles initialization, Posts2Posts relationships creation, and rewrite rules.
* [assets](assets) - for css, js, and image files.  The ibio.js file implements that interactive elements of the talks page.
* [lib](lib) - for classes, functions and post types
  * [classes](lib/classes)
    * [IBio_Template_Loader](lib/classes/template_loader.php) - Implements the custom template functionality, as well as the ibio_get_template_part function.
  * [functions](lib/functions) - groups of functions, hooks and filter setups that are used across various classes.  These are grouped based on topic area
    * [content](lib/functions/content.php) - things to set up various page, retrieve related content elements such as other talks and speakers, etc.  Used by templates.
    * [playlist](lib/functions/playlist.php) - Playlist display shortcode, playlist sorting functions, and other items related to playlists. Of note is the function ibio_talks_playlist, which implements the retrieval of playlist items._
* [templates](templates) - for front-end display code that can be over-ridden by a theme.  Inside "shared" and "single-talk" are several parts that are used.  Most of them are self-explanatory, but the following may be helpful:
  * [videos-container](templates/single-talk/videos-container.php) - the main black box that shows up on a talk page, and includes multiple videos, a sidebar for navigating between them, and related content.  It uses the single-video.php template to show individual parts videos. 
  * [single-video](templates/shared/single-video.php) - Used by Talks and Sessions, this displays an embedded video and the toolbar that appears below.  This includes the download links, the transcript, and more. 


## Post Types

### [Talks](lib/post-types/talks.php)

Lectures consisting of one or more videos.  This is the meat of the site, and most complex type of content. Some relevant features:

+ Talks are usually displayed with their featured image and short title. The Short Title is a custom field.
+ When a talk is saved, a custom excerpt is generated that includes links to each part as well as the short description saved in Yoast.  Custom fields used by FaceWP are also updated, such as duration.
+ Talks have custom URL's (based on their category).  This URL is generated in the rewrite portion of the plugin.
+ Breadcrumbs that appear for a talk are customized in the iBiology theme, and are also dependent on their category.


### [Sessions](lib/post-types/course-session.php)

Flipped course sessions.  Consists of more than one video, and include Educator resources, Q/A, etc.  From a data perspective, a session is identical to a talk, but it's displayed differently.

### [Speakers](lib/post-types/speakers.php)

Biographical information about each speaker, including awards.  A list of all talks by that speaker is also displayed.  Most iBbiology speakers have only one talk, but there are some who are quite prolific.  The speaker page uses the custom excerpt of talks, rather than the smaller "card"-based display.

### [Playlists](lib/post-types/playlists.php)

Lectures organized in a series.  They can be ordered sequentially or not.  When not ordered sequentially, talks on a playlist are displayed in random order.

**[playlist] shortcode**
You can use the playlist shortcode to display talks from a playlist.  For example [playlist id='123' numtalks='10' start_index='3'] will show 10 talks from the playlist with the post ID 123, starting at the 3rd item.

Attributes are:
* id: the specific ID of the playlist to show.  Required.  An error will display if this is not correctly filled in or is for an empty playlist.
* numtalks: the number of talks to show.  Default is 4.
* start_index: the first item to show in an ordered list.
* audience: limit the items returned to those relevant for a specific audience.

This is implemented in [lib/functions/playlist.php](lib/functions/playlist.php)

### Custom Field Groups

* Information, Videos - this is used on **talks** and **sessions**, and contains the list of videos, as well as more general information such as the short title, year recorded, and language of the item.
* Related Resources. used on **talks**, **playlists**, **speakers** and **sessions** This includes the primary related category and playlist, as well as free-form text fields used for adding citations, bibliography, etc.  The display of this is driven primarily from template parts found in the "shared" directory. 
* Assessments and Educator Resources - this is used on **talks** and **sessions**, and includes information for educators, and Q&A for students.

* Speaker Information
* Playlist Information
* Migration Information - used for when we moved the site over, may not be relevant.

## Critical Dependencies
Without the following, the plugin won't work correctly at all.

+ [Advanced Custom Fields (Pro)](https://advancedcustomfields.com) used for setting up the edit screens for talks, videos, etc.  Note: the current set of fields is NOT hardcoded into the plugin or theme.  Instead, this is controlled from within the WordPress admin.
+ [StudioPress Genesis](https://my.studiopress.com/themes/genesis/) theme.  The iBiology theme is a child theme of genesis.  The ibio-content plugin also assumes a genesis child theme is used by the site.
+ [Posts2Posts](https://wordpress.org/plugins/posts-to-posts/) plugin, which is used to create the relationships between speakers <-> talks and playlists <-> talks/sessions



