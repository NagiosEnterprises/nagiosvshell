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
	global $tac_data; 

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
	global $tac_data; 
	global $username; 

	$overview_table = <<<OVERVIEWTABLE
<table class="tac">
<tr><th>Tactical Monitoring Overview</th></tr>
	<tr>
		<td>
			Last Check: {$tac_data['lastcmd']}<br />
			Updated every 90 seconds<br />
			Nagios® Core™ {$tac_data['version']} - www.nagios.org<br />
			Logged in as $username<br />
		</td>
	</tr>
</table>
OVERVIEWTABLE;
	return $overview_table;
}


function hosts_table()
{
	global $tac_data; 

	$hosts_table =<<<HOSTSTABLE
<!-- ########################HOSTS TABLE########################## -->
<table class="tac">
<tr><th>Hosts</th></tr>
<tr>
	<td class="ok"><a href="{$tac_data['hostlink']}UP">{$tac_data['hostsUpTotal']}</a> Up</td>
	<td class="down"><a href="{$tac_data['hostlink']}DOWN">{$tac_data['hostsDownTotal']}</a> Down</td>
	<td class="unreachable"><a href="{$tac_data['hostlink']}UNREACHABLE">{$tac_data['hostsUnreachableTotal']}</a> Unreachable</td>
	<td class="pending">{$tac_data['hostsPending']} Pending</td>				
	
</tr> 
<tr>
	<td class="problem">{$tac_data['hostsProblemsTotal']} Problems</td>
	<td class="unhandled">{$tac_data['hostsUnhandledTotal']} Unhandled</td>
	<td class="acknowledged">{$tac_data['hostsAcknowledgedTotal']} Acknowledged</td>
	<td><a href="index.php?type=hosts" title="All Hosts">{$tac_data['hostsTotal']}</a> Total </td>
</tr>

</table>
HOSTSTABLE;
	return $hosts_table;
}

function services_table()
{
	global $tac_data; 

	$services_table = <<<SERVICESTABLE
<!-- ######################SERVICES TABLE##################### -->
<table class="tac">
<tr><th>Services</th></tr>
	
  <tr>
	   <td class='ok'><a href='{$tac_data['servlink']}OK'>{$tac_data['servicesOkTotal']}</a> Ok</td>
	   <td class="critical singleLine"><a href="{$tac_data['servlink']}CRITICAL">{$tac_data['servicesCriticalTotal']}</a> Critical</td>
		<td class="warning singleLine"><a href="{$tac_data['servlink']}WARNING">{$tac_data['servicesWarningTotal']}</a> Warning</td>		
		<td class="unknown singleLine"><a href="{$tac_data['servlink']}UNKNOWN">{$tac_data['servicesUnknownTotal']}</a> Unknown</td>
		<td class="pending singleLine">{$tac_data['servicesPending']} Pending</td>
  </tr>
  <tr>
	<td class="problem">{$tac_data['servicesProblemsTotal']} Problems</td>
	<td class="unhandled"><div class="singleLine">{$tac_data['servicesUnhandledTotal']} Unhandled</div></td>
	<td class="acknowledged"><div class="singleLine">{$tac_data['servicesAcknowledgedTotal']} Acknowledged</div></td>
	<td colspan="2"><a href="index.php?type=services" title="All Services">{$tac_data['servicesTotal']}</a> Total </td>
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
	global $tac_data; 

	$features_table = <<<FEATURESTABLE
<!-- #####################ENABLED FEATURES TABLE ####################-->
<table class="tac">
<tr><th>Monitoring Features</th></tr>
<tr>
	<td>Flap Detection</td><td>Notifications</td><td>Event Handlers</td>
	<td>Active Checks</td><td>Passive Checks</td>
</tr><tr>	

<!-- ///////////////////////FLAPPING//////////////////////////////// -->
	<td class="green">
		{$tac_data['hostsFdHtml']}<br />
		 {$tac_data['servicesFdHtml']}<br />
		{$tac_data['hostsFlapHtml']}<br />
		 {$tac_data['servicesFlapHtml']}<br />							 									
	</td>
	
	<!-- /////////////////////////////NOTIFICATIONS/////////////////////////////// -->
	<td class="green">
		{$tac_data['hostsNtfHtml']}<br />
		 {$tac_data['servicesNtfHtml']}<br />	
	</td>
	
	<!-- ///////////////////////////////EVENT HANDLERS///////////////////////////// -->
	<td class="green">
		{$tac_data['hostsEhHtml']}<br />
		 {$tac_data['servicesEhHtml']}<br />	
	</td>
	
	<!-- /////////////////////////////////ACTIVE/PASSIVE CHECKS///////////////////////////	-->
	<td class="green">
		{$tac_data['hostsAcHtml']}<br />
		 {$tac_data['servicesAcHtml']}<br />			
	</td>

	<td class="green">
		{$tac_data['hostsPcHtml']}<br />
		 {$tac_data['servicesPcHtml']}<br />			
	</td>


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