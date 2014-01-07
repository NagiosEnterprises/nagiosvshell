<?php  //fetch_icons.php  - separate function to grab all icons for host and service tables 


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

/*
function fetch_host_icons($array)
{
	$icons = ""; 
	$icons.= comment_icon($host['host_name']); //comment icon and count 
	$icons .= downtime_icon($host['scheduled_downtime_depth']); //scheduled downtime icon
	$icons .=  '' 
	//notifications disabled?
	//passive checks? 
	//flapping? 

}
*/ 




/* expecting host name 
 * returns all image icons for the host status tables if icon exists 
 */
function fetch_host_icons($hostname)
{
	$hostname = trim($hostname);
	global $NagiosData;
	$hosts_objs = $NagiosData->getProperty('hosts_objs'); //host config, used to check for icon image
	$host_obj = $hosts_objs[$hostname]; //get host config array 
	$host = $NagiosData->get_details_by('host',$hostname); //host details 
		
	$icons = '';	
	$icons .= "<a href='index.php?type=services&host_filter=".rawurlencode($hostname)."'>
					<img class='tableIcon' src='views/images/statusdetailmulti.png' height='12' widt='12' title='".gettext('See All Services For This Host')."' alt='S' /></a>";
	$icons .= isset($host_obj['icon_image']) ? '<img class="tableIcon" border="0" width="15" height="15" title="" alt="" src="views/images/logos/'.$host_obj['icon_image'].'">' : ''; 
	$icons.= comment_icon($host['host_name']); //comment icon and count, see function def below  
	$icons .= ($host['scheduled_downtime_depth'] > 0) ? '<img src="views/images/downtime.png" title="'.gettext('In Downtime').'" class="tableIcon" alt="DT" height="12" width="12" />' : ''; //scheduled downtime icon
	$icons .= ($host['notifications_enabled'] == 1) ? '' : '<img src="views/images/nonotifications.png" title="'.gettext('Notifications Disabled').'" class="tableIcon" alt="NO NTF" height="12" width="12" />'; //notifications enabled?  
	$icons .= ($host['is_flapping']) == 0 ? '' : '<img src="views/images/flapping.png" title="'.gettext('State Is Flapping').'" class="tableIcon" alt="FLAP" height="12" width="12" />'; //is flapping 
	$icons .= ($host['active_checks_enabled']==0 && $host['passive_checks_enabled']==1) ? '<img src="views/images/passive.png" title="'.gettext('Passive Checks Enabled').'" class="tableIcon" alt="PC" height="12" width="12" />' : ''; //passive host 
	$icons .= ($host['current_state'] != 0 && $host['problem_has_been_acknowledged'] > 0) ? '<img src="views/images/ack.png" title="'.gettext('Problem Has Been Acknowledged').'" class="tableIcon" alt="ACK" height="12" width="12" />' : ''; //acknowledged problem  
	
	return $icons;
}



/* expecting host name 
 * returns all image icons for the host status tables if icon exists 
 */
function fetch_service_icons($service_id)
{
	//$servicename = trim($servicename);
	global $NagiosData;
	$services_objs = $NagiosData->getProperty('services_objs'); //host config, used to check for icon image
	//array_dump($services_objs);
	$service_obj = $services_objs[$service_id]; 
	unset($services_objs); 
	/*
 	foreach($services_objs as $s) 
 	{ 
 		if($s['host_name'] == $hostname && $s['service_description'] == $servicename) $service_obj = $s; //extract host details for icons 
 	}	
	*/
	//$service_obj = $services_objs[$servicename]; //get host config array 
	$service = $NagiosData->get_details_by('service','service'.$service_id); //host details 
	//print_r($details); 
	//die(); 
	/*
 	foreach($details as $d) 
 	{ 
 		if($d['host_name'] == $hostname && $d['service_description'] == $servicename) $service = $d; //extract host details for icons 
 	}	
	*/	
	$icons = '';	
	$icons .= isset($service_obj['icon_image']) ? '<img class="tableIcon" border="0" width="15" height="15" title="" alt="" src="views/images/logos/'.$service_obj['icon_image'].'">' : ''; 
	$icons.= comment_icon($service['host_name'], $service['service_description']); //comment icon and count, see function def below  
	$icons .= ($service['scheduled_downtime_depth'] > 0) ? '<img src="views/images/downtime.png" title="'.gettext('In Downtime').'" class="tableIcon" alt="DT" height="12" width="12" />' : ''; //scheduled downtime icon
	$icons .= ($service['notifications_enabled'] == 1) ? '' : '<img src="views/images/nonotifications.png" title="'.gettext('Notifications Disabled').'" class="tableIcon" alt="NO NTF" height="12" width="12" />'; //notifications enabled?  
	$icons .= ($service['is_flapping']) == 0 ? '' : '<img src="views/images/flapping.png" title="'.gettext('State Is Flapping').'" class="tableIcon" alt="FLAP" height="12" width="12" />'; //is flapping 
	$icons .= ($service['active_checks_enabled']==0 && $service['passive_checks_enabled']==1) ? '<img src="views/images/passive.png" title="'.gettext('Passive Checks Enabled').'" class="tableIcon" alt="PC" height="12" width="12" />' : ''; //passive host 
	$icons .= ($service['current_state'] != 0 && $service['problem_has_been_acknowledged'] > 0) ? '<img src="views/images/ack.png" title="'.gettext('Problem Has Been Acknowledged').'" class="tableIcon" alt="ACK" height="12" width="12" />' : ''; //acknowledged problem  
	
	return $icons;
}







///////////////////////////////////////////////////
//expecting a hostname, and optionally a service description
// if true, returns the img link for the comment icon 
//
function comment_icon($host='', $service='')
{
	$check = check_comments($host,$service);
	$img = '';
	if($check>0)
		$img = '<img src="views/images/hascomments.png" title="'.$check.' Comment(s)" alt="Comments" class="tableIcon" height="15" width="15" />';
	
	return $img;
}






?>