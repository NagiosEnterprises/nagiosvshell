<?php //host details view page

//this page will be an include for the command_router.php controller file 
		//expecting $dets array of processed status details 
//include additional items by first adding them to the arrays on command_router.php 

function get_host_details($dets)
{
//defining string using HEREDOC syntax, which is a format for multiline 
//   print statements with variables 
//  
// 
	$page=<<<PAGE

	<h3>Host Status Detail</h3>
	<div class="detailWrapper">
	<h4><em>Host: </em>{$dets['Host']}</h4>
	<h5><em>Member of: </em>{$dets['MemberOf']}</h5>
	<h5><a href="index.php?type=services&name_filter={$dets['Host']}" title="See All Services For This Host">See All Services For This Host</a></h5>
	<div class="detailcontainer">
	<fieldset class="hostdetails">
	<legend>Advanced Details</legend>
	<table>
		<tr><td>Current State</td><td class="{$dets['State']}">{$dets['State']}</td></tr>
		<tr><td>Status Information</td><td><div class="td_maxwidth">{$dets['StatusInformation']}</div></td></tr>
		<tr><td>Duration</td><td>{$dets['Duration']}</td></tr>
		<tr><td>State Type</td><td>{$dets['StateType']}</td></tr>
		<tr><td>Current Check</td><td>{$dets['CurrentCheck']}</td></tr>
		<tr><td>Last Check</td><td>{$dets['LastCheck']}</td></tr>
		<tr><td>Next Check</td><td>{$dets['NextCheck']}</td></tr>
		<tr><td>Last State Change</td><td>{$dets['LastStateChange']}</td></tr>
		<tr><td>Last Notification</td><td>{$dets['LastNotification']}</td></tr>
		<tr><td>Check Type</td><td>{$dets['CheckType']}</td></tr>
		<tr><td>Check Latency</td><td>{$dets['CheckLatency']}</td></tr>
		<tr><td>Execution Time</td><td>{$dets['ExecutionTime']}</td></tr>
		<tr><td>State Change</td><td>{$dets['StateChange']}</td></tr>
		<tr><td>Performance Data</td><td><div class="td_maxwidth">{$dets['PerformanceData']}</div></td></tr>
		
	</table>	
	
	</fieldset>
	</div><!-- end detailcontainer -->
	
	<div class="rightContainer">
	<fieldset class='attributes'>
	<legend>Service Attributes</legend>	
	<table>	
	<tr><td class="{$dets['ActiveChecks']}">Active Checks: {$dets['ActiveChecks']}</td>
	<td><a href="{$dets['CmdActiveChecks']}"><img src="views/images/action_small.gif" title="Toggle Active Checks" class="iconLink" height="12" width="12" alt="Toggle" /></a></td></tr>
	<tr><td class="{$dets['PassiveChecks']}">Passive Checks: {$dets['PassiveChecks']}</td>
	<td><a href="{$dets['CmdPassiveChecks']}"><img src="views/images/action_small.gif" title="Toggle Passive Checks" class="iconLink" height="12" width="12" alt="Toggle" /></a></td></tr>
	<tr><td class="{$dets['Obsession']}">Obsession: {$dets['Obsession']}</td>
	<td><a href="{$dets['CmdObsession']}"><img src="views/images/action_small.gif" title="Toggle Obsession" class="iconLink" height="12" width="12" alt="Toggle" /></a></td></tr>
	<tr><td class="{$dets['Notifications']}">Notifications: {$dets['Notifications']}</td>
	<td><a href="{$dets['CmdNotifications']}"><img src="views/images/action_small.gif" title="Toggle Notifications" class="iconLink" height="12" width="12" alt="Toggle" /></a></td></tr>
	<tr><td class="{$dets['FlapDetection']}">Flap Detection: {$dets['FlapDetection']}</td>
	<td><a href="{$dets['CmdFlapDetection']}"><img src="views/images/action_small.gif" title="Toggle Flap Detection" class="iconLink" height="12" width="12" alt="Toggle" /></a></td></tr>
	</table>
	<p class="note">Commands will not appear until after page reload</p>
	</fieldset>
	
	
	<!-- Nagios Core Command Table -->
	<fieldset class='corecommands'>
	<legend>Core Commands</legend>
	<table>
	<tr><td><a href="{$dets['MapHost']}" title="Map Host"><img src="views/images/statusmapballoon.png" class="iconLink" height="12" width="12" alt="Map" /></a></td><td>Locate host on map</td></tr>
	<tr><td><a href="{$dets['CmdCustomNotification']}" title="Send Custom Notification"><img src="views/images/notification.gif" class="iconLink" height="12" width="12" alt="Notification" /></a></td><td>Send custom notification</td></tr>
	<tr><td><a href="{$dets['CmdScheduleDowntime']}" title="Schedule Downtime"><img src="views/images/downtime.png" class="iconLink" height="12" width="12" alt="Downtime" /></a></td><td>Schedule downtime</td></tr>
	<tr><td><a href="{$dets['CmdScheduleDowntimeAll']}" title="Schedule Recursive Downtime"><img src="views/images/downtime.png" class="iconLink" height="12" width="12" alt="Downtime" /></a></td><td>Schedule downtime for this host and all services</td></tr>
	<tr><td><a href="{$dets['CmdScheduleChecks']}" title="Schedule Check"><img src="views/images/schedulecheck.png" class="iconLink" height="12" width="12" alt="Schedule" /></a></td><td>Schedule a check for all services of this host</td></tr>
	<tr><td><a href="{$dets['CmdAcknowledge']}" title="{$dets['AckTitle']}"><img src="views/images/ack.png" class="iconLink" height="12" width="12" alt="Acknowledge" /></a></td><td>{$dets['AckTitle']}</td></tr><!-- make into variable -->
	<tr><td colspan="2"><a class="label" href="{$dets['CoreLink']}" title="See This Host In Nagios Core">See This Host In Nagios Core</a></td></tr>	
	</table>
	</fieldset>
	
	</div><!-- end rightContainer -->
	</div><!-- end detailWrapper -->
	


	<!-- begin comment table -->
	<div class="commentTable">
	<h5 class="commentTable">Comments</h5>
	<p class="commentTable"><a class="label" href="{$dets['AddComment']}" title="Add Comment">Add Comment</a></p> 
	
	<table class="commentTable"><tr><th>Author</th><th>Entry Time</th><th>Comment</th><th>Actions</th></tr>


PAGE;


	//host comments table from Nagios core 
	
	//print host comments in table rows if any exist
	//see display_functions.php for function  
	$page .= get_host_comments($dets['Host']);
	//close comment table 
	$page .= '</table></div><br />';
	return $page;
}

?> 
