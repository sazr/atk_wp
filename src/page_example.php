<?php

namespace atk_wp;

class ATKWP_Comment extends \Atk4\Data\Model {
	public $table = 'test';

	protected function init() : void {
		parent::init();

		$this->addField( 'comment_ID' );
		$this->addField( 'comment_post_ID' );
		$this->addField( 'comment_author' );
		$this->addField( 'comment_author_email' );
		$this->addField( 'comment_author_url' );
		$this->addField( 'comment_author_IP' );
		$this->addField( 'comment_date' );
		$this->addField( 'comment_date_gmt' );
		$this->addField( 'comment_content' );
		$this->addField( 'comment_karma' );
		$this->addField( 'comment_approved' );
		$this->addField( 'comment_agent' );
		$this->addField( 'comment_type' );
		$this->addField( 'comment_parent' );
		$this->addField( 'user_id' );
	}
}

class Page_Example {

	use Page;

	public function setup_menu() {
		add_menu_page(
			'Example ATK Page',
			'Example ATK Page',
			'manage_options',
			'atk_wp-index',
			array( $this, 'render' ),
			0
		);
	}

	protected function get_data() {
		$page_data = array(
			'comments' => null,
		);

		$page_data['comments'] = get_comments(
			array(
				'date_query' => array(
					'after'     => '4 weeks ago',
					'before'    => 'tomorrow',
					'inclusive' => true,
				),
			)
		);

		$json                  = json_encode( $page_data['comments'] );
		$page_data['comments'] = json_decode( $json, true );

		return $page_data;
	}

	protected function _render( $layout, $data = null ) {

		$app       = \atk_wp\App::get_app();
		$page_data = $this->get_data();

		$right = \Atk4\Ui\Panel\Right::addTo( $layout, array( 'dynamic' => array() ) );
		$msg   = \Atk4\Ui\Message::addTo( $right, array( 'Other' ) );

		$menu = \Atk4\Ui\Menu::addTo( $layout );
		$menu->addItem( 'foo', 'foo.php' );
		$menu->addItem( 'bar' );
		$menu->addItem( 'baz' );

		$dropdown = \Atk4\Ui\Dropdown::addTo(
			$menu,
			array(
				'With Callback',
				'dropdownOptions' => array( 'on' => 'hover' ),
			)
		);
		$dropdown->setSource( array( 'a', 'b', 'c' ) );
		$dropdown->onChange(
			function ( $itemId ) {
				return 'New seleced item id: ' . $itemId;
			}
		);

		$btn = \Atk4\Ui\Button::addTo( $menu, array( 'Open Submenu' ) );
		$btn->js( true )->data( 'btn', '2' );
		$btn->on( 'click', $right->jsOpen( array(), array( 'btn' ), 'orange' ) );

		$btn = \Atk4\Ui\Button::addTo( $menu )->set( 'Show Toast' );
		$btn->on( 'click', new \Atk4\Ui\JsToast( 'Hi there!' ) );

		$app->getExecutorFactory()->registerTrigger(
			$app->getExecutorFactory()::CARD_BUTTON,
			array(
				Button::class,
				null,
				'blue',
				'icon' => 'plane',
			),
			null
		);
		\Atk4\Ui\CardDeck::addTo( $layout )->setSource(
			$page_data['comments'],
			array(
				'comment_author_email',
				'comment_date',
				'comment_content',
			)
		);

		$tabs = \Atk4\Ui\Tabs::addTo( $layout );
		\Atk4\Ui\Message::addTo( $tabs->addTab( 'Intro' ), array( 'Other tabs are loaded dynamically!' ) );

		$tabs->addTab(
			'Users',
			function ( \Atk4\Ui\VirtualPage $p ) {
				// this tab is loaded dynamically, but also contains dynamic component
				\Atk4\Ui\Message::addTo( $p, array( 'fdfddffd' ) );

				// \Atk4\Ui\Crud::addTo($p)->setModel(new User($app->db));
			}
		);

		// $tabs->addTab('Settings', function (\Atk4\Ui\VirtualPage $p) use ($app) {
		// second tab contains an AJAX form that stores itself back to DB

		// $m = new User($app->db);
		// $m = $m->tryLoadAny();
		// \Atk4\Ui\Form::addTo($p)->setModel($m);
		// });

		// $msg = \Atk4\Ui\Message::addTo($layout,['Other']);
	}
}

