<?php  //hostgroups.php    display page for hostgroup details 

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


print "<h3>Host Groups</h3>";

global $NagiosData;
$hostgroups = $NagiosData->getProperty('hostgroups');
$hosts = $NagiosData->getProperty('hosts');
$services = $NagiosData->getProperty('services');


// before optimization: 15.9s
// hosts optimization: 13.4s, 11.9s
// hosts+services optimization: 7.5s

$start_time = microtime(TRUE);

//foreach($hostgroups as $group => $members) {
//	print "<a href=\"#$group\">$group</a>\n";
//}

//////////////////////////////////////table creation, displays both summary and grid view   
foreach($hostgroups as $group=>$members)
{

	//group label
	print "<h5><a name=\"$group\">$group</a></h5>"; 
	
	//status summaries for hosts 
	$hg_details = build_hostgroup_details($members);	
	$host_counts = get_state_of('hosts', $hg_details);
	
	//status summaries for services within hostgroups 
	$sg_details = build_host_servicegroup_details($members);
	$service_counts = get_state_of('services', $sg_details);
	
	//print table data for group summary 
	print "<table class='statusable'><tr>
			<th></th><th>Up</th><th>Down</th><th colspan='2'>Unreachable</th></tr>
			<tr><td>Hosts</td>
				<td class='ok'> {$host_counts['UP']} </td>
				<td class='down'> {$host_counts['DOWN']} </td>
				<td class='unreachable' colspan='2'> {$host_counts['UNREACHABLE']} </td>
			</tr>
			
			<tr><th></th><th>Ok</th><th>Warning</th><th>Critical</th><th>Unknown</th></tr>			
			<tr>
				<td>Services</td>		 	
				<td class='ok'> {$service_counts['OK']} </td>
				<td class='warning'> {$service_counts['WARNING']} </td>
				<td class='critical'> {$service_counts['CRITICAL']} </td>
				<td class='unknown'> {$service_counts['UNKNOWN']} </td>
			</tr></table>";
	
	//TODO: javascript link to expand into grid view, pass $group as div ID  	
	print "<p class='label'><a onclick=\"showHide('$group')\" href='javascript:void(0)'>Toggle Grid</a></p>";
	//print table data for individual hosts. 
	
	//details table in GRID view 
	print "<div class='hidden' id='$group'>";
	print "<table class='statustable'><tr><th>$group</th><th>Host Status</th><th>Services</th></tr>";			
	foreach($members as $member)
	{
		print "<tr>\n";
		$member = trim($member); //trim whitespace 

		//pull group member data from global $hosts array
		$host = $hosts[$member];
		$host_url = htmlentities(BASEURL.'index.php?cmd=gethostdetail&arg='.$host['host_name']);
		$tr = get_color_code($host);
		print "<td><a href='$host_url'>".$host['host_name']."</a></td><td class='$tr'>".$host['current_state'].'</td>';
		print "<td>\n";
		
		//pull group member data from global $services array 
		if (isset($host['services'])) {
			foreach($host['services'] as $service)
			{
				$service_url = htmlentities(BASEURL.'index.php?cmd=getservicedetail&arg='.$service['serviceID']);				
				$tr = get_color_code($service);
				print " <span class='$tr'><a href='$service_url'>".$service['service_description']."</a></span>&nbsp; ";				
			}	
		}
		print "</td></tr>";		
	}	
	print "</table></div><br />\n";
	
}


$end_time = microtime(TRUE);
fb($end_time - $start_time, "Elapsed time in hostgroups");
?>
