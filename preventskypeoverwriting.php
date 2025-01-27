<?php 
/*
Plugin Name: Prevent Skype Overwriting
Version: 0.1
Plugin URI: http://www.wpbeginner.com/
Description: This plugin adds a simple meta code which prevents Skype from overwriting all phone numbers with their widget.
Author: WPBeginner
Author URI: http://www.wpbeginner.com/
*/
/* Version check */
function skypeoverwrite_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}

function overwrite_skype_meta() {
    ?>
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <?php
}
add_action( 'wp_head', 'overwrite_skype_meta' );

add_action('wp_dashboard_setup', 'skypeoverwrite_dashboard_widgets');

function skypeoverwrite_dashboard_widgets() {
   global $wp_meta_boxes;

   wp_add_dashboard_widget('wpbeginnerskypeoverwritewidget', 'Latest from WPBeginner', 'skypeoverwrite_widget');
}
		function skypeoverwrite_limit( $text, $limit, $finish = ' [&hellip;]') {
			if( strlen( $text ) > $limit ) {
		    	$text = substr( $text, 0, $limit );
				$text = substr( $text, 0, - ( strlen( strrchr( $text,' ') ) ) );
				$text .= $finish;
			}
			return $text;
		}

		function skypeoverwrite_widget() {
			$options = get_option('wpbeginnerskypeoverwritewidget');
			require_once(ABSPATH.WPINC.'/rss.php');
			if ( $rss = fetch_rss( 'http://wpbeginner.com/feed/' ) ) { ?>
				<div class="rss-widget">
                
				<a href="http://www.wpbeginner.com/" title="WPBeginner - Beginner's guide to WordPress"><img src="http://cdn.wpbeginner.com/pluginimages/wpbeginner.gif"  class="alignright" alt="WPBeginner"/></a>			
				<ul>
                <?php 
				$rss->items = array_slice( $rss->items, 0, 5 );
				foreach ( (array) $rss->items as $item ) {
					echo '<li>';
					echo '<a class="rsswidget" href="'.clean_url( $item['link'], $protocolls=null, 'display' ).'">'. ($item['title']) .'</a> ';
					echo '<span class="rss-date">'. date('F j, Y', strtotime($item['pubdate'])) .'</span>';
					
					echo '</li>';
				}
				?> 
				</ul>
				<div style="border-top: 1px solid #ddd; padding-top: 10px; text-align:center;">
				<a href="http://feeds2.feedburner.com/wpbeginner"><img src="http://cdn.wpbeginner.com/pluginimages/feed.png" alt="Subscribe to our Blog" style="margin: 0 5px 0 0; vertical-align: top; line-height: 18px;"/> Subscribe with RSS</a>
				&nbsp; &nbsp; &nbsp;
				<a href="http://www.wpbeginner.com/wordpress-newsletter/"><img src="http://cdn.wpbeginner.com/pluginimages/email.gif" alt="Subscribe via Email"/> Subscribe by email</a>
                &nbsp; &nbsp; &nbsp;
                <a href="http://facebook.com/wpbeginner/"><img src="http://cdn.wpbeginner.com/pluginimages/facebook.png" alt="Join us on Facebook" style="margin: 0 5px 0 0; vertical-align: middle; line-height: 18px;" />Join us on Facebook</a>
				</div>
				</div>
			<?php }
		}


?>