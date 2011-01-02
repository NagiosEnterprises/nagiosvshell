<?php  //servicegroups.php    //display page for service groups 


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

function get_servicegroup_data()
{
	$servicegroup_data = array();

	$sg_details = build_servicegroups_array();
	foreach($sg_details as $group => $members)
	{
		$servicegroup_data[$group]['state_counts'] = array();
		foreach(array('OK', 'WARNING', 'CRITICAL', 'UNKNOWN') as $state)
		{
			$servicegroup_data[$group]['state_counts'][$state] = count_by_state($state, $members);
		}
		$servicegroup_data[$group]['services'] = array();
		foreach ($members as $serv) {
			$service_data = array(
				'host_name'   => $serv['host_name'],
				'host_url'    => htmlentities(BASEURL.'index.php?mode=filter&type=hostdetail&arg='.$serv['host_name']),
				'service_url' => htmlentities(BASEURL.'index.php?mode=filter&type=servicedetail&arg='.$serv['serviceID']),
				'plugin_output' => $serv['plugin_output'],
				'description'   => $serv['service_description'],
				'current_state' => $serv['current_state'],
			);
			$servicegroup_data[$group]['services'][] = $service_data;
		}
	}

	return $servicegroup_data;
}

function display_servicegroups($data)
{

	$page = "<h3>Service Groups</h3>";
	
	//////////////////////////////////////table creation, displays both summary and grid view   
	foreach($data as $group => $group_data)
	{
		//create two joined tables, one summary, one grid
		
		$page .= "<h5>$group</h5>";
		//summary table 
		$page .= "<table class='statustable'><tr>
					<th>Ok</th><th>Critical</th><th>Warning</th><th>Unknown</th></tr>
					<tr>
						<td class='ok'>{$group_data['state_counts']['OK']}</td>
						<td class='critical'>{$group_data['state_counts']['CRITICAL']}</td>
						<td class='warning'>{$group_data['state_counts']['WARNING']}</td>
						<td class='unknown'>{$group_data['state_counts']['UNKNOWN']}</td>
					</tr>
				</table>";
		
		$page .= "<p class='label'><a onclick=\"showHide('$group')\" href='javascript:void(0)'>Toggle Grid</a></p>";
		//grid table 
		
		$page .= "<div class='hidden' id='$group'>
				<table class='statustable'>
				<tr><th>Host</th><th>Status</th><th>Service</th><th>Status Information</th></tr>";
				
		#foreach($members as $serv)
		foreach($group_data['services'] as $serv)
		{		//add a hoststatus element to service arrays 
		
			$page .= "<tr>
						<td><a href='{$serv['host_url']}'>".$serv['host_name']."</a></td>
						<td class='".strtolower($serv['current_state'])."'>".$serv['current_state']."</td>
						<td><a href='{$serv['service_url']}'>".$serv['description']."</a></td>
						<td>".$serv['plugin_output']."</td>
					</tr>";
				
		}
		$page .= '</table></div>'; 
	}

	return $page;
}

?>
