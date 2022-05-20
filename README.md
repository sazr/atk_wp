# ATK WordPress Plugin


### Setup instructions

- Download this repository and place it in your WordPress plugin directory (for eg, `wp-content/plugins/`).
- Open command prompt/terminal and navigate to this repository (for eg, `cd c:/xampp/htdocs/mywebsite/wp-content/plugins/atk_wp`).
- In command prompt/terminal issue the following command `composer install`. You should now have a new folder called `vendor`.
- Navigate to your website and activate the ATK WP plugin.


### Add a new page that uses ATK

```

class Page_HelloATK {

	use \atk_wp\Page;

	public function setup_menu() {
	    add_menu_page(
	        'Hello ATK',
	        'Hello ATK',
	        'manage_options',
	        'atk_wp-index',
	        [$this, 'render'],
	        0
	    );
	}

	protected function _render($layout, $data=null) {

		$app = $this->plugin;

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
	}
}


$app = new \atk_wp\App('my_plugin_name');
new Page_HelloATK($app, 'Page_HelloATK');

```

# TODO: 

- Support ATK Wizards