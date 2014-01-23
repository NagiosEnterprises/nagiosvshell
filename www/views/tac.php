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


function get_tac_html()
{
	$tac_data = get_tac_data();

	$tac = "";
	$tac .= info_table().'<br />';
	$tac .= overview_table($tac_data).'<br />';
	$tac .= health_meters($tac_data); //added health meters -MG 4/23/11
	$tac .= hosts_table($tac_data).'<br />';
	$tac .= services_table($tac_data).'<br />';
	$tac .= features_table($tac_data);
	$tac .= search_box().'<br />';

	return $tac;
}

function info_table()
{
	$version = 'v'.VERSION;
	$info_table ="
<!-- ##################Nagios Info Table################### -->
<div id='infodiv'>

<p class='note'>Nagios V-Shell $version<br />
	Copyright (c) 2010-2012 <br />
	Nagios Enterprises, LLC. <br />
   ".gettext('Written by')." Mike Guthrie<br />
   ".gettext("For questions, feedback, <br /> or support, visit the")." <br />
   <a href='http://support.nagios.com/forum/viewforum.php?f=19' target='_blank'>V-Shell ".gettext('Forum')."</a>.</p>

</div>";
	return $info_table;
}

function overview_table($tac_data)
{
	global $NagiosUser; 
	$overview_table = "
<table class='tac'>
<tr><th>".gettext('Tactical Monitoring Overview')."</th></tr>
	<tr>
		<td>
			".gettext('Last Check').": {$tac_data['lastcmd']}<br />
			 Nagios® Core™ {$tac_data['version']} - www.nagios.org<br />
			".gettext('Logged in as')." ".$NagiosUser->get_username()."<br />
		</td>
	</tr>
</table>
";
	return $overview_table;
}



//builds health meter divs for hosts and services 
function health_meters($tac_data)
{
		$tac  = "<div id='meterContainer'>";
		$tac .= health_meter($tac_data, 'host'); //added meter 
		$tac .= health_meter($tac_data, 'service');
		$tac .= "</div> <!-- end health meters --> \n";
		return $tac; 
}

//generic health meter function, calculates health and returns divs 
function health_meter($tac_data, $type)
{
	$Total = $tac_data[$type.'sTotal'];
	$problems = $tac_data[$type.'sProblemsTotal'];
	$health = (floatval($Total-$problems) / floatval($Total)) *100; 
	//$health = 30;
	$title = ucfirst($type); 
	$meter =  "<div class='h_container'>".ucfirst($type)." ".gettext('Health').": ".round($health,2)."% <br />\n
					<div class='borderDiv'>
					<div class='healthmeter' style='background: rgb(".color_code(round($health,0))."); width: ".$health."%;'>\n
				</div></div></div>\n"; 
	return $meter;
}


function hosts_table($tac_data)
{
	$hosts_table ="
<!-- ########################HOSTS TABLE########################## -->
<table class='tac'>
<tr><th>".gettext('Hosts')."</th></tr>
<tr>
	<td class='ok'><a class='highlight' href='{$tac_data['hostlink']}UP'><div class='td'>{$tac_data['hostsUpTotal']} ".gettext('Up')."</div></a></td>
	<td class='down'><a class='highlight' href='{$tac_data['hostlink']}DOWN'><div class='td'>{$tac_data['hostsDownTotal']} ".gettext('Down')."</div></a></td>
	<td class='unreachable'><a class='highlight' href='{$tac_data['hostlink']}UNREACHABLE'><div class='td'>{$tac_data['hostsUnreachableTotal']} ".gettext('Unreachable')."</div></a></td>
	<td class='pending'><a class='highlight' href='{$tac_data['hostlink']}PENDING'><div class='td'>{$tac_data['hostsPending']} ".gettext('Pending')."</div></a></td>				
	
</tr> 
<tr>
	<td class='problem'><a class='highlight' href='{$tac_data['hostlink']}PROBLEMS'><div class='td'>{$tac_data['hostsProblemsTotal']} ".gettext('Problems')."</div></a></td>
	<td class='unhandled'><a class='highlight' href='{$tac_data['hostlink']}UNHANDLED'><div class='td'>{$tac_data['hostsUnhandledTotal']} ".gettext('Unhandled')."</div></a></td>
	<td class='acknowledged'><a class='highlight' href='{$tac_data['hostlink']}ACKNOWLEDGED'><div class='td'>{$tac_data['hostsAcknowledgedTotal']} ".gettext('Acknowledged')."</div></a></td>
	<td><div class='td'><a class='highlight' href='index.php?type=hosts' title='All Hosts'>{$tac_data['hostsTotal']} ".gettext('Total')."</div></a></td>
</tr>

</table>
";
	return $hosts_table;
}

function services_table($tac_data)
{
	$services_table = "
<!-- ######################SERVICES TABLE##################### -->
<table class='tac'>
<tr><th>".gettext('Services')."</th></tr>
	
  <tr>
	   <td class='ok'><a class='highlight' href='{$tac_data['servlink']}OK'><div class='td'>{$tac_data['servicesOkTotal']} ".gettext('Ok')."</div></a></td>
	   <td class='critical'><a class='highlight' href='{$tac_data['servlink']}CRITICAL'><div class='td'>{$tac_data['servicesCriticalTotal']} ".gettext('Critical')."</div></a></td>
		<td class='warning'><a class='highlight' href='{$tac_data['servlink']}WARNING'><div class='td'>{$tac_data['servicesWarningTotal']} ".gettext('Warning')."</div></a></td>		
		<td class='unknown'><a class='highlight' href='{$tac_data['servlink']}UNKNOWN'><div class='td'>{$tac_data['servicesUnknownTotal']} ".gettext('Unknown')."</div></a></td>
		<td class='pending'><a class='highlight' href='{$tac_data['servlink']}PENDING'><div class='td'>{$tac_data['servicesPendingTotal']} ".gettext('Pending')."</div></a></td>
  </tr>
  <tr>
	<td class='problem'><a class='highlight' href='{$tac_data['servlink']}PROBLEMS'><div class='td'>{$tac_data['servicesProblemsTotal']} ".gettext('Problems')."</div></a></td>
	<td class='unhandled'><a class='highlight' href='{$tac_data['servlink']}UNHANDLED'><div class='td'>{$tac_data['servicesUnhandledTotal']} ".gettext('Unhandled')."</div></a></td>
	<td class='acknowledged'><a class='highlight' href='{$tac_data['servlink']}ACKNOWLEDGED'>{$tac_data['servicesAcknowledgedTotal']} ".gettext('Acknowledged')."</a></td>
	<td colspan='2'><a class='highlight' href='index.php?type=services' title='All Services'><div id='td_servicestotal' class='td'>{$tac_data['servicesTotal']} ".gettext('Total')." </div></a></td>
</tr>

</table>
";
	return $services_table;
}

function search_box() {
	$box = "
<!-- #####################SEARCH BOX####################-->
<div class='resultFilter'>
	<form id='resultfilterform' action='{$_SERVER['PHP_SELF']}' method='get'>
		<input type='hidden' name='type' value='services'>
		<label class='label' for='name_filter'>".gettext('Search String')."</label>
		<input type='text' name='name_filter'></input>
		<input type='submit' name='submitbutton' value='".gettext('Filter')."' />
	</form>
</div>
";
	return $box;
}

function features_table($tac_data)
{
	$features_table = "
<!-- #####################ENABLED FEATURES TABLE ####################-->
<table class='tac'>
<tr><th>".gettext('Monitoring Features')."</th></tr>
<tr>
	<td>".gettext('Flap Detection')."</td><td>".gettext('Notifications')."</td><td>".gettext('Event Handlers')."</td>
	<td>".gettext('Active Checks')."</td><td>".gettext('Passive Checks')."</td>
</tr><tr>	

<!-- ///////////////////////FLAPPING//////////////////////////////// -->
	<td class='green'>
		{$tac_data['hostsFdHtml']}<br />
		 {$tac_data['servicesFdHtml']}<br />
		{$tac_data['hostsFlapHtml']}<br />
		 {$tac_data['servicesFlapHtml']}<br />							 									
	</td>
	
	<!-- /////////////////////////////NOTIFICATIONS/////////////////////////////// -->
	<td class='green'>
		{$tac_data['hostsNtfHtml']}<br />
		 {$tac_data['servicesNtfHtml']}<br />	
	</td>
	
	<!-- ///////////////////////////////EVENT HANDLERS///////////////////////////// -->
	<td class='green'>
		{$tac_data['hostsEhHtml']}<br />
		 {$tac_data['servicesEhHtml']}<br />	
	</td>
	
	<!-- /////////////////////////////////ACTIVE/PASSIVE CHECKS///////////////////////////	-->
	<td class='green'>
		{$tac_data['hostsAcHtml']}<br />
		 {$tac_data['servicesAcHtml']}<br />			
	</td>

	<td class='green'>
		{$tac_data['hostsPcHtml']}<br />
		 {$tac_data['servicesPcHtml']}<br />			
	</td>


</tr>
</table>
<br />
";
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
