# Page Options and Creation #
It is an extremely simple process to create a new page using this template. 

## Creating a new page ##
Creating a new page is very simply. Here is an example of `index.php`:

    include_once ("setup.php");
	
	$page->page_title = 'Home';
	$page->page_header = 'Home';
	
	$page->display();

The `include_once` path may need to be changed depending on where the file is stored. Most likely it is in a sub folder in `html`. Therefore the path will probably be something like:

    include_once ("../../setup.php");

## Note ##
If planning to use Widgets, it is suggested that you keep a folder named `html/widgets` in which the widgets are stored. The widgets **not** create/display a new page. Instead just design them using `$page->html .= '';`. This will ensure that any page can `include` them and utilize them from anywhere, as long as they already are displaying a page.