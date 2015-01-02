<?php
include_once ("../../setup.php");

$page->page_title = 'Recent Moderations';
$page->page_header = 'Recent Moderations';

$page->privilege = 1;

$page->html .= 'Just a nice spot to see 20 of the most recent rejections and approvals. ';


//////////////////////
//Recently Approved //
//////////////////////
$sql = "SELECT * FROM `blockedusers` WHERE `approvalStatus` = 1\n"
    . "ORDER BY `blockedusers`.`approvalDate` DESC\n"
    . "LIMIT 20";
$result = $database->execute($sql);

foreach($result as &$value) {
    $sql = "SELECT ip FROM `reports` WHERE `id` = '".$value['id']."' ORDER BY date DESC LIMIT 1";
    $ipResult = $database->execute($sql);
    if(isset($ipResult[0]['ip']))
        $value['ip'] = $ipResult[0]['ip'];
    else
        $value['ip'] = '';
}
unset($value);


$page->html .= '<h4>Recently Approved (last 20)</h4>
<table class="review table table-hover table-bordered">
    <thead>
        <tr>
            <th class="id">ID</th>
            <th class="comment">Comment</th>
            <th class="date">Date ID Submitted</th>
            <th class="date">Date Approved</th>
            <th class="ip">IP Address</th>
            <th class="decision">Approved By</th>
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
    $page->html .= '<td>' . long2ip($value['ip']) . '</td>';
    $page->html .= '<td>' . $value['approvingUser'] . '</td>';



    $page->html .= '</tr>';
}   


    $page->html .= '</tbody>
</table>';

///////////////////////
// Recently Rejected //
///////////////////////

$sql = "SELECT * FROM `blockedusers` WHERE `approvalStatus` = -1\n"
    . "ORDER BY `blockedusers`.`approvalDate` DESC\n"
    . "LIMIT 20";
$result = $database->execute($sql);

foreach($result as &$value) {
    $sql = "SELECT ip FROM `reports` WHERE `id` = '".$value['id']."' ORDER BY date DESC LIMIT 1";
    $ipResult = $database->execute($sql);
    if(isset($ipResult[0]['ip']))
        $value['ip'] = $ipResult[0]['ip'];
    else
        $value['ip'] = '';
}
unset($value);

$page->html .= '<h4>Recently Rejected (last 10)</h4>
<table class="review table table-hover table-bordered">
    <thead>
        <tr>
            <th class="id">ID</th>
            <th class="comment">Comment</th>
            <th class="date">Date ID Submitted</th>
            <th class="date">Date Approved</th>
            <th class="ip">IP Address</th>
            <th class="decision">Approved By</th>
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
    $page->html .= '<td>' . long2ip($value['ip']) . '</td>';
    $page->html .= '<td>' . $value['approvingUser'] . '</td>';



    $page->html .= '</tr>';
}   


    $page->html .= '</tbody>
</table>';

$page->display();
?>
