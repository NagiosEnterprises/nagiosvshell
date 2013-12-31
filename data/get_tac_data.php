<?php  //get_tac_data.php   gathers data array for tactical overview information 

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
	global $NagiosUser; 	
	
	$now = time();
	$info     = $NagiosData->getProperty('info');
	$program  = $NagiosData->getProperty('program');
	//$program  = $program[0];
	$services = $NagiosData->grab_details('service'); 
	$hosts    = $NagiosData->grab_details('host');
	//$h_states = $NagiosData->getProperty('host'); 	
	$tac_data = array(); //main array with all of the counter data for this function 

	//default to xml 
	//prepare data for consolidated array 
	$hoststates    = get_state_of('hosts');
	$servicestates = get_state_of('services');
	
	
	$lcc = $now - (settype($info['last_command_check'], 'integer') ); 
	$tac_data = array(
	//global data
	'version' => $info['version'], 
	'last_command_check' => $lcc, 
	'lastcmd' => date('D M d H:i s\s', $lcc), 					//FIX for timezone stuff in 5.3+  	
	'servlink' => htmlentities(BASEURL.'index.php?type=services&state_filter='),
	'hostlink' => htmlentities(BASEURL.'index.php?type=hosts&state_filter='),
	
	//host counts 
	'hostsTotal' =>  ($hoststates['UP'] + $hoststates['DOWN'] + $hoststates['UNREACHABLE'] +$hoststates['PENDING']),
	'hostsUpTotal' => $hoststates['UP'],
	'hostsDownTotal' => $hoststates['DOWN'],
	'hostsUnreachableTotal' => $hoststates['UNREACHABLE'],
	'hostsProblemsTotal' => ($hoststates['DOWN']+$hoststates['UNREACHABLE']),
	'hostsUnhandledTotal' => 0,
	'hostsAcknowledgedTotal' => 0,  
		
	//service counts 
	'servicesOkTotal' => $servicestates['OK'],
	'servicesWarningTotal' => $servicestates['WARNING'],
	'servicesCriticalTotal' => $servicestates['CRITICAL'],
	'servicesUnknownTotal' => $servicestates['UNKNOWN'],
	'servicesProblemsTotal' => ($servicestates['CRITICAL']+$servicestates['UNKNOWN']+$servicestates['WARNING']),
	'servicesTotal' => ($servicestates['CRITICAL']+$servicestates['UNKNOWN']+$servicestates['WARNING']+$servicestates['OK']), //add pending later 
	
	//monitoring features 
	'flap_detection' => $program['enable_flap_detection'],
	'notifications' => $program['enable_notifications'],
	'active_service_checks' =>$program['active_service_checks_enabled'],
	'passive_service_checks' =>$program['passive_service_checks_enabled'],
	'event_handlers' => $program['enable_event_handlers'],
	
	//sentry host counters for loops 
	'hostsDownAcknowledged' => 0,
	'hostsDownUnhandled' => 0,
	'hostsUnreachableAcknowledged' => 0,
	'hostsUnreachableUnhandled' => 0,
	'hostsUnreachableScheduled' => 0, 
	'hostsUpDisabled' => 0,
	'hostsDownDisabled' => 0,
	'hostsDownScheduled' => 0, 
	'hostsUnreachableDisabled' => 0,
	'hostsPending' => 0, 
	'hostsPendingDisabled' => 0, 
	//NEW 
	'hostsFlappingDisabled' => 0,
	'hostsFlapping' =>0,
	'hostsNotificationsDisabled' => 0,
	'hostsEventHandlerDisabled' => 0,
	'hostsActiveChecksDisabled' =>0,
	'hostsPassiveChecksDisabled' =>0,
	
	//service totals 	
	'servicesWarningAcknowledged' => 0,
	'servicesWarningUnhandled' => 0,
	'servicesCriticalAcknowledged' => 0,
	'servicesCriticalUnhandled' => 0,
	'servicesUnknownAcknowledged' => 0,
	'servicesUnknownUnhandled' => 0,
	'servicesOkDisabled' => 0, 
	'servicesWarningDisabled' => 0,
	'servicesCriticalDisabled' => 0,
	'servicesUnknownDisabled' => 0,
	'servicesTotalDisabled' => 0,
	'servicesPending' => 0,
	'servicesPendingDisabled' => 0, 
	'servicesWarningHostProblem' => 0, 
	'servicesUnknownHostProblem' => 0, 
	'servicesCriticalHostProblem' => 0, 
	'servicesWarningScheduled' => 0, 
	'servicesUnknownScheduled' => 0,	
	'servicesCriticalScheduled' => 0,
	
	//NEW
	'servicesFlappingDisabled' => 0,
	'servicesFlapping' =>0,
	'servicesNotificationsDisabled' => 0,
	'servicesEventHandlerDisabled' => 0,
	'servicesActiveChecksDisabled' =>0,
	'servicesPassiveChecksDisabled' =>0,
	
	//html output items 
	'hostsFdHtml' => 'All Hosts Enabled', 'servicesFdHtml' => 'All Services Enabled', 
	'hostsFlapHtml' => 'No Hosts Flapping', 'servicesFlapHtml' => 'No Servies Flapping',
	'hostsNtfHtml' => 'All Hosts Enabled', 'servicesNtfHtml' => 'All Services Enabled', 
	'hostsAcHtml' => 'All Hosts Enabled', 'servicesAcHtml' => 'All Services Enabled', 
	'hostsPcHtml' => 'All Hosts Enabled', 'servicesPcHtml' => 'All Services Enabled', 
	'hostsEhHtml' => 'All Hosts Enabled', 'servicesEhHtml' => 'All Services Enabled', 
	);//end array 
	
	
	$hostStates = array(NULL, 'Down', 'Unreachable'); // used in tracking host states
	foreach($hosts as $h)
	{
		if(!isset($h['host_name']) || !$NagiosUser->is_authorized_for_host($h['host_name'])) continue; 	//user-level filtering 
	
		//html specific data 		
		if($h['flap_detection_enabled'] != 1) $tac_data['hostsFlappingDisabled']++;
		if($h['is_flapping'] == 1) $tac_data['hostsFlapping']++;
		if($h['notifications_enabled'] == 0) $tac_data['hostsNotificationsDisabled']++;
		if($h['event_handler_enabled'] == 0) $tac_data['hostsEventHandlerDisabled']++;	
		if($h['active_checks_enabled'] == 0) $tac_data['hostsActiveChecksDisabled']++;
		if($h['passive_checks_enabled'] == 0) $tac_data['hostsPassiveChecksDisabled']++;
		
		//xml necessary for Nagios Fusion 
		if($h['last_check']==0 && $h['active_checks_enabled'] == 1)  { $tac_data['hostsPending']++;  continue; } //skip ahead for pending 
		if($h['last_check'] == 0 && $h['active_checks_enabled'] == 0)  { $tac_data['hostsPendingDisabled']++; $tac_data['hostsPending']++;  continue; } //pending 

		switch($h['current_state']) {
			case 0:
				if($h['active_checks_enabled'] == 0) $tac_data['hostsUpDisabled']++;
			break;

			case 1:
			case 2:
				$hostClass = 'hosts'.$hostStates[$h['current_state']];

				if($h['active_checks_enabled'] == 0)         $tac_data[$hostClass.'Disabled']++;
				if($h['problem_has_been_acknowledged'] > 0)  $tac_data[$hostClass.'Acknowledged']++;
				if($h['problem_has_been_acknowledged'] == 0) $tac_data[$hostClass.'Unhandled']++;
				if($h['scheduled_downtime_depth'] > 0)       $tac_data[$hostClass.'Scheduled']++;
			break;

			default: /* do nothing */ break;
		}

	} 


	$serviceStates = array(NULL, 'Warning', 'Critical', 'Unknown'); // used in tracking service states
	foreach($services as $s)
	{
		if(!$NagiosUser->is_authorized_for_service($s['host_name'],$s['service_description'])) continue; 		
		
		//html specific data 		
		if($s['flap_detection_enabled'] != 1) $tac_data['servicesFlappingDisabled']++;
		if($s['is_flapping'] == 1)            $tac_data['servicesFlapping']++;
		if($s['notifications_enabled'] == 0)  $tac_data['servicesNotificationsDisabled']++;
		if($s['event_handler_enabled'] == 0)  $tac_data['servicesEventHandlerDisabled']++;
		if($s['active_checks_enabled'] == 0)  $tac_data['servicesActiveChecksDisabled']++;
		if($s['passive_checks_enabled'] == 0) $tac_data['servicesPassiveChecksDisabled']++;
	
		//xml necessary for Nagios Fusion 
		//$current_host = $h_states[$s['host_name']];	
		$current_host = $hosts[$s['host_name']];
		if($s['last_check'] == 0 && $s['active_checks_enabled'] == 1) { $tac_data['servicesPending']++; continue; } //pending 
		if($s['last_check'] == 0 && $s['active_checks_enabled'] == 0) { $tac_data['servicesPendingDisabled']++;  continue;  } //pending 
		if($s['active_checks_enabled'] == 0) $tac_data['servicesTotalDisabled']++;

		switch($s['current_state']) {
			case 0:
				$tac_data['servicesOkDisabled']++;
			break;

			case 1:
			case 2:
			case 3:
				$serviceClass = 'services'.$serviceStates[$s['current_state']];

				if($s['active_checks_enabled'] == 0)         $tac_data[$serviceClass.'Disabled']++;
				if($s['problem_has_been_acknowledged'] > 0)  $tac_data[$serviceClass.'Acknowledged']++;
				if($s['problem_has_been_acknowledged'] == 0) $tac_data[$serviceClass.'Unhandled']++;
				if($s['scheduled_downtime_depth'] > 0)       $tac_data[$serviceClass.'Scheduled']++;
				if($current_host['current_state'] > 0)       $tac_data[$serviceClass.'HostProblem']++;
			break;

			default: /* do nothing */ break;
		}
	}
	
	//process HTML variables for monitoring features table 
	if($tac_data['hostsFlappingDisabled']    > 0) $tac_data['hostsFdHtml']      = '<span class="red">'.$tac_data['hostsFlappingDisabled'].' Hosts disabled</span>';
	if($tac_data['servicesFlappingDisabled'] > 0) $tac_data['servicesFdHtml']   = '<span class="red">'.$tac_data['servicesFlappingDisabled'].' Services disabled</span>';
	if($tac_data['hostsFlapping']            > 0) $tac_data['hostsFlapHtml']    = '<span class="red">'.$tac_data['hostsFlapping'].' Hosts disabled</span>';
	if($tac_data['servicesFlapping']         > 0) $tac_data['servicesFlapHtml'] = '<span class="red">'.$tac_data['servicesFlapping'].' Services disabled</span>';
	if($tac_data['hostsNotificationsDisabled']    > 0) $tac_data['hostsNtfHtml']    = '<span class="red">'.$tac_data['hostsNotificationsDisabled'].' Hosts disabled</span>';
	if($tac_data['servicesNotificationsDisabled'] > 0) $tac_data['servicesNtfHtml'] = '<span class="red">'.$tac_data['servicesNotificationsDisabled'].' Services disabled</span>';
	if($tac_data['hostsEventHandlerDisabled']     > 0) $tac_data['hostsEhHtml']     = '<span class="red">'.$tac_data['hostsEventHandlerDisabled'].' Hosts disabled</span>';
	if($tac_data['servicesEventHandlerDisabled']  > 0) $tac_data['servicesEhHtml']  = '<span class="red">'.$tac_data['servicesEventHandlerDisabled'].' Services disabled</span>';
	if($tac_data['hostsActiveChecksDisabled']     > 0) $tac_data['hostsAcHtml']     = '<span class="red">'.$tac_data['hostsActiveChecksDisabled'].' Hosts disabled</span>';
	if($tac_data['servicesActiveChecksDisabled']  > 0) $tac_data['servicesAcHtml']  = '<span class="red">'.$tac_data['servicesActiveChecksDisabled'].' Services disabled</span>';
	if($tac_data['hostsPassiveChecksDisabled']    > 0) $tac_data['hostsPcHtml']     = '<span class="red">'.$tac_data['hostsPassiveChecksDisabled'].' Hosts disabled</span>';
	if($tac_data['servicesPassiveChecksDisabled'] > 0) $tac_data['servicesPcHtml']  = '<span class="red">'.$tac_data['servicesPassiveChecksDisabled'].' Services disabled</span>';

	//other misc totals 
	$tac_data['hostsUnhandledTotal']       = $tac_data['hostsDownUnhandled']         +$tac_data['hostsUnreachableUnhandled']; 
	$tac_data['hostsAcknowledgedTotal']    = $tac_data['hostsDownAcknowledged']      +$tac_data['hostsUnreachableAcknowledged']; 
	$tac_data['servicesUnhandledTotal']    = $tac_data['servicesWarningUnhandled']   +$tac_data['servicesUnknownUnhandled']    +$tac_data['servicesCriticalUnhandled']; 
	$tac_data['servicesAcknowledgedTotal'] = $tac_data['servicesWarningAcknowledged']+$tac_data['servicesUnknownAcknowledged'] +$tac_data['servicesCriticalAcknowledged'];
	$tac_data['servicesPendingTotal'] = $tac_data['servicesPending'] + $tac_data['servicesPendingDisabled']; 
	
	return $tac_data; 
} //end get_tac_data() 

?>
