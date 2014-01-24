# Form Creation and Examples #
Forms can be tedious to create. As of now there is no class created to allow for easy form creation, so for now the examples should help.

## Example of form creation ##
	<form name="songRequest" action="index.php" method="post">
		<label>Song Name</label>
		<input type="text" class="input-xlarge required" placeholder="Song name" name="songName_Request" maxlength="230">
		<label>Artist</label>
		<input type="text" class="input-xlarge required" placeholder="Artist" name="artistName_Request" maxlength="230">
		<label>Your Name</label>
		<input type="text" class="input-xlarge" placeholder="Your name here" name="name_Request" maxlength="230">
		<label>Your Email (will email you if song is chosen or not)</label>
		<input type="email" class="input-xlarge" placeholder="Email Address" name="emailAddress_Request" maxlength="230">
		<label>Anything to note?</label>
		<textarea rows="3" class="input-xlarge" name="notes_Request"></textarea><br/>
		<input type="submit" name="submit_Request" class="btn btn-primary">
	</form>

As seen in the above code, using the `required` class is beneficial as it will show star in the input box meaning it is required. 

## Handling Form Submits (POST) ##
Check for the submit, else display form:

    if($_POST['submit_Request'])

Validation is key here. If the field is **required** use a `isset` check first and make sure that the **string length** is not equal to `0` using `strlen`. This will also need a `trim` inside of it to ensure that blank space is not included.

If there is a max length *(Really should be one)* then another `strlen` check should be used to check if the string is longer than the set `maxlength`

If any of these checks fails do the following:

	$errors[] = "Error" ;

Then output the errors:

	for($i = 0; $i < count($errors);$i++) {
		$page->html .= $alert->displayError($errors[$i], true);
	}