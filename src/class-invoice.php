<?php

class Invoice {
	/**
	 * WP Post ID
	 * @var integer
	 */
	protected $id = 0;

	/**
	 * Token
	 * @var string
	 */
	protected $token = '';

	/**
	 * Line items
	 * @var array
	 */
	protected $line_items = array();

	/**
	 * Total for invoice
	 * @var float
	 */
	protected $total = 0.0;

	/**
	 * Mailer object
	 * @var Mailer
	 */
	protected $mailer;

	public function __construct(Mailer $mailer = null)
	{
		$this->mailer = $mailer;
	}

	public function save()
	{
		if ( empty($this->id) ) {
			// Generate a new post!
			$this->id = $this->insert_post();
		}

		if ( empty($this->token) ) {
			$this->token = $this->generate_token();
			update_post_meta( $this->id, '_token', $this->token );
		}

		return true;
	}

	/**
	 * Override magic __get
	 * @param  string $key Property/method
	 * @return mixed value
	 */
	public function __get($key)
	{
		if ( method_exists( $this, 'get_' . $key ) ) {
			$value = call_user_func( array( $this, 'get_' . $key ) );
		} else {
			$value = $this->$key;
		}

		return $value;
	}

	/**
	 * MAGIC function to see if a property is set/empty/etc
	 * @param  string  $name Name of property
	 * @return boolean
	 */
	public function __isset( $name ) {
		if ( property_exists( $this, $name) ) {
			return false === empty( $this->$name );
		} else {
			return null;
		}
	}

	/**
	 * Insert post
	 * @return integer Post ID
	 */
	protected function insert_post()
	{
		$args = array(
			'post_type' => WPDSM_INVOICES_POST_TYPE,
			'post_status' => 'publish',
		);

		$post_id = wp_insert_post( $args );

		return $post_id;
	}

	/**
	 * Generate a token
	 * @return string
	 */
	protected function generate_token()
	{
		return md5( $this->id . rand() );
	}

	public function add_line_item( $data )
	{
		$this->line_items[] = $data;
		$this->total += (float) $data['price'];
	}

	public function get_stripe_form()
	{
		$description = '';
		$description = array_map(function($item) { return $item['description']; }, $this->line_items);
		$description = implode( ', ', $description );
		$description = esc_attr( $description );

		ob_start();
		?>
		<form action="/charge" method="POST">
		  <script
		    src="https://checkout.stripe.com/checkout.js"
		    class="stripe-button"
		    data-key="pk_test_6pRNASCoBOKtIshFeQd4XMUh"
		    data-image="/square-image.png"
		    data-name="Demo Site"
		    data-description="<?= $description ?>"
		    data-amount="<?= $this->get_total_in_cents() ?>">
		  </script>
		</form>
		<?php
		$form = ob_get_clean();

		return $form;
	}

	public function get_total_in_cents()
	{
		return $this->total * 100;
	}

	public function send_payment_notification()
	{
		$message = 'Your invoice for $' . $this->total . ' has been paid!';

		$this->mailer->send('admin@admin.com', 'Invoice Update', $message);

		return $message;
	}
}
