<?php
include_once ("../../setup.php");

$page->page_title = 'Review';
$page->page_header = 'Review';

$page->privilege = 1;

$sql = "SELECT * FROM `blockedusers` WHERE `approvalStatus` = 0\n"
    . "ORDER BY `blockedusers`.`count` DESC, `blockedusers`.`date` ASC\n"
    . "LIMIT 50";
$result = $database->execute($sql);

$page->html .= '<h4 class="floatLeft">Review Queue</h4>
<div class="all">
	<button id="rejectAll" class="btn btn-danger reject" type="button" name="rejectAll">Reject Remaining</button>
	<button id="approveAll" class="btn btn-success approve" type="button" name="ApproveAll">Approve Remaining</button>
</div>
<div style="clear: both;"></div>
<table class="table table-hover table-bordered review">
	<thead>
		<tr>
			<th class="id">ID</th>
			<th class="comment">Comment</th>
			<th class="date">Date</th>
			<th class="ip">IP</th>
			<th class="count">Reports</th>
			<th class="decision">Approve/Reject</th>
		</tr>
	</thead>
	<tbody>';

foreach($result as $value) {
	$page->html .= '<tr>';

	if($value['youtubeUrl'] != NULL && $value['youtubeUrl'] != 'Manual')
		$page->html .= '<td class="id"><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['id'] . '</a> <a target="_blank" href="'. $value['youtubeUrl'] .'">(^)</a></td>';
	else
		$page->html .= '<td class="id"><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['id'] . '</a></td>';
	$page->html .= '<td class="comment">' . $value['comment'] . '</td>';
	$page->html .= '<td class="date">' . $value['date'] . '</td>';
	$page->html .= '<td class="ip">' . long2ip($value['ip']) . '</td>';
	$page->html .= '<td class="count">' . $value['count'] . '</td>';
	$page->html .= '<td class="decision"><button class="btn btn-success approve" type="button" name="'.$value['pkey'].'">Approve</button><button class="btn btn-danger reject" type="button" name="'.$value['pkey'].'">Reject</button></td>';

	$page->html .= '</tr>';
}		
	
$page->html .= '</tbody>
</table>';

$sql = "SELECT * FROM `blockedusers` WHERE `approvalStatus` = 1 OR `approvalStatus` = -1\n"
    . "ORDER BY `blockedusers`.`approvalDate` DESC\n"
    . "LIMIT 10";
$result = $database->execute($sql);


$page->html .= '<h4>Recently Approved (last 10)</h4>
<table class="table table-hover table-bordered">
	<thead>
		<tr>
			<th>ID</th>
			<th>Comment</th>
			<th>Date Submitted</th>
			<th>Date Approved</th>
			<th>Approved By</th>
		</tr>
	</thead>
	<tbody>';

foreach($result as $value) {
	
	if($value['approvalStatus'] == 1)
		$page->html .= '<tr class="success">';
	else
		$page->html .= '<tr class="error">';

	if(($value['youtubeUrl'] != NULL) && ($value['youtubeUrl'] != 'Manual'))
		$page->html .= '<td><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['id'] . '</a> <a target="_blank" href="'. $value['youtubeUrl'] .'">(^)</a></td>';
	else
		$page->html .= '<td><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['id'] . '</a></td>';
	

	$page->html .= '<td class="comment">' . $value['comment'] . '</td>';
	$page->html .= '<td>' . $value['date'] . '</td>';
	$page->html .= '<td>' . $value['approvalDate'] . '</td>';
	$page->html .= '<td>' . $value['approvingUser'] . '</td>';



	$page->html .= '</tr>';
}	


	$page->html .= '</tbody>
</table>';

$page->display();
?>