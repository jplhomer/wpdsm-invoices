<?php

class InvoiceTest extends WP_UnitTestCase {
	public function test_token()
	{
		$invoice = new Invoice;
		$invoice->save();

		$this->assertFalse( empty($invoice->id) );

		$this->assertFalse( empty($invoice->token) );

		// _token
		$token = get_post_meta( $invoice->id, '_token', true );

		$this->assertFalse( empty($token) );

		$args = array(
			'post_type' => WPDSM_INVOICES_POST_TYPE,
			'meta_key' => '_token',
			'meta_value' => $invoice->token,
		);

		$results = get_posts( $args );

		$this->assertCount( 1, $results );
		$this->assertEquals( $invoice->id, $results[0]->ID );
	}

	public function test_line_items()
	{
		$invoice = new Invoice;

		$this->assertCount( 0, $invoice->line_items );
		$this->assertEquals( 0.0, $invoice->total );

		$line_item = array(
			'description' => 'Snickers',
			'price' => 50,
		);

		$invoice->add_line_item( $line_item );

		$this->assertCount( 1, $invoice->line_items );
		$this->assertEquals( $line_item['price'], $invoice->total );
	}

	public function test_payment_form()
	{
		$invoice = new Invoice;

		$line_item = array(
			'description' => 'Snickers',
			'price' => 50,
		);

		$invoice->add_line_item( $line_item );
		$invoice->save();

		$form = $invoice->get_stripe_form();
		$this->assertFalse( empty($form) );
		$this->assertTrue( !!strstr($form, 'data-description="Snickers"') );
		$this->assertTrue( !!strstr($form, 'data-amount="5000"') );
	}

	public function test_payment_notification()
	{
		$mailer = $this->getMockBuilder( 'Mailer' )
					  ->setMethods( array('send') )
					  ->getMock();

		$invoice = new Invoice($mailer);

		$line_item = array(
			'description' => 'Snickers',
			'price' => 50,
		);

		$invoice->add_line_item( $line_item );
		$invoice->save();

		$mailer->expects( $this->once() )
				->method( 'send' );

		$message = $invoice->send_payment_notification();

		$this->assertEquals( 'Your invoice for $50 has been paid!', $message );
	}
}
