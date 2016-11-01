<?php

function wpdsm_invoice_init() {
	register_post_type( WPDSM_INVOICES_POST_TYPE, array(
		'labels'            => array(
			'name'                => __( 'Invoices', 'wpdsm-invoices' ),
			'singular_name'       => __( 'Invoice', 'wpdsm-invoices' ),
			'all_items'           => __( 'All Invoices', 'wpdsm-invoices' ),
			'new_item'            => __( 'New Invoice', 'wpdsm-invoices' ),
			'add_new'             => __( 'Add New', 'wpdsm-invoices' ),
			'add_new_item'        => __( 'Add New Invoice', 'wpdsm-invoices' ),
			'edit_item'           => __( 'Edit Invoice', 'wpdsm-invoices' ),
			'view_item'           => __( 'View Invoice', 'wpdsm-invoices' ),
			'search_items'        => __( 'Search Invoices', 'wpdsm-invoices' ),
			'not_found'           => __( 'No Invoices found', 'wpdsm-invoices' ),
			'not_found_in_trash'  => __( 'No Invoices found in trash', 'wpdsm-invoices' ),
			'parent_item_colon'   => __( 'Parent Invoice', 'wpdsm-invoices' ),
			'menu_name'           => __( 'Invoices', 'wpdsm-invoices' ),
		),
		'public'            => false,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => false,
		'supports'          => array( 'title', 'editor' ),
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-admin-post',
		'show_in_rest'      => true,
		'rest_base'         => 'wpdsm_invoice',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'wpdsm_invoice_init' );

function wpdsm_invoice_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['wpdsm_invoice'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Invoice updated. <a target="_blank" href="%s">View Invoice</a>', 'wpdsm-invoices'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'wpdsm-invoices'),
		3 => __('Custom field deleted.', 'wpdsm-invoices'),
		4 => __('Invoice updated.', 'wpdsm-invoices'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Invoice restored to revision from %s', 'wpdsm-invoices'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Invoice published. <a href="%s">View Invoice</a>', 'wpdsm-invoices'), esc_url( $permalink ) ),
		7 => __('Invoice saved.', 'wpdsm-invoices'),
		8 => sprintf( __('Invoice submitted. <a target="_blank" href="%s">Preview Invoice</a>', 'wpdsm-invoices'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Invoice scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Invoice</a>', 'wpdsm-invoices'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Invoice draft updated. <a target="_blank" href="%s">Preview Invoice</a>', 'wpdsm-invoices'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'wpdsm_invoice_updated_messages' );
