<?php

class InvoicePostTypeTest extends WP_UnitTestCase {
	public function test_permissions()
	{
		$this->assertTrue( defined('WPDSM_INVOICES_POST_TYPE') );

		$post_type = get_post_type_object( WPDSM_INVOICES_POST_TYPE );

		$this->assertInternalType('object', $post_type);

		$this->assertFalse( $post_type->public );
		$this->assertFalse( $post_type->show_in_nav_menus );

		//etc
	}
}
