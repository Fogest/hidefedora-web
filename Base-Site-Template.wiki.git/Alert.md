# Using the Alert Class #
The Alert class is an easy way to create message prompts that appear on pages. There are 4 supported alert's:

1. Error
2. Warning
3. Success
4. Info

Each alert has 2 different options which you can choose:

1. `display<AlertName>($message)`
2. `display<AlertName>Block($message)`

Both of these options take the same $message parameter. Do not state the error name in the $message. Simply state the problem itself. Ex:

> Unable to complete query.

To make sure an alert is displayed be sure to include the error like so:

    $page->html .= $alert->displayError($message);

If you miss the html part, you will most likely end up with the alert not appearing.