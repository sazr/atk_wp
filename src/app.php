<?php

namespace atk_wp;

class App extends \Atk4\Ui\App {


	protected static $instance = null;

	const NAME = 'atk_wp';

	protected static $atk_wp_pages = array();

	protected static $current_page;

	public static function get_app() {
		if ( self::$instance == null ) {
			self::$instance = new \atk_wp\App();
		}

		return self::$instance;
	}

	protected function __construct() {
		$this->catch_exceptions = false;

		parent::__construct(
			array(
				'always_run' => false,
			)
		);
		$this->initLayout( array( \Atk4\Ui\Layout\Centered::class ) );
		$this->db = new \Atk4\Data\Persistence\SQL( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD );

		add_action( 'admin_head', array( $this, 'output_assets' ) );
		add_filter( 'admin_body_class', array( $this, 'add_admin_body_classes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_ajax_' . self::NAME, array( $this, 'ajax_callback' ) );
		add_action( 'wp_ajax_nopriv_' . self::NAME, array( $this, 'ajax_callback' ) );
	}

	public static function setup() {
		add_action( 'init', static::class . '::get_app' );
	}

	public function output_assets() {
		echo $this->html->template->renderToHtml( 'HEAD' );
		echo $this->html->template->renderToHtml( 'style' );
		echo '<script type="application/javascript">' . $this->html->template->renderToHtml( 'InitJsBundle' ) . '</script>';
	}

	public function enqueue_assets( $hook_suffix ) {
		wp_enqueue_style( 'atk-wp-admin', plugin_dir_url( __FILE__ ) . '../css/atk_wp_admin.css', array(), '1.0' );
	}

	public function add_admin_body_classes( $classes ) {
		$classes .= ' atk_wp ';
		$classes .= $this->html->template->renderToHtml( 'class' );
		return $classes;
	}

	public function register_page( $page_slug, $page ) {
		self::$atk_wp_pages[ $page_slug ] = $page;
	}

	public function url( $page = array(), $needRequestUri = false, $extraRequestUriArgs = array() ) {
		if ( isset( $page['atk_wp_wizard'] ) ) {
			if ( is_admin() && isset( $_GET['page'] ) ) {
				$extraRequestUriArgs['page'] = $_GET['page'];
				// $this->page = null;
			}
		} else {
			$this->page = admin_url( 'admin-ajax' );
		}

		$extraRequestUriArgs['action']      = self::NAME;
		$extraRequestUriArgs['atk_wp_page'] = isset( self::$current_page ) ? self::$current_page : 'TODO';

		return parent::url( $page, $needRequestUri, $extraRequestUriArgs );
	}

	public function ajax_callback() {
		$page_slug = sanitize_text_field( $_REQUEST['atk_wp_page'] );

		if ( ! isset( self::$atk_wp_pages[ $page_slug ] ) ) {
			return null;
		}

		$page = self::$atk_wp_pages[ $page_slug ];
		$page->render( true /*, $data*/ );
		$this->run();
	}

	public function set_current_page( $page_slug = null ) {
		 self::$current_page = $page_slug;
	}
}

\atk_wp\App::setup();
