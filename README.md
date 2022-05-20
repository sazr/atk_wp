# ATK WordPress Plugin


### Setup instructions

- You'll need [Composer](https://getcomposer.org/) installed to use this
- Download this repository and place it in your WordPress plugin directory (for eg, `wp-content/plugins/`).
- Open command prompt/terminal and navigate to this repository (for eg, `cd c:/xampp/htdocs/mywebsite/wp-content/plugins/atk_wp`).
- In command prompt/terminal issue the following command `composer install`. You should now have a new folder called `vendor`.
- Navigate to your website and activate the ATK WP plugin.
- The plugin will display an example page that demonstrates how to use ATK UI. You can see this page in the dashboard as 'Example ATK Page'.


### Add a new page that uses ATK

```

class Page_Example {

	use \atk_wp\Page;

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

	protected function _render($layout, $data=null) {

		$app = \atk_wp\App::get_app();

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
	}
}


new \atk_wp\Page_Example('Page_Example');

```


Once you've activated the plugin you can then code pages and instantiate them anywhere. For example in your theme's `functions.php` you can simple place this and it will create the page.

```
require_once( get_template_directory() . '/your_pages/your_page.php' );
new Your_Page('your_page_name');
```

And `/your_pages/your_page.php` could be something like:

```
class Your_Page {

	use \atk_wp\Page;

	public function setup_menu() {
	    add_menu_page(
	        'Your Page',
	        'Your Page',
	        'manage_options',
	        'your_page-index',
	        [$this, 'render'],
	        0
	    );
	}

	protected function _render($layout, $data=null) {

		$app = \atk_wp\App::get_app();

		$msg = \Atk4\Ui\Message::addTo( $layout,['Your Page'] );
	}
}
```


# TODO: 

- Support ATK Wizards