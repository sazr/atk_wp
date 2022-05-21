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

		$app       = \atk_wp\App::get_app();

		$right = \Atk4\Ui\Panel\Right::addTo( $layout, array( 'dynamic' => array() ) );
		$msg   = \Atk4\Ui\Message::addTo( $right, array( 'Other' ) );

		$menu = \Atk4\Ui\Menu::addTo( $layout );
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

		\Atk4\Ui\Message::addTo( $layout, array( 'Some information' ) );
	}
}


new \atk_wp\Page_Example('Page_Example');

```


Once you've activated the plugin you can then code pages and instantiate them anywhere. For example in your theme's `functions.php` you can place this and it will create the page.

```
require_once( get_template_directory() . '/pages/your_page.php' );
new Your_Page('your_page_name');
```

And `/pages/your_page.php` could be something like:

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

## List of ATK UI widgets you can use

See [here](https://github.com/atk4/ui#bundled-and-planned-components) for the full list. Below are just some of the widgets you can use in your WordPress plugin or theme.


| Component                                                    | Description                                                  | Introduced |
| ------------------------------------------------------------ | ------------------------------------------------------------ | ---------- |
| View | Template, Render Tree and various patterns  | 0.1   |
| Button | Button in various variations including icons, labels, styles and tags | 0.1 |
| Input | Decoration of input fields, integration with buttons. | 0.2 |
| JS| Assign JS events and abstraction of PHP callbacks. | 0.2 |
| Header| Simple view for header. | 0.3 |
| Menu | Horizontal and vertical multi-dimensional menus with icons. | 0.4 |
| Form| Validation, Interactivity, Feedback, Layouts, Field types. | 0.4 |
| Layouts | Admin, Centered. | 0.4 |
| Table | Formatting, Columns, Status, Link, Template, Delete. | 1.0  |
| Grid | Toolbar, Paginator, Quick-search, Expander, Actions. | 1.1 |
| Message | Such as "Info", "Error", "Warning" or "Tip" for easy use.| 1.1 |
| Modal | Modal dialog with dynamically loaded content. | 1.1 |
| Reloading | Dynamically re-render part of the UI. | 1.1  |
| Actions | Extended buttons with various interactions  | 1.1 |
| Crud | Create, List, Edit and Delete records (based on Advanced Grid) | 1.1 |
| Tabs | 4 Responsive: Admin, Centered, Site, Wide. | 1.2 |
| Loader | Dynamically load itself and contained components inside. | 1.3  |
| Modal View | Open/Load contained components in a dialog. | 1.3  |
| Breadcrumb | Push links to pages for navigation. Wizard. | 1.4  |
| ProgressBar | Interactive display of a multi-step PHP code execution progress | 1.4 |
| Console | Execute server/shell commands and display progress live | 1.4 |
| Items and Lists | Flexible and high-performance way to display lists of items. | 1.4 |
| Wizard | Multi-step, wizard with temporary data storing. | 1.4  |
| Actions | Vizualization of user-defined actions | 2.0 |



# TODO: 

- Support ATK Wizards