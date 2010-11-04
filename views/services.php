<?php //services.php      //display page for service details


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



//expecting array of service status and returns a service table  
function display_services($services)
{
	//Table header generation 
	print '
	<table class="servicetable"><tr> 
	<th class="hostname">Host Name</th>
	<th class="service_description">Service</th>
	<th class="status">Status</th>
	<th class="duration">Duration</th>
	<th class="attempt">Attempt</th>
	<th class="last_check">Last Check</th>
	<th class="plugin_output">Status Information</th></tr>';


	//process service array   
	//service table rows 
	$count = 0;
	foreach($services as $service)
	{
		$count++; 
		$tr = get_color_code($service);
		$url = htmlentities(BASEURL.'index.php?cmd=getservicedetail&arg='.$service['serviceID']);
		$host_url = htmlentities(BASEURL.'index.php?cmd=gethostdetail&arg='.$service['host_name']);
		$color = get_host_status_color($service['host_name']);
		$icon = return_icon_link($service['host_name']);
		$comments = comment_icon($service['host_name'], $service['service_description']);
		$h_comments = comment_icon($service['host_name']);
		$dt_icon = downtime_icon($service['scheduled_downtime_depth']);
		$host_dt = downtime_icon(get_host_downtime($service['host_name']) );
		//removing duplicate host names from table for a cleaner look
		if(isset($_GET['view']))
		{	 
			if(isset($services[($count-1)]['host_name']) && $services[($count-1)]['host_name'] == $service['host_name'])
			{
				$td1 = '<td></td>';		
			}
			else
			{
				$hostlink = "<a href='$host_url' title='View Host Details'>";
				$td1 = "<td class='$color'><div class='hostname'>$hostlink".$service['host_name']."</a> $icon $host_dt $h_comments</div></td>";
			}
		}
		else
		{
			$hostlink = "<a href='$host_url' title='View Host Details'>";
			$td1 = "<td class='$color'><div class='hostname'>$hostlink".$service['host_name']."</a> $icon $host_dt $h_comments</div></td>";
		}
		
		//table data generation 				
		//Using HEREDOC string syntax to print rows 
		$tablerow = <<<TABLE
		
		<tr class='statustablerow'>	
			{$td1}
			<td class='service_description'><div class='service_description'><a href="{$url}">{$service['service_description']}</a>{$comments}{$dt_icon}</div></td>
			<td class="{$tr}">{$service['current_state']}</td>
			<td class='duration'>{$service['duration']}</td>
			<td class='attempt'>{$service['attempt']}</td>
			<td class='last_check'>{$service['last_check']}</td>
			<td class='plugin_output'><div class='plugin_output'>{$service['plugin_output']}</div></td>
		</tr>
		
TABLE;

		print $tablerow;
		
	}
	print '</table>';
}
//end php 
?>











