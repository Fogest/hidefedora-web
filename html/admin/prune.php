<?php
include_once ("../../setup.php");

$page->page_title = 'Prune IP';
$page->page_header = 'Prune IP';

$page->privilege = 1;

$page->html .= 'Some users keep spamming false reports. To remove ALL of their reports and set their reputation to -500 simply insert their IP address and hit "Submit". 
	Please be careful with this function as it is time consuming to reverse!';

if(isset($_POST['submit'])) {
	$ip = ip2long($_POST['ipAddress']);
	$args['rep'] = -500;
	$args['isBanned'] = 1;
	$where['ip'] = $ip;
	$result = $database->update('reportingusers',$args,$where);
	if(!$result)
		die('Error pruning user');

	$sql = "DELETE blockedusers FROM `reportingusers` \n"
    . "INNER JOIN reports ON reportingusers.ip=reports.ip\n"
    . "INNER JOIN blockedusers ON reports.id=blockedusers.id\n"
    . "WHERE reportingusers.ip = ".$ip;

    $result = $database->execute($sql);
    if(!$result)
    	die('Error pruning user');
    echo 'Success!';
}

$page->html .= '<!-- Text input-->
</br><form method="POST"><div class="control-group">
  <label for="ipAddress">IP Address to Prune</label>
    <input id="ipAddress" name="ipAddress" type="text" placeholder="127.0.0.1" class="input-xlarge" required="">
</div>

<!-- Button -->
<div class="control-group">
    <button id="submit" name="submit" class="btn btn-primary">Submit</button>
</div></form>';

$page->display();
?>
