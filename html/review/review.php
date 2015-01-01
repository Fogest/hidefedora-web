<?php
include_once ("../../setup.php");

$page->page_title = 'Review';
$page->page_header = 'Review';

$page->privilege = 1;

///////////////////////
//Review Queue Table //
///////////////////////

$sql = "SELECT COUNT(*) as Count FROM `blockedusers` WHERE `approvalStatus`=0";
$count = $database->execute($sql);
$count = $count[0]['Count'];

$sql = "SELECT * FROM `blockedusers` WHERE `approvalStatus` = 0\n"
    . "ORDER BY `blockedusers`.`date` ASC\n";
$result = $database->execute($sql);

$page->html .= $alert->displayInfo("Some major work is being done on the review system. You will notice the count is now named weight. There will
	soon be a system in place to rank things better based on keywords, the rep of the reporting user, and on the number of reports like it is already doing. Right
	now the system is only using report counts!");

$page->html .= '<div class="banner-message">There are <strong>'.$count.'</strong> report(s) remaining! The current page shows a maximum of 75 of these.</div><div id="pop1" class="popbox">
				    <h2>Success!</h2>
				    <p>This is an example popbox.</p>
				</div>
				';

$page->html .= '<h4 class="floatLeft">Review Queue</h4>
<div class="all-top">
	<button class="rejectAll btn btn-danger reject" type="button" name="rejectAll">Reject Remaining</button>
	<button class="approveAll btn btn-success approve" type="button" name="ApproveAll">Approve Remaining</button>
</div>
<div style="clear: both;"></div>
<table class="table table-hover table-bordered review">
	<thead>
		<tr>
			<th class="id">Name</th>
			<th class="comment">Comment</th>
			<th class="date">Date</th>
			<th class="ip">IP</th>
			<th class="weight">Weight</th>
			<th class="decision">Approve/Reject</th>
		</tr>
	</thead>
	<tbody>';

foreach($result as &$value) {
	$sql = "SELECT DISTINCT reports.ip,reportingusers.rep FROM reports \n"
    . "INNER JOIN reportingusers\n"
    . "ON reports.ip=reportingusers.ip\n"
    . "WHERE `id` = '".$value['id']."'\n";
    $weightingResult = $database->execute($sql);
    $value['weight'] = 0;
    foreach($weightingResult as $weight)
    	$value['weight'] = $weight['rep'] + $value['weight'];
}
unset($value);

usort($result, function ($a, $b) {
    if ($a['weight'] == $b['weight']) return 0;
    return ($a['weight'] > $b['weight']) ? -1 : 1;
});
$count = 0;
foreach($result as $value) {
	$count = $count + 1;
	$page->html .= '<tr>';
	if($value['displayName'] != NULL) {
		if($value['youtubeUrl'] != NULL && $value['youtubeUrl'] != 'Manual')
			$page->html .= '<td class="id"><img src="'.$value['profilePictureUrl'].'" alt="'.$value['displayName'].'"><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['displayName'] . '</a> <a target="_blank" href="'. $value['youtubeUrl'] .'">(^)</a></td>';
		else
			$page->html .= '<td class="id"><img src="'.$value['profilePictureUrl'].'" alt="'.$value['displayName'].'"><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['displayName'] . '</a></td>';
	} else {
		$page->html .= '<td class="id"><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['id'] . '</a> - User likely valid, however errors fetching user data!</td>';
	}

	$page->html .= '<td class="comment">' . $value['comment'] . '</td>';

	//Gotta get the last reporting user for this report to get most recent date and most recent ip.
	$sql = "SELECT * FROM `reports` WHERE `id` = '".$value['id']."'";
	$userInfoResult = $database->execute($sql);
	if(isset($userInfoResult[0]['id'])) {
		$page->html .= '<td class="date">' . $userInfoResult[0]['date'] . '</td>';
		$page->html .= '<td class="ip">' . long2ip($userInfoResult[0]['ip']) . '</td>';
	} else {
		$page->html .= '<td class="date"></td>';
		$page->html .= '<td class="ip"></td>';
	}
	$page->html .= '<td class="count">' . $value['weight'] . '</td>';
	$page->html .= '<td class="decision"><button data-profileid="'.$value['id'].'" class="btn btn-success approve" type="button" name="'.$value['pkey'].'">Approve</button><button data-profileid="'.$value['id'].'" class="btn btn-danger reject" type="button" name="'.$value['pkey'].'">Reject</button></td>';

	$page->html .= '</tr>';
	if($count >= 75)
		break;
}		
	
$page->html .= '</tbody>
</table>

<div class="all-bottom">
	<button class="rejectAll btn btn-danger reject" type="button" name="rejectAll">Reject Remaining</button>
	<button class="approveAll btn btn-success approve" type="button" name="ApproveAll">Approve Remaining</button>
</div><div style="clear: both;"></div>';


////////////////////////////
//Recently Approved Table //
////////////////////////////

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
			<th>Date ID Submitted</th>
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

	if($value['displayName'] != NULL) {
		if($value['youtubeUrl'] != NULL && $value['youtubeUrl'] != 'Manual')
			$page->html .= '<td class="id"><img src="'.$value['profilePictureUrl'].'" alt="'.$value['displayName'].'"><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['displayName'] . '</a> <a target="_blank" href="'. $value['youtubeUrl'] .'">(^)</a></td>';
		else
			$page->html .= '<td class="id"><img src="'.$value['profilePictureUrl'].'" alt="'.$value['displayName'].'"><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['displayName'] . '</a></td>';
	} else {
		$page->html .= '<td class="id"><a target="_blank" href="https://plus.google.com/' . $value['id'] . '">' . $value['id'] . '</a> - User likely valid, however errors fetching user data!</td>';
	}

	

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