<?php //host details view page

//this page will be an include for the command_router.php controller file 
		//expecting $dets array of processed status details 
//include additional items by first adding them to the arrays on command_router.php 

function get_host_details($dets)
{
	global $NagiosUser; 
	
	$page="

	<h3>".gettext('Host Status Detail')."</h3>
	<div class='detailWrapper'>
	<h4><em>".gettext('Host').": </em>{$dets['Host']}</h4>
	<h5><em>".gettext('Member of').": </em>{$dets['MemberOf']}</h5>
	<h5><a href='index.php?type=services&host_filter={$dets['Host']}' title='".gettext('See All Services For This Host')."'>".gettext('See All Services For This Host')."</a></h5>
	<div class='detailcontainer'>
	<fieldset class='hostdetails'>
	<legend>".gettext('Advanced Details')."</legend>
	<table>
		<tr><td>".gettext('Current State')."</td><td class='{$dets['State']}'>{$dets['State']}</td></tr>
		<tr><td>".gettext('Status Information')."</td><td><div class='td_maxwidth'>{$dets['StatusInformation']}</div></td></tr>
		<tr><td>".gettext('Duration')."</td><td>{$dets['Duration']}</td></tr>
		<tr><td>".gettext('State Type')."</td><td>{$dets['StateType']}</td></tr>
		<tr><td>".gettext('Current Check')."</td><td>{$dets['CurrentCheck']}</td></tr>
		<tr><td>".gettext('Last Check')."</td><td>{$dets['LastCheck']}</td></tr>
		<tr><td>".gettext('Next Check')."</td><td>{$dets['NextCheck']}</td></tr>
		<tr><td>".gettext('Last State Change')."</td><td>{$dets['LastStateChange']}</td></tr>
		<tr><td>".gettext('Last Notification')."</td><td>{$dets['LastNotification']}</td></tr>
		<tr><td>".gettext('Check Type')."</td><td>{$dets['CheckType']}</td></tr>
		<tr><td>".gettext('Check Latency')."</td><td>{$dets['CheckLatency']}</td></tr>
		<tr><td>".gettext('Execution Time')."</td><td>{$dets['ExecutionTime']}</td></tr>
		<tr><td>".gettext('State Change')."</td><td>{$dets['StateChange']}</td></tr>
		<tr><td>".gettext('Performance Data')."</td><td><div class='td_maxwidth'>{$dets['PerformanceData']}</div></td></tr>
		
	</table>	
	
	</fieldset>
	</div><!-- end detailcontainer -->
	
	<div class='rightContainer'>
	
	"; 
	
	if(!$NagiosUser->if_has_authKey('authorized_for_read_only')) 
	$page.="	
	
		<fieldset class='attributes'>
		<legend>".gettext('Service Attributes')."</legend>	
		<table>	
		<tr><td class='{$dets['ActiveChecks']}'>".gettext('Active Checks').": {$dets['ActiveChecks']}</td>
		<td><a href='{$dets['CmdActiveChecks']}'><img src='views/images/action_small.gif' title='".gettext('Toggle Active Checks')."' class='iconLink' height='12' width='12' alt='Toggle' /></a></td></tr>
		<tr><td class='{$dets['PassiveChecks']}'>".gettext('Passive Checks').": {$dets['PassiveChecks']}</td>
		<td><a href='{$dets['CmdPassiveChecks']}'><img src='views/images/action_small.gif' title='".gettext('Toggle Passive Checks')."' class='iconLink' height='12' width='12' alt='Toggle' /></a></td></tr>
		<tr><td class='{$dets['Obsession']}'>".gettext('Obsession').": {$dets['Obsession']}</td>
		<td><a href='{$dets['CmdObsession']}'><img src='views/images/action_small.gif' title='".gettext('Toggle Obsession')."' class='iconLink' height='12' width='12' alt='Toggle' /></a></td></tr>
		<tr><td class='{$dets['Notifications']}'>".gettext('Notifications').": {$dets['Notifications']}</td>
		<td><a href='{$dets['CmdNotifications']}'><img src='views/images/action_small.gif' title='".gettext('Toggle Notifications')."' class='iconLink' height='12' width='12' alt='Toggle' /></a></td></tr>
		<tr><td class='{$dets['FlapDetection']}'>".gettext('Flap Detection').": {$dets['FlapDetection']}</td>
		<td><a href='{$dets['CmdFlapDetection']}'><img src='views/images/action_small.gif' title='".gettext('Toggle Flap Detection')."' class='iconLink' height='12' width='12' alt='Toggle' /></a></td></tr>
		</table>
		<p class='note'>".gettext('Commands will not appear until after page reload')."</p>
		</fieldset>
		
		
		<!-- Nagios Core Command Table -->
		<fieldset class='corecommands'>
		<legend>".gettext('Core Commands')."</legend>
		<table>
		<tr><td><a href='{$dets['MapHost']}' title='".gettext('Map Host')."'><img src='views/images/statusmapballoon.png' class='iconLink' height='12' width='12' alt='Map' /></a></td><td>".gettext('Locate host on map')."</td></tr>
		<tr><td><a href='{$dets['CmdCustomNotification']}' title='".gettext('Send Custom Notification')."'><img src='views/images/notification.gif' class='iconLink' height='12' width='12' alt='Notification' /></a></td><td>".gettext('Send custom notification')."</td></tr>
		<tr><td><a href='{$dets['CmdScheduleDowntime']}' title='".gettext('Schedule Downtime')."'><img src='views/images/downtime.png' class='iconLink' height='12' width='12' alt='Downtime' /></a></td><td>".gettext('Schedule downtime')."</td></tr>
		<tr><td><a href='{$dets['CmdScheduleDowntimeAll']}' title='".gettext('Schedule Recursive Downtime')."'><img src='views/images/downtime.png' class='iconLink' height='12' width='12' alt='Downtime' /></a></td><td>".gettext('Schedule downtime for this host and all services')."</td></tr>
		<tr><td><a href='{$dets['CmdScheduleChecks']}' title='".gettext('Schedule Check')."'><img src='views/images/schedulecheck.png' class='iconLink' height='12' width='12' alt='Schedule' /></a></td><td>".gettext('Schedule a check for all services of this host')."</td></tr>
		<tr><td><a href='{$dets['CmdAcknowledge']}' title='{$dets['AckTitle']}'><img src='views/images/ack.png' class='iconLink' height='12' width='12' alt='Acknowledge' /></a></td><td>{$dets['AckTitle']}</td></tr><!-- make into variable -->
		<tr><td colspan='2'><a class='label' href='{$dets['CoreLink']}' title='".gettext('See This Host In Nagios Core')."'>".gettext('See This Host In Nagios Core')."</a></td></tr>	
		</table>
		</fieldset>
		"; //end if authorized for object 
	
	$page.=" 
	</div><!-- end rightContainer -->
	</div><!-- end detailWrapper -->
	

	<!-- begin comment table -->
	<div class='commentTable'>
	";
	
	if(!$NagiosUser->if_has_authKey('authorized_for_read_only')) 
	{
		$page .=" 
		<h5 class='commentTable'>".gettext('Comments')."</h5>
		<p class='commentTable'><a class='label' href='{$dets['AddComment']}' title='".gettext('Add Comment')."'>".gettext('Add Comment')."</a></p> 

		<table class='commentTable'><tr><th>".gettext('Author')."</th><th>".gettext('Entry Time')."</th><th>".gettext('Comment')."</th><th>".gettext('Actions')."</th></tr>
		";
		//host comments table from Nagios core 
		
		//print host comments in table rows if any exist
		//see display_functions.php for function  
		$page .= get_host_comments($dets['Host']);
		//close comment table 
		$page .= '</table>';
	}
	$page.='</div><br />';
	return $page;
}

?> 
