<?php
include_once ("setup.php");

$page->page_title = 'Home';
$page->page_header = 'Home';

if(isset($_POST['submit'])) {
	$regex = "/((https|http):\/\/plus\.google\.com\/\d+)|(^\d+$)/"; 
	$profileurl = $_POST['profileUrl'];

	if(!isset($_POST['profileUrl']))
		$page->html .= $alert->displayError('Profile URL not filled.');
	else if(trim($profileurl) == '')
		$page->html .= $alert->displayError('Profile URL not filled.');
	else if(!preg_match($regex,$profileurl))
		$page->html .= $alert->displayError('URL must be from YouTube or Google+');
	else {
		$regex = "/\d+/";
		$id = array();
		if(!preg_match_all($regex,$profileurl,$id))
			$page->html .= $alert->displayError('Error finding ID in url.');
		else {
			$query = "SELECT id FROM blockedusers WHERE id=" . $database->clean_data($id[0][0]) . ";";
			$result = $database->execute($query);
			if(isset($result[0]['id'])) {
				$page->html .= $alert->displayError('ID is already in database. If it is not being blocked yet it could be because it is under review still!');
			} else {

				$table = 'blockedusers';
				$args['id'] = $id[0][0];
				$args['date'] = date("Y-m-d H:i:s");

				$result = $database->insert($table,$args);
				if(!$result)
					$page->html .= $alert->displayError('Failed to save to database!');
				else
					$page->html .= $alert->displaySuccess('URL saved; Now in review process!');
			}
		}
	}


} else {

	$page->html .= '<form id="fedora-form" name="fedora-form" method="post" class="form-horizontal">
	<fieldset>

	<!-- Form Name -->
	<legend>Submit Fedora User for Review</legend>

	<!-- Text input-->
	<div class="control-group">
	  <label class="control-label" for="profileUrl">Profile URL</label>
	  <div class="controls">
	    <input id="profileUrl" name="profileUrl" type="text" placeholder="https://plus.google.com/12345678987654321" class="input-xlarge" required="">
	    
	  </div>
	</div>

	<!-- Button -->
	<div class="control-group">
	  <label class="control-label" for="submit">Submit</label>
	  <div class="controls">
	    <button id="submit" name="submit" class="btn btn-primary">Submit</button>
	  </div>
	</div>

	</fieldset>
	</form>
	';
}

$page->display();
?>