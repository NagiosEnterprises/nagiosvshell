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















