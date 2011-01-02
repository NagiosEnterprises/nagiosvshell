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
function display_services($services,$start,$limit)
{
	//Table header generation 
	$table = '
	<table class="servicetable"><tr> 
	<th class="hostname">Host Name</th>
	<th class="service_description">Service</th>
	<th class="status">Status</th>
	<th class="duration">Duration</th>
	<th class="attempt">Attempt</th>
	<th class="last_check">Last Check</th>
	<th class="plugin_output">Status Information</th></tr>';
		
	$resultsCount = count($services);
	//if results are greater than number that the page can display, create page links
	//if no result limit is defined, page will display all results.  Default limit is 100 results
	//calculate number of pages 
	$pageCount = (($resultsCount / $limit) < 1) ? 1 : intval($resultsCount/$limit);
	
	//check if more than one page is needed 
	if($pageCount * $limit < $resultsCount)
	{
		$table .= do_pagenumbers($pageCount,$start,$limit,$resultsCount,'services');
	}
	
	//creates notes for total results as well as form for setting page limits 
	$table .= do_result_notes($start,$limit,$resultsCount,'services');

	// Fixup post filtering indices
	$curidx = 0;
	foreach ($services as $id => $servinfo) {
			unset($services[$id]);
			$services[$curidx++] = $servinfo;
	}

	//process service array   
	//service table rows 
	$last_displayed_host = NULL;
	for($i=$start; $i<=($start+$limit); $i++)
	{
		if ($i > $resultsCount) break;      //short circuit
		if(!isset($services[$i])) continue; //skip undefined indexes of array

		$tr = get_color_code($services[$i]);
		#$url = htmlentities(BASEURL.'index.php?cmd=getservicedetail&arg='.$services[$i]['serviceID']);
		$url = htmlentities(BASEURL.'index.php?mode=filter&type=servicedetail&arg='.$services[$i]['serviceID']);
		#$host_url = htmlentities(BASEURL.'index.php?cmd=gethostdetail&arg='.$services[$i]['host_name']);
		$host_url = htmlentities(BASEURL.'index.php?mode=filter&type=hostdetail&arg='.$services[$i]['host_name']);
		$color = get_host_status_color($services[$i]['host_name']);
		$icon = return_icon_link($services[$i]['host_name']);
		$comments = comment_icon($services[$i]['host_name'], $services[$i]['service_description']);
		$h_comments = comment_icon($services[$i]['host_name']);
		$dt_icon = downtime_icon($services[$i]['scheduled_downtime_depth']);
		$host_dt = downtime_icon(get_host_downtime($services[$i]['host_name']) );

		//removing duplicate host names from table for a cleaner look
		if(isset($_GET['mode']) && $_GET['mode'] == 'view')
		{	 
			if ($services[$i]['host_name'] == $last_displayed_host)
			{
				$td1 = '<td></td>';
			}
			else
			{
				$last_displayed_host = $services[$i]['host_name'];
				$hostlink = "<a class='highlight' href='$host_url' title='View Host Details'>";
				$td1 = "<td class='$color'><div class='hostname'>$hostlink".$services[$i]['host_name']."</a> $icon $host_dt $h_comments</div></td>";
			}
		}
		else
		{
			$hostlink = "<a class='hightlight' href='$host_url' title='View Host Details'>";
			$td1 = "<td class='$color'><div class='hostname'>$hostlink".$services[$i]['host_name']."</a> $icon $host_dt $h_comments</div></td>";
		}
		
		//table data generation 				
		//Using HEREDOC string syntax to print rows 
		$tablerow = <<<TABLEROW
		
		<tr class='statustablerow'>	
			{$td1}
			<td class='service_description'><div class='service_description'><a href="{$url}">{$services[$i]['service_description']}</a>{$comments}{$dt_icon}</div></td>
			<td class="{$tr}">{$services[$i]['current_state']}</td>
			<td class='duration'>{$services[$i]['duration']}</td>
			<td class='attempt'>{$services[$i]['attempt']}</td>
			<td class='last_check'>{$services[$i]['last_check']}</td>
			<td class='plugin_output'><div class='plugin_output'>{$services[$i]['plugin_output']}</div></td>
		</tr>
		
TABLEROW;

		$table .= $tablerow;
		
	}
	$table .= '</table>';
	return $table;
}
//end php 
?>
