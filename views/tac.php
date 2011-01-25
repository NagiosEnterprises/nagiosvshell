<?php //tactical overview page 


// Nagios V-Shell
// Copyright (c) 2010 Nagios Enterprises, LLC.
// Written by Mike Guthrie <mguthrie@nagios.com>
//
// LICENSE:
//
// This work is made available to you under the terms of Version 2 of
// the GNU General Public License. A copy of that license should have
// been provided with this software, but in any event can be obtained
// from http://www.fsf.org.
// 
// This work is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
// General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
// 02110-1301 or visit their web page on the internet at
// http://www.fsf.org.
//
//
// CONTRIBUTION POLICY:
//
// (The following paragraph is not intended to limit the rights granted
// to you to modify and distribute this software under the terms of
// licenses that may apply to the software.)
//
// Contributions to this software are subject to your understanding and acceptance of
// the terms and conditions of the Nagios Contributor Agreement, which can be found 
// online at:
//
// http://www.nagios.com/legal/contributoragreement/
//
//
// DISCLAIMER:
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
// INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
// PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
// HOLDERS BE LIABLE FOR ANY CLAIM FOR DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
// OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE 
// GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, STRICT LIABILITY, TORT (INCLUDING 
// NEGLIGENCE OR OTHERWISE) OR OTHER ACTION, ARISING FROM, OUT OF OR IN CONNECTION 
// WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

//returns $tac_data array - all of the counter statistics for the tactical overview 

function get_tac_data()
{
	//print "<br /><br /><br /><br />";
	global $username;
	global $NagiosData;
	$now = time();
	$info = $NagiosData->getProperty('info');
	$program = $NagiosData->getProperty('program');
	$program = $program[0]; 
	$services = $NagiosData->grab_details('service'); 
	$hosts = $NagiosData->grab_details('host');
	$h_states = $NagiosData->getProperty('host'); 	
	$tac_data = array(); //main array with all of the counter data for this function 

	//default to xml 
	//prepare data for consolidated array 
	$hoststates = get_state_of('hosts');
	$servicestates = get_state_of('services');
	
	///////////////////////////create function counts, LOOP ONCE with lots of counters 
	///problems acknowledged
		//sentry host counters for loops 
	$hostsDownAcknowledged = 0;
	$hostsDownUnhandled = 0;
	$hostsUnreachableAcknowledged = 0;
	$hostsUnreachableUnhandled = 0;
	$hostsUpDisabled = 0;
	$hostsDownDisabled = 0;
	$hostsUnreachableDisabled = 0;
	$hostsPending = 0; 
	$hostsPendingDisabled =0; 
	$hostsDownScheduled =0;
	$hostsUnreachableScheduled=0;  
	
	//sentry service counters for loops 
	$servicesWarningAcknowledged = 0;
	$servicesWarningUnhandled = 0;
	$servicesCriticalAcknowledged = 0;
	$servicesCriticalUnhandled = 0;
	$servicesUnknownAcknowledged = 0;
	$servicesUnknownUnhandled = 0;
	$servicesOkDisabled = 0; 
	$servicesWarningDisabled = 0;
	$servicesCriticalDisabled = 0;
	$servicesUnknownDisabled = 0;
	$servicesPending = 0;
	$servicesPendingDisabled = 0; 
	$servicesWarningHostProblem = 0; 
	$servicesUnknownScheduled = 0; 
	$servicesCriticalScheduled = 0; 
	$servicesWarningScheduled = 0; 
	$servicesUnknownHostProblem=0; 
	$servicesWarningHostProblem=0;
	$servicesCriticalHostProblem=0;

	foreach($hosts as $h)
	{
		if($h['last_check']==0 && $h['active_checks_enabled'] == 1)  { $hostsPending++;  continue; } //skip ahead for pending 
		if($h['last_check'] == 0 && $h['active_checks_enabled'] == 0)  { $hostsPendingDisabled++;  continue; } //pending 
		if($h['current_state']==0 && $h['active_checks_enabled'] == 0) $hostsUpDisabled++;
		if($h['current_state']==1 && $h['active_checks_enabled'] == 0) $hostsDownDisabled++;				
		if($h['current_state']==1 && $h['problem_has_been_acknowledged'] > 0) $hostsDownAcknowledged++; 
		if($h['current_state']==1 && $h['problem_has_been_acknowledged'] == 0)  $hostsDownUnhandled++; 
		if($h['current_state']==1 && $h['scheduled_downtime_depth'] > 0) $hostsDownScheduled++;	
		if($h['current_state']==2 && $h['active_checks_enabled'] == 0) $hostsUnreachableDisabled++;		
		if($h['current_state']==2 && $h['problem_has_been_acknowledged'] > 0)  $hostsUnreachableAcknowledged++; 
		if($h['current_state']==2 && $h['problem_has_been_acknowledged'] == 0) $hostsUnreachableUnhandled++; 
		if($h['current_state']==2 && $h['scheduled_downtime_depth'] > 0) $hostsUnreachableScheduled++;
	} 

	foreach($services as $s)
	{
		$current_host = $h_states[$s['host_name']];	
		if($s['last_check'] == 0 && $s['active_checks_enabled'] == 1) { $servicesPending++; continue; } 
		if($s['last_check'] == 0 && $s['active_checks_enabled'] == 0) { $servicesPendingDisabled++;  continue;  }
		if($s['active_checks_enabled'] == 0) $servicesDisabled++;			 
		if($s['current_state']==0 && $s['active_checks_enabled'] == 0) $servicesOkDisabled++;
		if($s['current_state']==1 && $s['active_checks_enabled'] == 0) $servicesWarningDisabled++;
		if($s['current_state']==1 && $current_host['current_state'] > 0) $servicesWarningHostProblem++; 						
		if($s['current_state']==1 && $s['problem_has_been_acknowledged'] > 0) $servicesWarningAcknowledged++; 
		if($s['current_state']==1 && $s['problem_has_been_acknowledged'] == 0) $servicesWarningUnhandled++;
		if($s['current_state']==1 && $s['scheduled_downtime_depth'] > 0) $servicesWarningScheduled++; 
		if($s['current_state']==2 && $s['active_checks_enabled'] == 0) $servicesCriticalDisabled++;
		if($s['current_state']==2 && $s['problem_has_been_acknowledged'] > 0)  $servicesCriticalAcknowledged++; 
		if($s['current_state']==2 && $s['problem_has_been_acknowledged'] == 0) $servicesCriticalUnhandled++; 
		if($s['current_state']==2 && $s['scheduled_downtime_depth'] > 0) $servicesCriticalScheduled++;		
		if($s['current_state']==2 && $current_host['current_state'] > 0) $servicesCriticalHostProblem++;
		if($s['current_state']==3 && $s['active_checks_enabled'] == 0) $servicesUnknownDisabled++;
		if($s['current_state']==3 && $s['problem_has_been_acknowledged'] > 0) $servicesUnknownAcknowledged++; 
		if($s['current_state']==3 && $s['problem_has_been_acknowledged'] == 0) $servicesUnknownUnhandled++;  
		if($s['current_state']==3 && $current_host['current_state'] > 0) $servicesUnknownHostProblem++;
		if($s['current_state']==3 && $s['scheduled_downtime_depth'] > 0) $servicesUnknownScheduled++; 
	}
	
	
	//////////////////////////////////////////////////////////
	///build main tac data array for different viewing output 
	$tac_data = array(
	//host counts 
	'hosts_total' =>  ($hoststates['UP'] + $hoststates['DOWN'] + $hoststates['UNREACHABLE']),
	'hostsUpTotal' => $hoststates['UP'],
	'hostsDownTotal' => $hoststates['DOWN'],
	'hostsUnreachableTotal' => $hoststates['UNREACHABLE'],
		
	//service counts 
	'servicesOkTotal' => $servicestates['OK'],
	'servicesWarningTotal' => $servicestates['WARNING'],
	'servicesCriticalTotal' => $servicestates['CRITICAL'],
	'servicesUnknownTotal' => $servicestates['UNKNOWN'],
	
	//monitoring features 
	'flap_detection' => $program['enable_flap_detection'],
	'notifications' => $program['enable_notifications'],
	'active_service_checks' =>$program['active_service_checks_enabled'],
	'passive_service_checks' =>$program['passive_service_checks_enabled'],
	'event_handlers' => $program['enable_event_handlers'],
	
	//sentry host counters for loops 
	'hostsDownAcknowledged' => $hostsDownAcknowledged,
	'hostsDownUnhandled' => $hostsDownUnhandled,
	'hostsUnreachableAcknowledged' => $hostsUnreachableAcknowledged,
	'hostsUnreachableUnhandled' => $hostsUnreachableUnhandled,
	'hostsUnreachableScheduled' => $hostsUnreachableScheduled, 
	'hostsUpDisabled' => $hostsUpDisabled,
	'hostsDownDisabled' => $hostsDownDisabled,
	'hostsDownScheduled' => $hostsDownScheduled, 
	'hostsUnreachableDisabled' => $hostsUnreachableDisabled,
	'hostsPending' => $hostsPending, 
	'hostsPendingDisabled' => $hostsPendingDisabled, 
	
	//sentry service counters for loops 
	'servicesWarningAcknowledged' => $servicesWarningAcknowledged,
	'servicesWarningUnhandled' => $servicesWarningUnhandled,
	'servicesCriticalAcknowledged' => $servicesCriticalAcknowledged,
	'servicesCriticalUnhandled' => $servicesCriticalUnhandled,
	'servicesUnknownAcknowledged' => $servicesUnknownAcknowledged,
	'servicesUnknownUnhandled' => $servicesUnknownUnhandled,
	'servicesOkDisabled' => $servicesOkDisabled, 
	'servicesWarningDisabled' => $servicesWarningDisabled,
	'servicesCriticalDisabled' => $servicesCriticalDisabled,
	'servicesUnknownDisabled' => $servicesUnknownDisabled,
	'servicesPending' => $servicesPending,
	'servicesPendingDisabled' => $servicesPendingDisabled, 
	'servicesWarningHostProblem' => $servicesWarningHostProblem, 
	'servicesUnknownHostProblem' => $servicesUnknownHostProblem, 
	'servicesCriticalHostProblem' => $servicesCriticalHostProblem, 
	'servicesWarningScheduled' => $servicesWarningScheduled, 
	'servicesUnknownScheduled' => $servicesUnknownScheduled,	
	'servicesCriticalScheduled' => $servicesCriticalScheduled,
	);//end array 
	
	
	//print_r($tac_data); 
	return $tac_data; 
	/*
	if($type=='html') //additional array elements are needed 
	{
	
		$last_command = $now - (settype($info['last_command_check'], 'integer') ) ;		
		//$last_command = $now - $last_command;
    	// XXX Timezone warning here.  Fix
		$lastcmd = date('D M d H:i s\s', $last_command);	
		$hostlink = htmlentities(BASEURL.'index.php?type=hosts&state_filter=');
		$servlink = htmlentities(BASEURL.'index.php?type=services&state_filter=');
			// flap detection 
		$host_fd = check_boolean('host', 'flap_detection_enabled', 0) ? $host_fd : 0;
		$service_fd = check_boolean('service', 'flap_detection_enabled', 0) ? $service_fd : 0;
		$host_flap = check_boolean('host', 'is_flapping', 1) ? $host_flap : 0; 
		$service_flap = check_boolean('service', 'is_flapping', 1) ? $service_flap : 0; 
			//notifications
		$host_ntf = check_boolean('host', 'notifications_enabled', 0) ? $host_ntf : 0;
		//$service_ntf = check_boolean('service', 'notifications_enabled', 0) ? $service_ntf : 0;		
		$host_eh = check_boolean('host', 'event_handler_enabled', 0) ? $host_eh : 0;
		$service_eh = check_boolean('service', 'event_handler_enabled', 0) ? $service_eh : 0; 	
		$host_ac = check_boolean('host', 'active_checks_enabled', 0) ? $host_ac : 0; 
		$service_ac = check_boolean('service', 'active_checks_enabled', 0) ? $service_ac : 0;		
		$host_pc = check_boolean('host', 'passive_checks_enabled', 0) ? $host_pc : 0; 
		$service_pc = check_boolean('service', 'passive_checks_enabled', 0) ? $service_pc : 0;
	}	
	*/ 
	
} //end get_tac_data() 

get_tac_data(); 

function get_tac()
{
	$tac = "";
	$tac .= info_table().'<br />';
	$tac .= overview_table().'<br />';
	$tac .= hosts_table().'<br />';
	$tac .= services_table().'<br />';
	$tac .= features_table();
	$tac .= search_box().'<br />';

	return $tac;
}

function info_table()
{
	$info_table =<<<INFOTABLE
<!-- ##################Nagios Info Table################### -->
<div id='infodiv'>

<p class='note'>Nagios V-Shell<br />
	Copyright (c) 2010 <br />
	Nagios Enterprises, LLC. <br />
   Written by Mike Guthrie<br />
   For questions, feedback, <br /> 
   or support, visit the <br />
   <a href="http://support.nagios.com/forum/viewforum.php?f=19" target="_blank">V-Shell Forum</a>.</p>

</div>
INFOTABLE;
	return $info_table;
}

function overview_table()
{
	global $username;
	global $NagiosData;
	$info = $NagiosData->getProperty('info');
	$last_command = settype($info['last_command_check'], 'integer') ;	
	$now = time();
	$last_command = $now - $last_command;
    // XXX Timezone warning here.  Fix
	$lastcmd = date('D M d H:i s\s', $last_command);
	//Fri Sep 3 13:42:55 CDT 2010

	$overview_table = <<<OVERVIEWTABLE
<table class="tac">
<tr><th>Tactical Monitoring Overview</th></tr>
	<tr>
		<td>
			Last Check: $lastcmd<br />
			Updated every 90 seconds<br />
			Nagios® Core™ {$info['version']} - www.nagios.org<br />
			Logged in as $username<br />
		</td>
	</tr>
</table>
OVERVIEWTABLE;
	return $overview_table;
}


function hosts_table()
{
	$states = get_state_of('hosts');
	$hostlink = htmlentities(BASEURL.'index.php?type=hosts&state_filter=');

	$hosts_table =<<<HOSTSTABLE
<!-- ########################HOSTS TABLE########################## -->
<table class="tac">
<tr><th>Hosts</th></tr>
<tr>
	<td class="ok"><a href="{$hostlink}UP">{$states['UP']}</a> Up</td>
	<td class="down"><a href="{$hostlink}DOWN">{$states['DOWN']}</a> Down</td>
	<td class="unreachable"><a href="{$hostlink}UNREACHABLE">{$states['UNREACHABLE']}</a> Unreachable</td>				
</tr> 

</table>
HOSTSTABLE;
	return $hosts_table;
}

function services_table()
{
	$sts = get_state_of('services');
	$servlink = htmlentities(BASEURL.'index.php?type=services&state_filter=');

	$services_table = <<<SERVICESTABLE
<!-- ######################SERVICES TABLE##################### -->
<table class="tac">
<tr><th>Services</th></tr>

  <tr>
	   <td class='ok'><a href='{$servlink}OK'>{$sts['OK']}</a> Ok</td>
	   <td class="critical"><a href="{$servlink}CRITICAL">{$sts['CRITICAL']}</a> Critical</td>
		<td class="warning"><a href="{$servlink}WARNING">{$sts['WARNING']}</a> Warning</td>		
		<td class="unknown"><a href="{$servlink}UNKNOWN">{$sts['UNKNOWN']}</a> Unknown</td>
  </tr>
  
</table>
SERVICESTABLE;
	return $services_table;
}

function search_box() {
	$box = <<<FILTERDIV
<!-- #####################SEARCH BOX####################-->
<div class='resultFilter'>
	<form id='resultfilterform' action='{$_SERVER['PHP_SELF']}' method='get'>
		<input type="hidden" name="type" value="services">
		<label class='label' for='name_filter'>Search String</label>
		<input type="text" name='name_filter'></input>
		<input type='submit' name='submitbutton' value='Filter' />
	</form>
</div>
FILTERDIV;
	return $box;
}

function features_table()
{
	$features_table = <<<FEATURESTABLE
<!-- #####################ENABLED FEATURES TABLE ####################-->
<table class="tac">
<tr><th>Monitoring Features</th></tr>
<tr>
	<td>Flap Detection</td><td>Notifications</td><td>Event Handlers</td>
	<td>Active Checks</td><td>Passive Checks</td>
</tr><tr>	
FEATURESTABLE;

// monitoring features enabled/disabled 

///////////////////////FLAPPING////////////////////////////////
	$features_table .= '<td class="green">'."\n";
//////////////////////////////////////////////////////////////////
//checking for disabled flap detection 

	$host_fd = check_boolean('host', 'flap_detection_enabled', 0); 
	if($host_fd)
	{
		$features_table .= '<span class="red">'.$host_fd.' Hosts disabled</span>';
	}
	else
	{
		$features_table .= 'All Hosts enabled';
	}
	$features_table .= '<br />';	
	$service_fd = check_boolean('service', 'flap_detection_enabled', 0); 
	if($service_fd)
	{
		$features_table .= '<span class="red">'.$host_fd.' Hosts disabled</span>';
	}
	else
	{
		$features_table .= 'All Services enabled';
	}	
	$features_table .= "<br />\n";
	///////////////////////////////////////////////////////////
	//checking for flapping... 
	$host_flap = check_boolean('host', 'is_flapping', 1); 
	if($host_flap)
	{
		$features_table .= '<span class="red">'.$host_flap.' Hosts flapping';
	}
	else
	{
		$features_table .= 'No hosts flapping';
	}	
	$features_table .= "<br />\n";
	$service_flap = check_boolean('service', 'is_flapping', 1); 
	if($service_flap)
	{
		$features_table .= '<span class="red">'.$service_flap.' Services flapping</span>';
	}
	else
	{
		$features_table .= 'No Services flapping';
	}	
	$features_table .= "<br />\n";
	$features_table .= '</td>'; //close table data 
	
	/////////////////////////////NOTIFICATIONS///////////////////////////////
	$features_table .= '<td class="green">';
	//////////////////////////////////////////////////////////////////
	//checking for notifications 
	
	$host_ntf = check_boolean('host', 'notifications_enabled', 0); 
	if($host_ntf)
	{
		$features_table .= '<span class="red">'.$host_ntf.' Hosts disabled</span>';
	}
	else
	{
		$features_table .= 'All Hosts enabled';
	}
	$features_table .= "<br />\n";	
	$service_ntf = check_boolean('service', 'notifications_enabled', 0); 
	if($service_ntf)
	{
		$features_table .= '<span class="red">'.$service_ntf.' Services disabled</span>';
	}
	else
	{
		$features_table .= 'All Services enabled';
	}	
	$features_table .= "<br /></td>\n"; //close table data 
	
	///////////////////////////////EVENT HANDLERS/////////////////////////////
	$features_table .= '<td class="green">';
	//////////////////////////////////////////////////////////////////
	//checking for event handlers  
	
	$host_eh = check_boolean('host', 'event_handler_enabled', 0); 
	if($host_ntf)
	{
		$features_table .= '<span class="red">'.$host_eh.' Hosts disabled</span>';
	}
	else
	{
		$features_table .= 'All Hosts enabled';
	}
	$features_table .= "<br />\n";	
	$service_eh = check_boolean('service', 'event_handler_enabled', 0); 
	if($service_eh)
	{
		$features_table .= '<span class="red">'.$service_eh.' Services disabled</span>';
	}
	else
	{
		$features_table .= 'All Services enabled';
	}	
	$features_table .= "<br /></td>\n"; //close table data 
	
	/////////////////////////////////ACTIVE CHECKS///////////////////////////	
	$features_table .= '<td class="green">';
	//////////////////////////////////////////////////////////////////
	//checking for active checks  
	
	$host_ac = check_boolean('host', 'active_checks_enabled', 0); 
	if($host_ac)
	{
		$features_table .= '<span class="red">'.$host_ac.' Hosts disabled</span>';
	}
	else
	{
		$features_table .= 'All Hosts enabled';
	}
	$features_table .= "<br />\n";	
	$service_ac = check_boolean('service', 'active_checks_enabled', 0); 
	if($service_ac)
	{
		$features_table .= '<span class="red">'.$service_ac.' Services disabled</span>';
	}
	else
	{
		$features_table .= 'All Services enabled';
	}	
	$features_table .= "<br /></td>\n"; //close table data 
		
		
	/////////////////////////////////PASSIVE CHECKS///////////////////////////	
	$features_table .= '<td class="green">';
	//////////////////////////////////////////////////////////////////
	//checking for passive checks  
	
	$host_pc = check_boolean('host', 'passive_checks_enabled', 0); 
	if($host_pc)
	{
		$features_table .= '<span class="red">'.$host_pc.' Hosts disabled</span>';
	}
	else
	{
		$features_table .= 'All Hosts enabled';
	}
	$features_table .= "<br />\n";	
	$service_pc = check_boolean('service', 'passive_checks_enabled', 0); 
	if($service_pc)
	{
		$features_table .= '<span class="red">'.$service_pc.' Services disabled</span>';
	}
	else
	{
		$features_table .= 'All Services enabled';
	}	
	$features_table .= "<br /></td>\n"; //close table data 	

	$features_table .= <<<FEATURESTABLE
</tr>
</table>
<br />
FEATURESTABLE;
	return $features_table;
}

////////////////////////////////////////
//creates xml output for tactical overview 
//expecting $tac_data array for values 
function tac_xml($td)
{
	//$td is $tac_data from main array 
	$xmldoc='<?xml version="1.0" encoding="utf-8"?>'; 
	$xmldoc.=<<<XMLDOC
	
<tacinfo>	
	<!-- hosts -->
	<hoststatustotals>
		<down>
			<total>{$td['hostsDownTotal']}</total>
			<unhandled>{$td['hostsDownUnhandled']}</unhandled>
			<scheduleddowntime>{$td['hostsDownScheduled']}</scheduleddowntime>	
			<acknowledged>{$td['hostsDownAcknowledged']}</acknowledged>
			<disabled>{$td['hostsDownDisabled']}</disabled>
		</down>
		<unreachable>
			<total>{$td['hostsUnreachableTotal']}</total>
			<unhandled>{$td['hostsUnreachableUnhandled']}</unhandled>
			<scheduledunreachabletime>{$td['hostsUnreachableScheduled']}</scheduledunreachabletime>
			<acknowledged>{$td['hostsUnreachableAcknowledged']}</acknowledged>
			<disabled>{$td['hostsUnreachableDisabled']}</disabled>
		</unreachable>	
		<up>
			<total>{$td['hostsUpTotal']}</total>
			<disabled>{$td['hostsUpDisabled']}</disabled>
		</up>
		<pending>
			<total>{$td['hostsPending']}</total>
			<disabled>{$td['hostsPendingDisabled']}</disabled>
		</pending>
	</hoststatustotals>
	
	<!-- services -->
	<servicestatustotals>
		<warning>
			<total>{$td['servicesWarningTotal']}</total>	
			<unhandled>{$td['servicesWarningUnhandled']}</unhandled>
			<scheduleddowntime>{$td['servicesWarningScheduled']}</scheduleddowntime>
			<acknowledged>{$td['servicesWarningAcknowledged']}</acknowledged>
			<hostproblem>{$td['servicesWarningHostProblem']}</hostproblem>
			<disabled>{$td['servicesWarningDisabled']}</disabled>
		</warning>
		<unknown>
			<total>{$td['servicesUnknownTotal']}</total>
			<unhandled>{$td['servicesUnknownUnhandled']}</unhandled>
			<scheduleddowntime>{$td['servicesUnknownScheduled']}</scheduleddowntime>	
			<acknowledged>{$td['servicesUnknownAcknowledged']}</acknowledged>
			<hostproblem>{$td['servicesUnknownHostProblem']}</hostproblem>
			<disabled>{$td['servicesUnknownDisabled']}</disabled>
		</unknown>
		<critical>
			<total>{$td['servicesCriticalTotal']}</total>
			<unhandled>{$td['servicesCriticalUnhandled']}</unhandled>
			<scheduleddowntime>{$td['servicesCriticalScheduled']}</scheduleddowntime>
			<acknowledged>{$td['servicesCriticalAcknowledged']}</acknowledged>
			<hostproblem>{$td['servicesCriticalHostProblem']}</hostproblem>	
			<disabled>2</disabled>
		</critical>
		<ok>
			<total>{$td['servicesOkTotal']}</total>
			<disabled>{$td['servicesOkDisabled']}</disabled>
		</ok>
		<pending>
			<total>{$td['servicesPending']}</total>
			<disabled>{$td['servicesPendingDisabled']}</disabled>
		</pending>
	</servicestatustotals>
	
	<!-- monitoring features -->
	<monitoringfeaturestatus>	
		<flapdetection>
			<global>{$td['flap_detection']}</global>
		</flapdetection>
		<notifications>
			<global>{$td['notifications']}</global>
		</notifications>
		<eventhandlers>
			<global>{$td['event_handlers']}</global>
		</eventhandlers>
		<activeservicechecks>
			<global>{$td['active_service_checks']}</global>
		</activeservicechecks>
		<passiveservicechecks>		
			<global>{$td['passive_service_checks']}</global>
		</passiveservicechecks>
	</monitoringfeaturestatus>
</tacinfo>

XMLDOC;

	return $xmldoc; 	

}



?>