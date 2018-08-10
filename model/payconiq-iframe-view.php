<?php
/**
 * Created by PhpStorm.
 * User: biont
 * Date: 03.11.16
 * Time: 14:27
 */

namespace wc_payconiq\model;

/**
 * Class PayPalIframeView
 *
 * @package WCPayPalPlus\WC
 */
class Payconiq_Iframe_View {

	/**
	 * View data
	 *
	 * @var array
	 */
	private $data;

	/**
	 * PayPalIframeView constructor.
	 *
	 * @param array $data View data.
	 */
	public function __construct( array $data ) {

		$this->data = $data;
	}

	/**
	 * Render the Paywall iframe
	 */
	public function render() {

		$id = $this->data['app_config']['placeholder'];
		?>
		<div id="<?php echo esc_attr( $id ) ?>"></div>
		<script type="application/javascript">
            if ( typeof PAYPAL != "undefined" ) {
                var ppp = PAYPAL.apps.PPP( <?php echo wp_json_encode( $this->data['app_config'] ) ?>);
            }
		</script>
		<style>
			<?php echo esc_attr('#'.$id) ?>
			iframe {
				height: 100% !important;
				width: 100% !important;
				*width: 100% !important;
			}
		</style>
		<?php
	}
}
