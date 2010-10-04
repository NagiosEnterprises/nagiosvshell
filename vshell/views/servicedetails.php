<?php //service details view page


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





//this page will be an include for the command_router.php controller file 
//include additional items by first adding them to the arrays on command_router.php 

//defining string using HEREDOC syntax, which is a format for multiline 
//   print statements with variables 
//  
// 
$page=<<<PAGE

	<h3>Service Status Detail</h3>
	<div class="detailcontainer">
	<h4><em>Service: </em>{$dets['Service']}</h4>
	<h4><em>Host: </em>{$dets['Host']}</h4>
	<h5><em>Member of: </em>{$dets['MemberOf']}</h5>
	<fieldset class="servicedetails">
	<legend>Advanced Details</legend>
	<table class="details">
		<tr><td>Service State</td><td class="{$dets['State']}">{$dets['State']}</td></tr>
		<tr><td>Check Command</td><td>{$dets['CheckCommand']}</td></tr>
		<tr><td>Plugin Output</td><td>{$dets['Output']}</td></tr>
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
		<tr><td>Performance Data</td><td>{$dets['PerformanceData']}</td></tr>
		
	</table>	
	
	</fieldset>
	
	
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

	<tr><td><a href="{$dets['CmdCustomNotification']}" title="Send Custom Notification"><img src="views/images/notification.gif" class="iconLink" height="12" width="12" alt="Notification" /></a></td><td>Send custom notification</td></tr>
	<tr><td><a href="{$dets['CmdScheduleDowntime']}" title="Schedule Downtime"><img src="views/images/downtime.png" class="iconLink" height="12" width="12" alt="Downtime" /></a></td><td>Schedule downtime</td></tr>
	<tr><td><a href="{$dets['CmdScheduleChecks']}" title="Schedule Check"><img src="views/images/schedulecheck.png" class="iconLink" height="12" width="12" alt="Schedule" /></a></td><td>Reschedule Next Check</td></tr>
	<tr><td><a href="{$dets['CmdAcknowledge']}" title="{$dets['AckTitle']}"><img src="views/images/ack.png" class="iconLink" height="12" width="12" alt="Acknowledge" /></a></td><td>{$dets['AckTitle']}</td></tr>
	<tr><td colspan="2"><a class="label" href="{$dets['CoreLink']}" title="See This Service In Nagios Core">See This Service In Nagios Core</a></td></tr>
	</table>
	</fieldset>
	
	</div>
	


	<!-- begin comment table -->
	<div>
	<h5 class="commentTable">Comments</h5>
	<p class="commentTable"><a class="label" href="{$dets['AddComment']}" title="Add Comment">Add Comment</a></p> 
	
	<table class="commentTable"><tr><th>Author</th><th>Entry Time</th><th>Comment</th><th>Actions</th></tr>
	


PAGE;

print $page;

	//print service comments in table rows if any exist
	//see display_functions.php for function  
	get_service_comments($dets['Host'], $dets['Service']);
	//close comment table 
	print '</table></div><br />';



?> 