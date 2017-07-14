=== Genesis Featured Video ===
Contributors: scott.deluzio
Tags: Genesis,featured video,featured image
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KFRZN69AUU99U
Requires at least: 3.1.0
Tested up to: 4.7
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replace featured images in a Genesis theme with a featured video from YouTube, Vimeo and other sources.

== Description ==
Replace featured images on your blog or archive pages in your Genesis theme with a featured video from YouTube, Vimeo, other video hosting services, or even self-hosted videos. This plugin lets your visitors play the video without having to click into the post to view it.

Custom post types are supported, so you can use this plugin to show product demonstration videos for WooCommerce or Easy Digital Downloads products, or show a promotional video for an upcoming event.

**This plugin requires your site to be using a Genesis child theme in order to work. It is not compatible with other themes.**

*Plugin author is a third-party developer who is not affiliated with StudioPress, the owner of the Genesis Framework.*

== Installation ==
1. Make sure you have a Genesis Framework child theme installed and activated on your site. You will not be able to activate this plugin without an active Genesis theme.
2. Download archive and unzip in wp-content/plugins, or install via Plugins Add New.
3. Activate the plugin through the Plugins menu in WordPress.

== Frequently Asked Questions ==
= How do I add a featured video? =
On the post edit page, there are two areas for you to look for. The first is titled "Format". The second is titled "Genesis Featured Video". 

On each post that you want to display a featured video on the archive page select "Video" from the Format section, and paste the URL to a video in the Genesis Featured Video section. Update or publish your post, and you should see your video replace the featured image on your blog or archive page.

= Where do I get the URLs for my videos? =
For YouTube and Vimeo videos, copy the URL from your browser's address bar when you are viewing the video on their site. You may also enter a direct URL to any video hosted elsewhere. For example `https://www.youtube.com/watch?v=0hEJe3HwOUs` or `http://example.com/videos/my-video.mp4`

= I don't want to use this on all of my custom post types. How can I change this? =
There are two ways. The first, and probably the easiest way is to simply not enter a video URL or select the Video post format on any custom post types that you don't want it to show up for.

If you are trying to remove the functionality altogether from certain post types, there is a filter included with the plugin that lets you edit the post types this plugin will allow featured videos to be used on.

`function change_post_types( $post_types ) {
	// only use plugin on posts and books
	$my_cpts = array(
		'post',
		'book',
	);
 
	// set $post_types to your cpt array
	$post_types = $my_cpts;
 
	return $post_types;
}
add_filter( 'gfv_post_types', 'change_post_types' );`

= I want to show the featured video in my post as well as on the archive page =
You can embed the video the same way that you normally embed videos in a post, or you can use a small line of code to force your video to show up on the post page.

`add_action( 'pre_get_posts', 'add_video_to_posts', 1 );
function add_video_to_posts() {
	remove_action( 'pre_get_posts', 'gfv_hide_video_on_post', 10 ) ;
}`

= How do I set the video width, height, and alignment? =
Go to the settings page on the Genesis > Featured Video menu in your site's admin area.

Width and height need to be entered in pixels.

Alignment is currently set to left, right, or none.

= Can I add any customizations to the video? =
The video will be wrapped in a `<div>` with one of the following classes depending on the position you select in the settings.

* gfvleft
* gfvright
* gfv

You can use that class to add styles to your videos.

= Why do my videos look distorted? =
Videos are commonly displayed with an aspect ratio of 16:9. Not all videos use this aspect ratio, but it is a good place to start.

If you are using a square width and height (i.e. 150x150), your video will likely look distorted.

 If you are unsure what an aspect ratio is, or how to calculate the width and height your video needs, visit [http://andrew.hedges.name/experiments/aspect_ratio/](http://andrew.hedges.name/experiments/aspect_ratio/). Enter 1920 in the W1 box, 1080 in the H1 box, and the Width OR Height you want your video to be in the W2 or H2 box respectively. The site will output the other dimension that you will need.

== Changelog ==
= 1.0.2 =
* Minor fix to ensure PHP 7 compatibility
= 1.0.1 =
* Initial release
= 1.0.0 =
* Initial release