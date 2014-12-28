<?php
include_once ("setup.php");

$page->page_title = 'Home';
$page->page_header = 'Home';

$sql = "SELECT COUNT(*) as Count FROM `blockedusers` WHERE `approvalStatus`=1";
$result = $database->execute($sql);
$result = $result[0]['Count'];


$page->html .= '<div id="status"></div>
<h4>Submit Fedora User for Review - Banned <strong>' . $result . '</strong> users and counting!</h4>

<!-- Text input-->
<div class="control-group">
  <label for="profileUrl">Profile URL</label>
    <input id="profileUrl" name="profileUrl" type="text" placeholder="https://plus.google.com/12345678987654321" class="input-xlarge" required="">
</div>

<!-- Button -->
<div class="control-group">
    <button id="submit" name="submit" class="btn btn-primary">Submit</button>
</div>

	<div class=" ad"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- banner hidefedora -->
	<ins class="adsbygoogle"
	     style="display:inline-block;width:728px;height:90px"
	     data-ad-client="ca-pub-7190930054905704"
	     data-ad-slot="8651857830"></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script></div>
';

$sql = "SELECT * FROM `blockedusers`\n"
. "WHERE `approvalStatus`=1\n"
. "ORDER BY `blockedusers`.`approvalDate` DESC\n"
. "LIMIT 15";
$result = $database->execute($sql);


$page->html .= '<h4>Recently Approved (last 15)</h4>
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

	$page->html .= '<td><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['id'] . '</a></td>';
	$page->html .= '<td>' . $value['date'] . '</td>';
	$page->html .= '<td>' . $value['approvalDate'] . '</td>';
	$page->html .= '</tr>';
}	

	$page->html .= '</tbody>
</table>';

$page->display();
?>
