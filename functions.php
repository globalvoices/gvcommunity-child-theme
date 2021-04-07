<?php
/**
 * Functions.php file for GV Community Child Theme
 *
 * Assumes parent is gv-project-theme. 
 * This code will run before the functions.php in that theme.
 */

/**
 * Filter/action pre-sidebar section of sidebar.php to insert things specific to Community Theme
 * 
 * @uses apply_filters('gv_pre_sidebar', $pre_sidebar) from sidebar.php
 * @param string $output
 * @return string filtered $output
 */
function gv_community_filter_pre_sidebar($output) {
	
	if (!is_page()) {
		return $output;
	}
	
	$output .= gv_display_sidebar('guide_sidebar_top', array(
		'echo' => false,
	));
	
	return $output;
}
add_filter('gv_pre_sidebar', 'gv_community_filter_pre_sidebar');

/**
 * Register menus for GV News Theme.
 * 
 * Adds guide_sidebar_top sidebar to contain a mini-menu of top guide pages
 * 
 * Runs during 'init' action
 */
function gv_community_register_sidebars() {
	/**
	 * Register 
	 * Only thing higher is the full page menu on page.php
	 * 
	 * Uses standard sidebar before/after stuff from elsewhere in theme to match
	 */
	register_sidebar(array(
		'name'=>'Guide sidebar top',
		'id' => 'guide_sidebar_top',
		'description' => 'This sidebar will show at the top of the sidebar for GUIDE pages, above everything else.',
		'before_widget' => '<div class="widget-container"><div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div><!--.widget--></div><!--.widget-container-->",
		'before_title' => "<h2 class='widgettitle'>",
		'after_title' => "</h2>\n"
	));
}
add_action('init', 'gv_community_register_sidebars');

/**
 * Filter post class to add .rtl-direction if the 'gv-rtl' postmeta is true
 * 
 * Expects a metabox in the post editor to check for "RTL". 
 * 
 * @param string $classes Other classes that will be shown
 * @param string $class Classes specified in the post_class() call (NOT IMPORTANT)
 * @param integer $post_id 
 * @return string List of classes with ours added if necessary
 */
function gv_filter_post_classes_rtl ($classes, $class, $post_id) {

	$is_rtl = get_post_meta($post_id, 'gv-rtl', true);
	if ($is_rtl)
		$classes[] = 'rtl-direction';
	return $classes;
}
add_filter('post_class', 'gv_filter_post_classes_rtl', 10, 3);

/**
 * Register custom postmeta fields with the Custom Metadata Manager plugin
 *
 * Convert to some other format if this ever stops working
 */
function gv_community_custom_metadata_manager_admin_init() {
	/**
	 * Exit if the plugin isn't present
	 */
	if(!function_exists( 'x_add_metadata_field' ) OR !function_exists( 'x_add_metadata_group' ) )
		return;
	/**
	 * Register a group for pages and posts
	 */
	x_add_metadata_group('gv_custom_metadata_posts', array('post', 'page'), array(
		'label' => 'Post Settings (Global Voices)',
		'priority' => 'high',
	));
	/**
	 * gv-rtl to indicate a right-to-left (e.g. Arabic) post
	 */
	x_add_metadata_field( 'gv-rtl', array('post', 'page'), array(
		'group' => 'gv_custom_metadata_posts',
		'label' => 'RTL: Check this box to display this post as Right-To-Left (for Arabic, Hebrew, Urdu etc.)',
		'field_type' => 'checkbox',
	));
}
add_action( 'admin_init', 'gv_community_custom_metadata_manager_admin_init' );

if (is_object($gv)) :

	/**
	 * Define an image to show in the header.
	 * Project theme generic has none, so it will use site title
	 */
	$gv->settings['header_img'] = 'https://community.globalvoicesonline.org/wp-content/uploads/2014/10/gv-community-header-900.png';

	/**
	 * Enable Featured posts - Tells GV Query Manipulation to prefetch featured posts before main loop and exclude their ids.
	 * @see gv_load_featured_posts()
	 */
//	$gv->use_featured_posts = true;
	
	/**
	 * Hide tags interface completely to avoid people using them
	 * @see gv_hide_tags_ui()
	 */
//	add_filter('gv_hide_tags_ui', '__return_true');
	
	/**
	 * Set site colors for use in PHP-driven CSS (AMP templates)
	 * 
	 * Currently specifically intended for AMP plugin 
	 * 
	 * @see gv_get_site_colors()
	 * @return type
	 */
	function gvcommunity_gv_site_colors() {
		return array(
			'solid_bg' => '45AF49',
			'solid_bg_text' => 'ffffff',
			'link_dark' => '1287c8',
			'link_light' => '5bb5e8',
		);
	}
	add_filter('gv_site_colors', 'gvcommunity_gv_site_colors');

	/**
	 * Filter Google Structured Data "logo" for AMP
	 * 
	 * @see SD reference https://developers.google.com/search/docs/data-types/article
	 * @see gv_get_sd_logo() Which uses this
	 * @param string $icon Default icon
	 * @return string desired icon
	 */
	function gvcommunity_theme_gv_sd_logo($icon) {
		return array(
			'url' => 'https://globalvoices.org/wp-content/gv-static/img/tmpl/community-structureddata-60x358.png',
			'height' => 60,
			'width' => 318,
		);
	}
	add_filter('gv_sd_logo', 'gvcommunity_theme_gv_sd_logo');

	/**
	 * Filter the favicon directory used by gv_display_head_icons()
	 * 
	 * @param string $dir Default directory (no trailing /) to find favicons in
	 * @return string desired directory (no trailing /)
	 */
//	function risingvoices_theme_gv_favicon_dir($dir) {
//		return 'https://s3.amazonaws.com/static.globalvoices/img/tmpl/favicon-rv';
//	}
//	add_filter('gv_favicon_dir', 'risingvoices_theme_gv_favicon_dir');
	
	/**
	 * Filter the apple touch icon 
	 * 
	 * @param string $icon Default icon
	 * @return string desired icon
	 */
//	function rising_theme_gv_apple_touch_icon($icon) {
//		return gv_get_dir('theme_images') ."risingvoices-apple-touch-icon-precomposed-300.png";
//	}
//	add_filter('gv_apple_touch_icon', 'rising_theme_gv_apple_touch_icon');
		
	/**
	 * Filter the og:image (facebook/g+) default icon 
	 * 
	 * @param string $icon Default icon
	 * @return string desired icon
	 */
//	function gvadvocacy_theme_gv_og_image_default($icon) {
//		return gv_get_dir('theme_images') ."rv-logo-square-600.png";
//	}
//	add_filter('gv_og_image_default', 'gvadvocacy_theme_gv_og_image_default');
	
	/**
	 * Filter ALL CASES OF og:image (facebook/g+) icon to be an RV logo
	 * 
	 * @param string $icon Default icon
	 * @return string desired icon
	 */
//	function gvadvocacy_theme_gv_og_image($icon) {
//		return gv_get_dir('theme_images') ."rv-logo-square-600.png";
//	}
//	add_filter('gv_og_image', 'gvadvocacy_theme_gv_og_image');
	
	/**
	 * Define Categories to be inserted into post data before returning content for translation during fetch
	 * @see gv_lingua::reply_to_ping()
	 */
	$gv->lingua_site_categories[] = 'gvcommunity';

	/**
	 * Define special categories as content types and the conditions in which to segregate them
	 * Used by gv_get_segregated_categories() and gv_
	 * segregation_conditions apply to primary content only. sidebar headlines etc assume segregation
	 * segregate_headlines - use if headlines will be a waste for this , blocks them from showing as title only
	 */
//	$gv->category_content_types = array(
//		'feature' => array('title' => 'feature'),
//	);
	
	/**
	 * Set a custom site description using a lingua string. To be used in social media sharing etc.
	 */
//	$gv->site_description = "Rising Voices aims to extend the benefits and reach of citizen media by connecting online media activists around the world and supporting their best ideas.";

	/**
	 * Sponsors definition to be used by gv_get_sponsors()
	 */
	$gv->sponsors = array(
		'berkman' => array(
			"name" => "Berkman",
			"slug" => "berkman",
			"description" => 'Berkman Center for Internet and Society at Harvard University.',
			"url" => "http://cyber.law.harvard.edu/",
			"status" => 'featured',
			),
		'mdif' => array(
			"name" => "Media Developmet Investment Fund",
			"slug" => "mdif",
			"description" => 'Media Development Investment Fund',
			"url" => "http://www.mdif.org/",
			"status" => 'featured',
			),
		'macarthur' => array(
			"name" => "MacArthur Foundation",
			"slug" => "macarthur",
			"description" => 'MacArthur Foundation',
			"url" => "http://www.macfound.org/",
			"status" => 'featured',
			),
		'hivos' => array(
			"name" => "Hivos",
			"slug" => "hivos",
			"description" => 'Hivos, the Humanist Institute for Development Cooperation',
			"url" => "http://www.hivos.org/",
			"status" => 'featured',
			),
		'omidyar' => array(
			"name" => "Omidyar Network",
			"slug" => "omidyar",
			'description' => 'Omidyar Network - Every person has the power to make a difference.',
			"url" => "http://www.omidyar.com/",
			'status' => 'featured',
			),
		'ford' => array(
			"name" => "Ford Foundation",
			"slug" => "ford",
			"description" => 'Ford Foundation - Working with Visionaries on the Frontlines of Social Change Worldwide',
			"url" => "http://fordfound.org/",
			"status" => 'featured',
			),
		'osi' => array(
			"name" => "Open Society Institute",
			"slug" => "osi",
			"description" => 'Open Society Institute - Building vibrant and tolerant democracies.',
			"url" => "http://www.soros.org/",
			"status" => 'featured',
			),
		'reuters' => array(
			"name" => "Reuters",
			"slug" => "reuters",
			"description" => 'Reuters - Business, financial, breaking and international news.',
			"url" => "http://www.reuters.com/",
			"status" => 'sponsor',
			),
		'knight' => array(
			"name" => "Knight Foundation",
			"slug" => "knight",
			"description" => 'John S. and James L. Knight Foundation',
			"url" => "http://www.knightfdn.org/",
			"status" => 'sponsor',
			),
		'unfpa' => array(
			"name" => "UNFPA",
			"slug" => "unfpa",
			"description" => '',
			"url" => "http://www.unfpa.org/",
			"status" => 'sponsor',
			),
	);

	/**
	 * Register "User Categories" as a user taxonomy
	 *
	 * Simple, hand-created list of user-categories specifically so that we can have
	 * a `council-2018` user category and display the list of users. 
	 * 
	 * English only for now. If we use something similar on Lingua it will need to be
	 * re-evaluated. 
	 *
	 */
	function gvcommunity_register_user_groups_taxonomy() {
		/**
		 * Set up the register_taxonomy args 
		 * (based on gv_achievements->register_taxonomy()
		 */
		$args = array(
//			'single_term_taxonomy' => true, // Users can have multiple terms
//			'checked_ontop' => true, // Passed to wp_category_checklist() Whether to filter selected terms to the top of list (breaks hierarchy)
			'public' => true,
			'hierarchical' => true,
			'labels' => array(
				'name' => __( 'User Categories'),
				'singular_name' => __( 'User Categories' ),
				'menu_name' => __( 'User Categories' ),
				'search_items' => __( 'Search User Categories' ),
				'popular_items' => __( 'Popular User Categories' ),
				'all_items' => __( 'All User Categories' ),
				'edit_item' => __( 'Edit User Category' ),
				'update_item' => __( 'Update User Category' ),
				'add_new_item' => __( 'Add New User Category' ),
				'new_item_name' => __( 'New User Category Name' ),
				'separate_items_with_commas' => __( 'Separate Categories with commas' ),
				'add_or_remove_items' => __( 'Add or remove User Categories' ),
				'choose_from_most_used' => __( 'Choose from the most popular User Categories' ),
			),
			'show_ui' => true,
			'show_in_menu' => true,
			'rewrite' => array(
//				'with_front' => true,
//				'slug' => 'author/' . $this->user_taxonomy // Use 'author' (default WP user slug).
			),
			'capabilities' => array(
				'manage_terms' => 'edit_users', // Using 'edit_users' cap to keep this simple.
				'edit_terms'   => 'edit_users',
				'delete_terms' => 'edit_users',
				'assign_terms' => 'edit_users',
			),
		);

		gv_register_user_taxonomy('user-groups', $args);
	}
	add_action('after_setup_theme', 'gvcommunity_register_user_groups_taxonomy', 11);

	/**
	 * COMMUNITY COUNCIL: Button to show a template acceptance letter for the council
	 * 
	 * Adds a "Show/Hide Community Acceptance Letter" button in the user editor just below the
	 * User Categories box that displays a textarea with sample acceptance text that can be
	 * used for each person. 
	 * 
	 * Useful because it integrates various URLs auto-generated from the user account being edited
	 * 
	 * @param WP_User $user_object
	 * @return type
	 */
	function gvcomunity_personal_options_council_acceptance_letter_template($user_object) {
		// Only show to admins
		if (!current_user_can('edit_users'))
			return;

		// Make sure we have a user to show
		if (!is_object($user_object) OR !isset($user_object->ID))
			return;

		// Set up values to include
		$user_email = $user_object->user_email;
		$user_login = $user_object->user_login;
		$user_display_name = $user_object->display_name;
		$user_profile_url = get_author_posts_url($user_object->ID);
		$user_profile_edit_url = get_edit_user_link($user_object->ID);
		
		/**
		 * Output
		 */
		$output = "<h3>GV Community Council Acceptance Template</h3>";
		$output .= "<p class='description'>Use this as a template when notifying this user that they have been added to the GV Community Council (i.e. that they are in the appropriate \"Community Council]\" User Category above)</p>";
		$output .= "<p class='council-acceptance-letter-button button'>Show/Hide Council Acceptance Letter</p>";
		$output .= "<div class='council-acceptance-letter hidden'>";
		$output .= "
		<textarea style='width:100%; height:15rem; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;'>

$user_email
	
$user_display_name, thank you for applying to join the GV Community Council for this year! We’re pleased to officially welcome you to the Council and that your voice will be included in this vital process. Here’s some initial information to get you started and ready to participate.
 
## The Community Council Google Group 
 
Soon you will receive an email informing you that you are subscribed to the 'Global Voices Community Council' Google Group, which can also be found here:

https://groups.google.com/forum/#!forum/gv-community-council.

We’ll use this group to send announcements, updates and other important information about the Council process, as well as for ongoing discussions among Council members about the ideas brought to the Council.  
 
## Community Site Profile
 
As part of the Council process, we've ensured that you have a user profile on the GV Community Blog. All council members will be listed on the Community Blog, unless they opted out for security reasons. If you didn't already have an account, we've created one for you by importing your user profile from another GV site:
 
$user_profile_url
 
Please take a few minutes to review that profile and make sure all the user settings (e.g. email, social media profiles) are correct. If your bio or other information isn't available in English on that profile, please consider translating the text (though feel free to keep both copies if you want!)

You can edit the account at this URL: 

$user_profile_edit_url
	
Username: $user_login
Email: $user_email
 
Password: If this is the first time you’re using this account, it won't have a password, so please use the 'Lost your password?' tool on the login form to create a new password for this profile

Note: This is a unique account, separate from the one(s) you have on the Global Voices/Lingua/Advox/Rising Voices/NewsFrames WordPress sites, though it probably has the same username and email.
 
## Thank you!
 
We appreciate the effort you've put into reading the Community Council documentation and filling out the application, and we look forward to your continued engagement and interaction with this process as it goes forward. Thanks again for being part of the Community Council.
 
- Global Voices Core Team (Ivan, Georgia, Filip, Maryalice, Eddie, Gohary, and Jer)

		</textarea>";			
		$output .= "</div>";
		$output .= "
		<script type='text/javascript'>
			jQuery(document).ready(function($) {
				$('.council-acceptance-letter-button').click(function() {
					$( '.council-acceptance-letter' ).toggleClass( 'hidden' );
				});
			});			
		</script>
		";
		
		echo $output;
	}
	add_action('show_user_profile', 'gvcomunity_personal_options_council_acceptance_letter_template', 10, 1 );
	add_action('edit_user_profile', 'gvcomunity_personal_options_council_acceptance_letter_template', 10, 1 );
endif; // is_object($gv)
?>