<?php
include_once ("../../setup.php");

$page->page_title = 'Unban';
$page->page_header = 'Unban';

$page->privilege = 1;

$page->html .= '<div id="status"></div>This is a private internal page to be utilized to unban a user. Simply enter their ID
				and hit "Submit" and the user will be switched from "Approved" to "Rejected" within the hour';

$page->html .= '
	<!-- Text input-->
	<div class="control-group">
	  <label for="profileId">Profile ID</label>
	    <input id="profileId" name="profileId" type="text" placeholder="12345678987654321" class="input-xlarge" required="">
	</div>

	<!-- Button -->
	<div class="control-group">
	    <button id="submit-unban" name="submit-unban" class="btn btn-primary">Submit</button>
	</div>';

//Appeals: 

/*$result = $database->execute($sql);

$page->html .= '<h4>Ban Appeals (last 15)</h4>
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<th>ID</th>
			<th>Date Submitted</th>
			<th>Date Approved</th>
		</tr>
	</thead>
	<tbody>';
foreach($result as $value) {
	$page->html .= '<tr class="success">';

	$page->html .= '<td><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['id'] . '</td>';
	$page->html .= '<td>' . $value['date'] . '</td>';
	$page->html .= '<td>' . $value['approvalDate'] . '</td>';
	$page->html .= '</tr>';
}	

	$page->html .= '</tbody>
</table>'; */

$page->display();
?>