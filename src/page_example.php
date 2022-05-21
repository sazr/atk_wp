<?php

namespace atk_wp;

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
		$menu->addItem( 'Dashboard' );
		$menu->addItem( 'Some link' );

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

		\Atk4\Ui\CardDeck::addTo( $layout, [ 'search' => false, 'noRecordScopeActions' => [], 'singleScopeActions' => [] ] )->setSource(
			$page_data['comments'],
			array(
				'comment_author_email',
				'comment_date',
			)
		);

		$tabs = \Atk4\Ui\Tabs::addTo( $layout );
		\Atk4\Ui\Message::addTo( $tabs->addTab( 'Intro' ), array( 'Other tabs are loaded dynamically!' ) );

		$tabs->addTab(
			'Dynamic Tab',
			function ( \Atk4\Ui\VirtualPage $p ) {
				\Atk4\Ui\Message::addTo( $p, array( 'This is dynamic content' ) );
			}
		);
	}
}

