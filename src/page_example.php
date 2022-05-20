<?php

namespace atk_wp;

class ATKWP_Comment extends \Atk4\Data\Model {
    public $table = 'test';

    protected function init() : void
    {
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
	        [$this, 'render'],
	        0
	    );
	}

	protected function get_data() {
		$page_data = array(
			'comments' => null,
		);

		$page_data['comments'] = get_comments( array(
		    'date_query' => array(
		        'after' => '4 weeks ago',
		        'before' => 'tomorrow',
		        'inclusive' => true,
		    ),
		) );

		$json  = json_encode( $page_data['comments'] );
		$page_data['comments'] = json_decode( $json, true );

		error_log( print_r( $page_data['comments'], true) );


		return $page_data;
	}

	protected function _render($layout, $data=null) {

		$app = \atk_wp\App::get_app();
		$page_data = $this->get_data();

		// Prepare Persistence and data Model
		$data = [ 'test' => [ 1 => $page_data['comments'][0] ] ];
		$p = new \Atk4\Data\Persistence\Array_($data);
		$model = new ATKWP_Comment($p);

		// add Crud
		\Atk4\Ui\Header::addTo($layout, ['Crud with Array Persistence']);
		$c = \Atk4\Ui\Crud::addTo($layout, ['ipp' => 5]);
		$c->setModel($model);

		// $table = \Atk4\Ui\Table::addTo($layout);
		// $table->setSource($data['comments'], ['name']);

		// $model = new \Atk4\Data\Model( new \Atk4\Data\Persistence\Static_( $data['comments'] ) );
		// // $table->setSource( $data['comments'] );
		// $table->setModel($model, ['action']);

		// $m = new User($app->db);
    // $m = $m->tryLoadAny();

    // if (!$m) {
    	// $m = $m->save([
    	// 	'name' => 'a',
    	// 	'password' => 'b',
    	// 	'email' => 'c',
    	// ]);
    	// $m->save();
  //   // }
  //   $f = \Atk4\Ui\Form::addTo($layout);
  //   $f->setModel($m);
  //   $f->onSubmit(function(\Atk4\Ui\Form $form) {
  //   // implement subscribe here

  //   	$form->model->save();
  //   	return $form->success("Subscribed to newsletter.");
		// });

		// $m = new User($app->db);
		// \Atk4\Ui\Crud::addTo($layout)->setModel($m);

		// \Atk4\Ui\CardTable::addTo($layout)->setModel((new User($app->db))->tryLoadAny());

		$right = \Atk4\Ui\Panel\Right::addTo($layout, ['dynamic' => []]);
		$msg = \Atk4\Ui\Message::addTo($right,['Other']);
		$btn = \Atk4\Ui\Button::addTo($layout, ['Open Static']);
		$btn->js(true)->data('btn', '2');
		$btn->on('click', $right->jsOpen([], ['btn'], 'orange'));

		$menu = \Atk4\Ui\Menu::addTo($layout);
		$menu->addItem('foo', 'foo.php');
		$menu->addItem('bar');
		$menu->addItem('baz');
		$dropdown = \Atk4\Ui\Dropdown::addTo($menu, ['With Callback', 'dropdownOptions' => ['on' => 'hover']]);
		$dropdown->setSource(['a', 'b', 'c']);
		$dropdown->onChange(function ($itemId) {
		    return 'New seleced item id: ' . $itemId;
		});

		$btn = \Atk4\Ui\Button::addTo($layout)->set('Minimal');
		$btn->on('click', new \Atk4\Ui\JsToast('Hi there!'));

		$tabs = \Atk4\Ui\Tabs::addTo($layout);
		\Atk4\Ui\Message::addTo($tabs->addTab('Intro'), ['Other tabs are loaded dynamically!']);

		$tabs->addTab('Users', function (\Atk4\Ui\VirtualPage $p) {
		    // this tab is loaded dynamically, but also contains dynamic component
			\Atk4\Ui\Message::addTo($p, ['fdfddffd']);

		    // \Atk4\Ui\Crud::addTo($p)->setModel(new User($app->db));
		});

		// $tabs->addTab('Settings', function (\Atk4\Ui\VirtualPage $p) use ($app) {
		//     // second tab contains an AJAX form that stores itself back to DB

		//     // $m = new User($app->db);
		//     // $m = $m->tryLoadAny();
		//     // \Atk4\Ui\Form::addTo($p)->setModel($m);
		// });

		// $msg = \Atk4\Ui\Message::addTo($layout,['Other']);
	}
}

