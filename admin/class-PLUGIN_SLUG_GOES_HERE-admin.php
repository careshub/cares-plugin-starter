<?php
/**
 * CARES Network Data Tools
 *
 * @package   PACKAGE_NAME_GOES_HERE
 * @author    PLUGIN_AUTHOR_NAME
 * @license   GPL-2.0+
 * @link      https://engagementnetwork.org
 * @copyright 2016 CARES Network
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `public/class-PLUGIN_SLUG_GOES_HERE.php`
 *
 * @package   PACKAGE_NAME_GOES_HERE_Admin
 * @author  PLUGIN_AUTHOR_NAME
 */
class PLUGIN_PREFIX_Admin {

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'PLUGIN_SLUG_GOES_HERE';

	/**
	 *
	 * The current version of the plugin.
	 *
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $version = '1.0.0';

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		$this->version = PLUGIN_PREFIX_get_plugin_version();

	}

	/**
	 * Hook WordPress filters and actions here.
	 *
	 * @since     1.0.0
	 */
	public function hook_actions() {
		// Load admin style sheet and JavaScript.
		// add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add settings
		add_action( 'admin_init', array( $this, 'settings_init' ) );

		// Add an action link pointing to the options page.
		// $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		// add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Data Tools', 'PLUGIN_SLUG_GOES_HERE' ),
			__( 'Data Tools', 'PLUGIN_SLUG_GOES_HERE' ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		?>
		<form action="<?php echo admin_url( 'options.php' ) ?>" method='post'>
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

			<?php
			settings_fields( $this->plugin_slug );
			do_settings_sections( $this->plugin_slug );
			submit_button();
			?>

		</form>
		<?php
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', 'PLUGIN_SLUG_GOES_HERE' ) . '</a>'
			),
			$links
		);

	}

	/**
	 * Register the settings and set up the sections and fields for the
	 * global settings screen.
	 *
	 * @since    1.0.0
	 */
	public function settings_init() {

		// Color customizations.
		add_settings_section(
			'cdt_custom_colors',
			__( 'Update the key colors in the Data Tools window to match this site\'s theme.', 'PLUGIN_SLUG_GOES_HERE' ),
			array( $this, 'cdt_custom_colors_section_callback' ),
			$this->plugin_slug
		);

		register_setting( $this->plugin_slug, 'cdt_custom_colors', array( $this, 'sanitize_custom_colors' ) );
		add_settings_field(
			'cdt_custom_colors',
			__( 'Customize the colors.', 'PLUGIN_SLUG_GOES_HERE' ),
			array( $this, 'render_color_customization' ),
			$this->plugin_slug,
			'cdt_custom_colors'
		);
	}

	/**
	 * Provide a section description for the global settings screen.
	 *
	 * @since    1.0.0
	 */
	public function cdt_custom_colors_section_callback() {}

	/**
	 * Add the form inputs for the plugin option form.
	 *
	 * @since    1.0.0
	 */
	public function render_color_customization() {
		$colors = get_option( 'cdt_custom_colors' );
		?>
		<label for="cdt_custom_colors-accentColorMain">Main Accent Color:</label>&emsp;<input type='text' class="wp-color-picker-input" name='cdt_custom_colors[accentColorMain]' id='cdt_custom_colors-accentColorMain' value='<?php echo $colors["accentColorMain"]; ?>'/>
		<?php
		/*
		* Add other color inputs as needed. Add their "keys" to the
		* $allowed_keys array in sanitize_custom_colors().
		* All colors are saved together in a serialized option, 'cdt_custom_colors'.
		*/
	}

	/**
	 * Sanitize the input.
	 *
	 * @since    1.0.0
	 */
	public function sanitize_custom_colors( $value ) {
		$allowed_keys = array( 'accentColorMain' );

		if ( ! is_array( $value ) ) {
			$value = preg_split( '/[\s,]+/', $value );
		}

		foreach ( $value as $key => $color ) {
			// Throw away bad keys.
			if ( ! in_array( $key, $allowed_keys ) ) {
				unset( $value[ $key ] );
				continue;
			}
			// Clean up values using WP function.
			$value[ $key ] = sanitize_hex_color( $color );
		}

		return $value;
	}

}
