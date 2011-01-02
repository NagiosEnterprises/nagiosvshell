<?php //display_functions.php  this file contains functions that process display info and contain html tags 


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


//////////////////////////////////
//used on the header.php file to create the main nav links 
//
//creates links based on user permissions 
//
//
function build_nav_links() //build page links based on user's permission level 
{
	global $authorizations;

	$keys = array();
	foreach($authorizations as $key => $value)
	{
		//echo "$key : $value <br />";
		if($value == 1) //if permission is set 
		{
			$keys[$key] = 1;
		}
	}
	//print_r($keys);
	//generate links based on permissions 
	$base = BASEURL.'index.php?';

	$navlinks = "";
	//NAV LINKS	 are added to a floated <ul>
	$navlinks .= '<ul class="nav">'; 	
	$navlinks .= '<li class="nav"><a href="index.php" class="nav" rel="internal">Tactical Overview</a></li>'; //default tactical overview link 
		
	if(isset($keys['hosts'], $keys['services'])) 
	{		
		$navlinks .= "<li class='nav'><a href='".$base."mode=view&type=hosts' class='nav' rel='internal'>Hosts</a></li>"; //hosts
		$navlinks .= "<li class='nav'><a href='".$base."mode=view&type=services' class='nav' rel='internal'>Services</a></li>"; //services
		$navlinks .= "<li class='nav'><a href='".$base."mode=view&type=hostgroups' class='nav' rel='internal'>Hostgroups</a></li>"; //hostgroups
		$navlinks .= "<li class='nav'><a href='".$base."mode=view&type=servicegroups' class='nav' rel='internal'>Servicegroups</a></li>"; //servicegroups
	}


	
	/////////////OBJECT VIEWS 
	if(isset($keys['configuration_information'])) //assuming full admin  
	{
		$navlinks .= "<li class='nav'><a class='nav' onmouseover='showDropdown(\"confDrop\")' onmouseout='hideDropdown(\"confDrop\")' href='javascript:void(0)'>Configurations</a>
		<div onmouseover='showDropdown(\"confDrop\")' onmouseout='hideDropdown(\"confDrop\")' id='confDrop'><ul>";	
	
	
	
		$navlinks .= "<li><a class='nav' href='".$base."mode=object&type=hosts_objs'>Hosts</a></li>\n"; //hosts
		$navlinks .= "<li><a class='nav' href='".$base."mode=object&type=services_objs'>Services</a></li>\n"; //services
		$navlinks .= "<li><a class='nav' href='".$base."mode=object&type=hostgroups_objs'>Hostgroups</a></li>\n"; //hostgroups
		$navlinks .= "<li><a class='nav' href='".$base."mode=object&type=servicegroups_objs'>Servicegroups</a></li>\n"; //servicegroups
		$navlinks .= "<li><a class='nav' href='".$base."mode=object&type=timeperiods'>Timeperiods</a></li>\n"; //timeperiods
		$navlinks .= "<li><a class='nav' href='".$base."mode=object&type=contacts'>Contacts</a></li>\n"; //contacts
		$navlinks .= "<li><a class='nav' href='".$base."mode=object&type=contactgroups'>Contactgroups</a></li>\n"; //contactgroups
		
				//COMMAND VIEW 
		if(isset($keys['host_commands'],$keys['service_commands'], $keys['system_commands']))
		{	
			//make link for commands 
			$navlinks .= "<li><a href='".$base."mode=object&type=commands' class='nav'>Commands</a></li>\n"; //commands config
		}	
	 
		$navlinks .= '</ul></div></li>';
	}
	
	//Nagios Core System links dropdown menu 
	if(isset($keys['system_commands']))
	{
		$navlinks .= "<li class='nav'><a class='nav' onmouseover='showDropdown(\"sysDrop\")' 
				onmouseout='hideDropdown(\"sysDrop\")' href='javascript:void(0)'>System Commands</a>
				<div onmouseover='showDropdown(\"sysDrop\")' onmouseout='hideDropdown(\"sysDrop\")' id='sysDrop'><ul>";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=3'>Comments</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=6'>Downtime</a></li>\n";	
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=0'>Process Info</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=4'>Performance Info</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=7'>Scheduling Queue</a></li>\n";
		$navlinks .= "</ul></div></li>\n";  
	}
	//Nagios Core Reports.  Leaving authorization up to Core for running reports 
		$navlinks .= "<li class='nav'><a class='nav' onmouseover='showDropdown(\"reportDrop\")' 
				onmouseout='hideDropdown(\"reportDrop\")' href='javascript:void(0)'>Reports</a>
				<div onmouseover='showDropdown(\"reportDrop\")' onmouseout='hideDropdown(\"reportDrop\")' id='reportDrop'><ul>";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."avail.cgi'>Availability</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."trends.cgi'>Trends</a></li>\n";	
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."history.cgi?host=all'>Alert History</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."summary.cgi'>Alert Summary</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."histogram.cgi'>Alert Histogram</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."notifications.cgi?contact=all'>Notifications</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."showlog.cgi'>Event Log</a></li>\n";
		
		$navlinks .= "</ul></div></li>\n";   //make dropdown list 
	 	
	$navlinks .= '</ul>'; //close main nav list 
	
	
	//For future developments..... 
	/*
	if(isset($keys['system_information']))
	{
		//make link 
		echo "system information is viewable"; 
	}	
	*/

	return $navlinks;	
}//end function 


/* FUNCTION: get_color_code()
 * 
 * DESC:  returns a style class based on host/service status
 * 
 * ARG: expecting a single host or service array 
 * 
 * RETURNS: css style for table row 
 * 
 * USAGE: $tr_class = get_color_code($host)
 */
function get_color_code($array) 
{
	$state = strtolower($array['current_state']);
	$vals = array('ok', 'up', 'down', 'warning', 'unknown', 'critical');
	$str = (in_array($state, $vals) ? $state : 'unknown');
	return $str;
}

/* get_host_status_color($hostname)
 * based on the hostname provided return the color code from get_color_code()
 */
function get_host_status_color($hostname)
{
	$hostname = trim($hostname);

	global $NagiosData;
	$hosts = $NagiosData->getProperty('hosts');

	$color = '';
	if(isset($hosts) && isset($hosts[$hostname]))
	{
		$color = get_color_code($hosts[$hostname]);
	}

	return $color;
}

/* expecting host name 
 * returns an image icon for the status table if icon exists 
 */
function return_icon_link($hostname)
{
	$hostname = trim($hostname);

	global $NagiosData;
	$hosts_objs = $NagiosData->getProperty('hosts_objs');

	$link = '';
	foreach($hosts_objs as $host)
	{
		if($hostname == $host['host_name'] && isset($host['icon_image']) )
		{
			$icon = $host['icon_image'];
			$link = '<img class="tableIcon" border="0" width="15" height="15" title="" alt="Icon" src="views/images/logos/'.$host['icon_image'].'">';
		}
		
	}
	return $link;
}
///////////////////////////////////////////////////
//expecting a hostname, and optionally a service description
// if true, returns the img link for the comment icon 
//
function comment_icon($host='', $service='')
{
	$host = trim($host);
	$service = trim($service);

	$check = check_comments($host,$service);
	$img = '';
	if($check>0)
	{
		$img = '<img src="views/images/hascomments.png" title="'.$check.' Comment(s)" alt="Comments" class="tableIcon" height="15" width="15" />';
	}
	return $img;
}

//////////////////////////////////////////////////////
//expecting a host name
//used on host details page 
function get_host_comments($host='')
{
	$host = trim($host);
	$hostcomments = check_comments($host);
	$comments = "";
	if($hostcomments)
	{
		global $NagiosData;
		$hosts = $NagiosData->getProperty('hosts');

		foreach($hosts[$host]['comments'] as $comment)
		{
			$author = $comment['author'];
			$entrytime = date('M d H:i\:s\s', $comment['entry_time']);
			$data = $comment['comment_data'];
			$desc = '';
			if(isset($comment['service_description']))
			{
				$desc = $comment['service_description'].' : ';
			}

			$cid = $comment['comment_id'];
			$row = "<tr><td>$author</td><td>$entrytime</td><td>$desc $data</td><td>
						<a href='".CORECMD."cmd_typ=2&com_id=$cid' title='Delete Comment'>
						<img class='iconLink' src='views/images/delete.png' alt='Delete' width='15' height='15' /></a></td></tr>\n";
			$comments .= $row;
		}
		 
	}
	else
	{
		$comments .= "<tr><td colspan='3'>There are no comments associated with this host</td></tr>";
	}
	return $comments; 
}

/* expecting a host name
 * used on host details page 
 */
function get_service_comments($host='', $service='')
{
	$host = trim($host);
	$service = trim($service);

// XXX TODO replace CORECMD link with a call to core_command_link(...)

	$hostcomments = check_comments($host, $service);
	$comments = "";
	if($hostcomments)
	{
		global $NagiosData;
		$hosts = $NagiosData->getProperty('hosts');

		if (isset($hosts[$host]) && isset($hosts[$host]['comments'])) {
			foreach($hosts[$host]['comments'] as $comment) {
				if (isset($comment['service_description']) && $comment['service_description'] == $service) {
					$author = $comment['author'];
					$entrytime = date('M d H:i\:s\s', $comment['entry_time']);
					$data = $comment['comment_data'];
							
					$cid = $comment['comment_id'];		
					$row = "<tr><td>$author</td><td>$entrytime</td><td>$data</td><td>
								<a href='".CORECMD."cmd_typ=4&com_id=$cid' title='Delete Comment'>
								<img class='iconLink' src='views/images/delete.png' alt='Delete' width='15' height='15' /></a></td></tr>\n";
					$comments .= $row;
				}
			}
		}
	}//end if 
	else
	{
		$comments .= "<tr><td colspan='3'>There are no comments associated with this host</td></tr>";
	}
	return $comments;
}

///////////////////////////http://localhost/var/www/http_public/nagpui/views/images/hascomments.png
/* Expecting the 'scheduled_downtime_depth' index of a host or service 
 * Returns either an empty string or an img link to the icon 
 */
function downtime_icon($arg)
{
	$img = '';	
	if(trim($arg)>0)
	{
		$img = '<img src="views/images/downtime.png" title="In Downtime" class="tableIcon" alt="DT" height="12" width="12" />'; 
	}

	return $img;
}


/* expecting values passed from hosts.php or services.php 
 * creates page numbers based on how many results are being processed for the tables 
 */
function do_pagenumbers($pageCount,$start,$limit,$resultsCount,$type)
{	

	$pagenums = "<div class='pagenumbers'>";
	$begin = 0;
	$cp_start = isset($_GET['start']) ? htmlentities($_GET['start']) : 0;

	parse_str($_SERVER['QUERY_STRING'], $query_vars);
	unset($query_vars['start']);
	$link_base = 'index.php?' . http_build_query($query_vars);

	// Build the pagination back arrow
	$back_arrow = NULL;
	$back_arrow_entities = '&laquo;';
	if ($cp_start > 0) {
		$link = $link_base . '&start='.($cp_start-$limit);
		$back_arrow = "<a href='$link' class='pagenumbers'>$back_arrow_entities</a>";
	} else { 
		$back_arrow = "<span class='deselect'>$back_arrow_entities</span>";
	}
	$pagenums .= "$back_arrow";

	// Build the direct page links
	for($i = 0; $i <= $pageCount; $i++)
	{
		//if end is greater than total results, set end to be the resultCount  
		$link = $link_base . "&start=$begin";
		$page = $i+1;
		//check if the link is the current page  
		//if we're on current page, don't print a link 
		if($cp_start == $begin) $pagenums .= "<span class='deselect'> $page </span>";
		else $pagenums .= "<a class='pagenumbers' href='$link'> $page </a>"; 

		//submit a hidden post page number 	
		$begin = ($begin + $limit) < $resultsCount ? ($begin + $limit) : $resultsCount;
	}

	// Build the pagination forward arrow
	$forward_arrow = NULL;
	$forward_arrow_entities = '&raquo;';
	if ($cp_start + $limit < $resultsCount) {
		$link = $link_base . '&start='.($cp_start+$limit);
		$forward_arrow = "<a href='$link' class='pagenumbers'>$forward_arrow_entities</a>";
	} else {
		$forward_arrow = "<span class='deselect'>$forward_arrow_entities</span>";
	}
	$pagenums .= $forward_arrow;

	$pagenums .= "</div>";
	return $pagenums;
}	//end do_pagenumbers()	

/* creates notes and page limit form above host and service tables 
 */
function do_result_notes($start,$limit,$resultsCount,$type)
{
	//check maximum display number for page 
	$end = (($start+$limit)<$resultsCount) ? ($start+$limit) : $resultsCount; 
	$resultnotes = "	
			<div class='tablenotes'>
				<p class='note'>Showing results $start - $end of $resultsCount results</p>
				<p class='note'>Current Result Limit: $limit</p>
			</div>
			
			<div class='resultLimit'>	
			<form id='limitform' action='".$_SERVER['PHP_SELF']."?view=$type' method='post'>
			<label class='label' for='pagelimit'>Limit Results</label>
			<select id='pagelimit1' name='pagelimit'>";
			foreach (array(15, 30, 50, 100, 250) as $possible_limit) {
				$selected = ($possible_limit == $limit) ? "selected='selected'" : NULL;
				$resultnotes .= "<option value=$possible_limit $selected>$possible_limit</option>\n";
			}

	$resultnotes .= "</select>
			<input type='submit' name='submit' value='Set Limit' />		
		</form></div>";	
	return $resultnotes;
} //end do_result_notes() 


?>
