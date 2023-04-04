<?php
/**
 * PEP.
 *
 * Onboarding config to load plugins and homepage content on theme activation.
 *
 * @package PEP
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

return array(
	'dependencies' => array(
		'plugins' => array(
            array(
				'name'        =>   __('NECESSARY','pep').' '.__('Customize editor control (when WC is available)','pep'),
				'slug'        => 'customize-editor-control/index.php',
			),
			array(
				'name'        => __('ADMIN','pep').' '.__( 'Atomic Blocks', 'pep' ),
				'slug'        => 'atomic-blocks/atomicblocks.php',
			),
			array(
				'name'        => __('ADMIN','pep').' '.__( 'Velvet Blues update URLS', 'pep' ),
				'slug'        => 'velvet-blues-update-urls/velvet-blues-update-urls.php',
			),
			array(
				'name'        =>   __('ADMIN','pep').' '.__('Disable Gutenberg blocks','pep'),
				'slug'        => 'disable-gutenberg-blocks/class-disable-gutenberg-blocks.php',
			),
			array(
				'name'        =>   __('ADMIN','pep').' '.__('Duplicate Post','pep'),
				'slug'        => 'duplicate-post/duplicate-post.php',
			),
			array(
				'name'        => __('ADMIN','pep').' '.__( 'Regenerate Thumbnails', 'pep' ),
				'slug'        => 'regenerate-thumbnails/regenerate-thumbnails.php',
			),
			array(
				'name'        => __('ACCESSIBILITY','pep').' '.__( 'WCAG 2.0 for Gravity Forms', 'pep' ),
				'slug'        => 'gravity-forms-wcag-20-form-fields/index.php',
			),
			array(
				'name'        => __('ACCESSIBILITY','pep').' '.__( 'Genesis Accessible', 'pep' ),
				'slug'        => 'genesis-accessible/genesis-accessible.php',
			),
			array(
				'name'        => __('PRIVACY','pep').' '.__( 'Cookie Notice for GDPR', 'pep' ),
				'slug'        => 'cookie-notice/index.php',
			),			
			array(
				'name'        => __('PRIVACY','pep').' '.__( 'Cookiebot.com (alternative)', 'pep' ),
				'slug'        => 'cookiebot/index.php',
			),
			array(
				'name'        => __('SECURITY','pep').' '.__( 'Wordfence Security', 'pep' ),
				'slug'        => 'wordfence/index.php',
			),
			array(
				'name'        => __('SECURITY','pep').' '.__( 'Sucuri', 'pep' ),
				'slug'        => 'sucuri-scanner/index.php',
			),
			array(
				'name'        => __('SECURITY','pep').' '.__( 'InfiniteWP', 'pep' ),
				'slug'        => 'iwp-client/index.html',
			),
			array(
				'name'        => __('SECURITY','pep').' '.__( 'Really Simple SSL', 'pep' ),
				'slug'        => 'really-simple-ssl/index.php',
			),
			array(
				'name'        => __('STATISTICS / SEO','pep').' '.__( 'Yoast SEO', 'pep' ),
				'slug'        => 'wordpress-seo/wp-seo.php',
			),
            array(
				'name'        => __('STATISTICS / SEO','pep').' '.__( 'Yoast Test Helper', 'pep' ),
				'slug'        => 'yoast-test-helper/yoast-test-helper.php',
			),
            array(
				'name'        => __('STATISTICS / SEO','pep').' '.__( 'Redirection', 'pep' ),
				'slug'        => 'redirection/index.php',
			),
            array(
				'name'        => __('FRONTEND','pep').' '.__( 'Easy Fancybox', 'pep' ),
				'slug'        => 'easy-fancybox/easy-fancybox.php',
			),
			array(
				'name'        => __('FRONTEND','pep').' '.__( 'Category Images', 'pep' ),
				'slug'        => 'categories-images/categories-images.php',
			),
			array(
				'name'        => __('FRONTEND','pep').' '.__( 'W3 Total Cache', 'pep' ),
				'slug'        => 'w3-total-cache/index.php',
			),
			
		),
	),
	'content' => array(
		'homepage' => array(
			'post_title'     => 'Homepage',
			'post_name'      => 'home',
			'post_content'   => require dirname( __FILE__ ) . '/import/content/homepage.php',
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'page_template'  => 'template-blocks.php',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		),
		'sitemap' => array(
			'post_title'     => 'Sitemap',
			'post_name'      => 'sitemap',
			'post_content'   => require dirname( __FILE__ ) . '/import/content/sitemap.php',
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		),
		'contact' => array(
			'post_title'     => 'Contact met '.get_bloginfo('name'),
			'post_name'      => 'contact',
			'post_content'   => require dirname( __FILE__ ) . '/import/content/contact.php',
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		),
	),
);