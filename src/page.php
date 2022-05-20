<?php

namespace atk_wp;

trait Page {

	public $page_slug;

	public function __construct($page_slug) {
	  $this->page_slug = $page_slug;

	  add_action( 'admin_menu', [$this, 'setup_menu'] );

	  \atk_wp\App::get_app()->register_page($this->page_slug, $this);
	}

	abstract protected function _render($layout, $data=null);

	public function setup_menu() {

	}

	public function render($is_ajax=false, $data=null) {
		$app = \atk_wp\App::get_app();
		$app->set_current_page($this->page_slug); //, $data);

		$layout = \Atk4\Ui\View::addTo($app, ['defaultTemplate' => path_join( plugin_dir_path( __FILE__ ), '../templates/page.html' ), 'name' => 'atk_wp_layout', /* 'classes' => 'atk_wp'*/]);

		$this->_render($layout, $data);
		
		if (!$is_ajax)
			echo $layout->render();

		// $app->set_current_page(null);
	}
}

?>