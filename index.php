<?php
/**

 * Plugin Name: WP Search Include Meta Data
 * Plugin URI: https://eastsidecode.com
 * Description: This plugin included meta data in stardard WordPress search.
 * Version: 1.1
 * Author: EastSide Code
 * Author URI: http://eastsidecode.com
 * License: GPL12

 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class esCodeSearchIncludeMetaData {

	function __construct() {
		
		
		/**
		 * Do a left join on the posts and postmeta tables
		 */

		add_filter('posts_join', function($join) {

			global $wpdb;

			if ( is_search() ) {    
				$join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
			}
		
			return $join;

		});


		/**
		 * Modify the posts where clause on the search page to include post meta
		 */

		add_filter('posts_where', function($where) {

			global $pagenow, $wpdb;

			if ( is_search() ) {
				$where = preg_replace(
					"/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
					"(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
			}
		
			return $where;

		});


		/**
		 * Prevent Duplicates
		 * https://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
		 */

		add_filter('posts_distinct', function($where) {

		    global $wpdb;

			if ( is_search() ) {
				return "DISTINCT";
			}

			return $where;

		});

		


	} // end construct

} // end class


/**
 * Initialize with a variable declaration
 */
$esCodeSearchIncludeMetaDataInit = new esCodeSearchIncludeMetaData();