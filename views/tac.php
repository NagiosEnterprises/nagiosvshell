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




//TODO move each table into it's own function to prevent variable overlap 




?>
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


<table class="tac">
<tr><th>Tactical Monitoring Overview</th></tr>
	<tr>
		<td>
			<?php 
			
			$last_command = settype($info['last_command_check'], 'integer') ;	
			$now = time();
			$last_command = $now - $last_command;						 
			$lastcmd = date('D M d H:i s\s', $last_command);
			//Fri Sep 3 13:42:55 CDT 2010
			print 'Last Check: '.$lastcmd."<br />\n"; 				 				 			
			print "Updated every 90 seconds<br />\n";
			print 'Nagios® Core™ '.$info['version'].' - www.nagios.org<br />';
			global $username;
			print 'Logged in as '.$username.'<br />';

						
			?>
		</td>
	</tr>
</table>
<br />



<!-- ########################HOSTS TABLE########################## -->
<table class="tac">
<tr><th>Hosts</th></tr>
<?php

$states = get_state_of('hosts');
$hlink = htmlentities(BASEURL.'index.php?cmd=filterhosts&arg=');
//using HEREDOC string syntax 
$hostrow = <<<HOSTROW

<tr>
	<td class="ok"><a href="{$hlink}UP">{$states['UP']}</a> Up</td>
	<td class="down"><a href="{$hlink}DOWN">{$states['DOWN']}</a> Down</td>
	<td class="unreachable"><a href="{$hlink}UNREACHABLE">{$states['UNREACHABLE']}</a> Unreachable</td>				
</tr> 

HOSTROW;
print $hostrow;
?>

</table>
<br />
<!-- ######################SERVICES TABLE##################### -->
<table class="tac">
<tr><th>Services</th></tr>
<?php

$sts = get_state_of('services');
$link = htmlentities(BASEURL.'index.php?cmd=filterservices&arg=');
//using HEREDOC syntax to print table rows 
$row = <<<TABLEROW

  <tr>
	   <td class='ok'><a href='{$link}OK'>{$sts['OK']}</a> Ok</td>
	   <td class="critical"><a href="{$link}CRITICAL">{$sts['CRITICAL']}</a> Critical</td>
		<td class="warning"><a href="{$link}WARNING">{$sts['WARNING']}</a> Warning</td>		
		<td class="unknown"><a href="{$link}UNKNOWN">{$sts['UNKNOWN']}</a> Unknown</td>
  </tr>
  
TABLEROW;
//end HEREDOC 
print $row;

?>

</table>

<!-- #####################ENABLED FEATURES TABLE ####################-->
<br />
<table class="tac">
<tr><th>Monitoring Features</th></tr>
<tr>
	<td>Flap Detection</td><td>Notifications</td><td>Event Handlers</td>
	<td>Active Checks</td><td>Passive Checks</td>
</tr><tr>	
<?php 

// monitoring features enabled/disabled 

///////////////////////FLAPPING////////////////////////////////
print '<td class="green">'."\n";
//////////////////////////////////////////////////////////////////
//checking for disabled flap detection 

$host_fd = check_boolean('host', 'flap_detection_enabled', 0); 
if($host_fd)
{
	print '<span class="red">'.$host_fd.' Hosts disabled</span>';
}
else
{
	print 'All Hosts enabled';
}
print '<br />';	
$service_fd = check_boolean('service', 'flap_detection_enabled', 0); 
if($service_fd)
{
	print '<span class="red">'.$host_fd.' Hosts disabled</span>';
}
else
{
	print 'All Services enabled';
}	
print "<br />\n";
///////////////////////////////////////////////////////////
//checking for flapping... 
$host_flap = check_boolean('host', 'is_flapping', 1); 
if($host_flap)
{
	print '<span class="red">'.$host_flap.' Hosts flapping';
}
else
{
	print 'No hosts flapping';
}	
print "<br />\n";
$service_flap = check_boolean('service', 'is_flapping', 1); 
if($service_flap)
{
	print '<span class="red">'.$service_flap.' Services flapping</span>';
}
else
{
	print 'No Services flapping';
}	
print "<br />\n";
print '</td>'; //close table data 

/////////////////////////////NOTIFICATIONS///////////////////////////////
print '<td class="green">';
//////////////////////////////////////////////////////////////////
//checking for notifications 

$host_ntf = check_boolean('host', 'notifications_enabled', 0); 
if($host_ntf)
{
	print '<span class="red">'.$host_ntf.' Hosts disabled</span>';
}
else
{
	print 'All Hosts enabled';
}
print "<br />\n";	
$service_ntf = check_boolean('service', 'notifications_enabled', 0); 
if($service_ntf)
{
	print '<span class="red">'.$service_ntf.' Services disabled</span>';
}
else
{
	print 'All Services enabled';
}	
print "<br /></td>\n"; //close table data 

///////////////////////////////EVENT HANDLERS/////////////////////////////
print '<td class="green">';
//////////////////////////////////////////////////////////////////
//checking for event handlers  

$host_eh = check_boolean('host', 'event_handler_enabled', 0); 
if($host_ntf)
{
	print '<span class="red">'.$host_eh.' Hosts disabled</span>';
}
else
{
	print 'All Hosts enabled';
}
print "<br />\n";	
$service_eh = check_boolean('service', 'event_handler_enabled', 0); 
if($service_eh)
{
	print '<span class="red">'.$service_eh.' Services disabled</span>';
}
else
{
	print 'All Services enabled';
}	
print "<br /></td>\n"; //close table data 

/////////////////////////////////ACTIVE CHECKS///////////////////////////	
print '<td class="green">';
//////////////////////////////////////////////////////////////////
//checking for active checks  

$host_ac = check_boolean('host', 'active_checks_enabled', 0); 
if($host_ac)
{
	print '<span class="red">'.$host_ac.' Hosts disabled</span>';
}
else
{
	print 'All Hosts enabled';
}
print "<br />\n";	
$service_ac = check_boolean('service', 'active_checks_enabled', 0); 
if($service_ac)
{
	print '<span class="red">'.$service_ac.' Services disabled</span>';
}
else
{
	print 'All Services enabled';
}	
print "<br /></td>\n"; //close table data 
	
	
/////////////////////////////////PASSIVE CHECKS///////////////////////////	
print '<td class="green">';
//////////////////////////////////////////////////////////////////
//checking for passive checks  

$host_pc = check_boolean('host', 'passive_checks_enabled', 0); 
if($host_pc)
{
	print '<span class="red">'.$host_pc.' Hosts disabled</span>';
}
else
{
	print 'All Hosts enabled';
}
print "<br />\n";	
$service_pc = check_boolean('service', 'passive_checks_enabled', 0); 
if($service_pc)
{
	print '<span class="red">'.$service_pc.' Services disabled</span>';
}
else
{
	print 'All Services enabled';
}	
print "<br /></td>\n"; //close table data 	

?>
</tr>
</table>
<br />















