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
	global $NagiosUser;


	//generate links based on permissions 
	$base = BASEURL.'index.php?';

	$navlinks = "";
	//NAV LINKS	 are added to a floated <ul>
	$navlinks .= '<ul class="nav">'; 	
	$navlinks .= '<li class="nav"><a href="index.php" class="nav" rel="internal">'.gettext('Tactical Overview').'</a></li>'; //default tactical overview link 
		
	$navlinks .= "<li class='nav'><a href='".$base."type=hosts' class='nav' rel='internal'>".gettext('Hosts')."</a></li>"; //hosts
	$navlinks .= "<li class='nav'><a href='".$base."type=services' class='nav' rel='internal'>".gettext('Services')."</a></li>"; //services
	$navlinks .= "<li class='nav'><a href='".$base."type=hostgroups' class='nav' rel='internal'>".gettext('Hostgroups')."</a></li>"; //hostgroups
	$navlinks .= "<li class='nav'><a href='".$base."type=servicegroups' class='nav' rel='internal'>".gettext('Servicegroups')."</a></li>"; //servicegroups
	
	
	/////////////OBJECT VIEWS 
	if($NagiosUser->if_has_authKey('authorized_for_configuration_information')) //assuming full admin  
	{
		$navlinks .= "<li class='nav'><a class='nav' onmouseover='showDropdown(\"confDrop\")' onmouseout='hideDropdown(\"confDrop\")' href='javascript:void(0)'>".gettext('Configurations')."</a>
		<div onmouseover='showDropdown(\"confDrop\")' onmouseout='hideDropdown(\"confDrop\")' id='confDrop'><ul>";	
	
	
	
		$navlinks .= "<li><a class='nav' href='".$base."type=object&objtype_filter=hosts_objs'>".gettext('Hosts')."</a></li>\n"; //hosts
		$navlinks .= "<li><a class='nav' href='".$base."type=object&objtype_filter=services_objs'>".gettext('Services')."</a></li>\n"; //services
		$navlinks .= "<li><a class='nav' href='".$base."type=object&objtype_filter=hostgroups_objs'>".gettext('Hostgroups')."</a></li>\n"; //hostgroups
		$navlinks .= "<li><a class='nav' href='".$base."type=object&objtype_filter=servicegroups_objs'>".gettext('Servicegroups')."</a></li>\n"; //servicegroups
		$navlinks .= "<li><a class='nav' href='".$base."type=object&objtype_filter=timeperiods'>".gettext('Timeperiods')."</a></li>\n"; //timeperiods
		$navlinks .= "<li><a class='nav' href='".$base."type=object&objtype_filter=contacts'>".gettext('Contacts')."</a></li>\n"; //contacts
		$navlinks .= "<li><a class='nav' href='".$base."type=object&objtype_filter=contactgroups'>".gettext('Contactgroups')."</a></li>\n"; //contactgroups
		
				//COMMAND VIEW 
		if($NagiosUser->is_admin()) 
		{	
			//make link for commands 
			$navlinks .= "<li><a href='".$base."type=object&objtype_filter=commands' class='nav'>".gettext('Commands')."</a></li>\n"; //commands config
		}	
	 
		$navlinks .= '</ul></div></li>';
	}
	
	//Nagios Core System links dropdown menu 
	if($NagiosUser->if_has_authKey('authorized_for_system_commands')) 
	{
		$navlinks .= "<li class='nav'><a class='nav' onmouseover='showDropdown(\"sysDrop\")' 
				onmouseout='hideDropdown(\"sysDrop\")' href='javascript:void(0)'>".gettext('System Commands')."</a>
				<div onmouseover='showDropdown(\"sysDrop\")' onmouseout='hideDropdown(\"sysDrop\")' id='sysDrop'><ul>";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=3'>".gettext('Comments')."</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=6'>".gettext('Downtime')."</a></li>\n";	
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=0'>".gettext('Process Info')."</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=4'>".gettext('Performance Info')."</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=7'>".gettext('Scheduling Queue')."</a></li>\n";
		$navlinks .= "</ul></div></li>\n";  
	}
	//Nagios Core Reports.  Leaving authorization up to Core for running reports 
		$navlinks .= "<li class='nav'><a class='nav' onmouseover='showDropdown(\"reportDrop\")' 
				onmouseout='hideDropdown(\"reportDrop\")' href='javascript:void(0)'>".gettext('Reports')."</a>
				<div onmouseover='showDropdown(\"reportDrop\")' onmouseout='hideDropdown(\"reportDrop\")' id='reportDrop'><ul>";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."avail.cgi'>".gettext('Availability')."</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."trends.cgi'>".gettext('Trends')."</a></li>\n";	
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."history.cgi?host=all'>".gettext('Alert History')."</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."summary.cgi'>".gettext('Alert Summary')."</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."histogram.cgi'>".gettext('Alert Histogram')."</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."notifications.cgi?contact=all'>".gettext('Notifications')."</a></li>\n";
		$navlinks .= "<li><a class='nav' target='_blank' href='".CORECGI."showlog.cgi'>".gettext('Event Log')."</a></li>\n";
		
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
function get_color_code($array,$int=false) 
{

	if($int)
		$state = strtolower(return_host_state($array['current_state']));
	else 
		$state = strtolower($array['current_state']);	
	$vals = array('ok', 'up', 'down', 'warning', 'unknown', 'critical', 'pending');
	$str = (in_array($state, $vals) ? $state : 'unknown');
	return $str;
}

/* get_host_status_color($hostname)
 * based on the hostname provided return the color code from get_color_code()
 */
function get_host_status_color($hostname)
{
	global $NagiosData;
	$host = $NagiosData->get_details_by('host',$hostname);

	$color = '';
	if(isset($host))
		$color = get_color_code($host,true);
	

	return $color;
}



//////////////////////////////////////////////////////
//expecting a host name
//used on host details page 
function get_host_comments($host)
{
	global $NagiosData;
	
	$hostcomments = $NagiosData->getProperty('hostcomments');
	$comments = "";
	

	foreach($hostcomments as $comment)
	{
	   if($comment['host_name'] == $host) 
	   {
   		$entrytime = date('M d H:i\:s\s', $comment['entry_time']);
   		$comments .= "<tr><td>".$comment['author']."</td><td>$entrytime</td><td>".$comment['comment_data']."</td><td>
   					<a href='".CORECMD."cmd_typ=2&com_id=".$comment['comment_id']."' title='".gettext('Delete Comment')."'>
   					<img class='iconLink' src='views/images/delete.png' alt='Delete' width='15' height='15' /></a></td></tr>\n";
		}
	}//end foreach 

	if($comments == '')
		$comments .= "<tr><td colspan='4'>".gettext('There are no comments associated with this host')."</td></tr>";
	
	return $comments; 
}

/** expecting a host name
 * used on host details page 
 * @TODO replace CORECMD link with a call to core_command_link(...)
 */
function get_service_comments($host, $service)
{
   global $NagiosData;
	$comments = '';	
	$servicecomments = $NagiosData->getProperty('servicecomments');

	foreach($servicecomments as $comment) 
	{
		if ($comment['host_name'] == $host && $comment['service_description'] == $service) {
			$entrytime = date('M d H:i\:s\s', $comment['entry_time']);		
			$comments .= "<tr><td>".$comment['author']."</td><td>$entrytime</td><td>".$comment['comment_data']."</td><td>
						<a href='".CORECMD."cmd_typ=4&com_id=".$comment['comment_id']."' title='".gettext('Delete Comment')."'>
						<img class='iconLink' src='views/images/delete.png' alt='Delete' width='15' height='15' /></a></td></tr>\n";
		}
	}
   
	if($comments=='')	
		$comments .= "<tr><td colspan='3'>".gettext('There are no comments associated with this host')."</td></tr>";
	
	return $comments;
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

	$pagenums .= "</div><!-- end div.pagenumbers -->\n";
	return $pagenums;
}	//end do_pagenumbers()	

/* creates notes and page limit form above host and service tables 
 */
function do_result_notes($start,$limit,$resultsCount,$type)
{
	//check maximum display number for page 
	$end = (($start+$limit)<$resultsCount) ? ($start+$limit) : $resultsCount; 
	$resultnotes = "	
			<div class='tableNotes'>
				<span class='note' style='vertical-align: bottom;'>Showing results $start - $end of $resultsCount results<br />
									    ".gettext('Current Result Limit').": $limit</span>
			</div><!-- end div.tablenotes -->
			
		<div class='resultLimit'>	
			<form id='limitform' action='".$_SERVER['PHP_SELF']."?type=$type' method='post'>
			<label class='label note' for='pagelimit'>".gettext('Limit Results')."</label><br />
			<select id='pagelimit' name='pagelimit'>";
			foreach (array(15, 30, 50, 100, 250) as $possible_limit) {
				$selected = ($possible_limit == $limit) ? "selected='selected'" : NULL;
				$resultnotes .= "\t\t\t<option value=$possible_limit $selected>$possible_limit</option>\n";
			}

	$resultnotes .= "</select>
			<input type='submit' name='submitbutton' value='".gettext('Set Limit')."' />
		</form>
		</div><!-- end resultLimit --> \n\n";	
	return $resultnotes;
} //end do_result_notes() 


	//creates state and string filters for host and service tables.  Called on hosts.php and services.php  
	//expecting string :(optional) $name_filter 
	//expecting string: $type = 'host' or 'service' 
function result_filter($name_filter="", $type='host')
{
	$ucType = ucfirst($type); 
	$plType = $type.'s';
	$states = ($type == 'host') ? array('', 'UP', 'DOWN', 'UNREACHABLE', 'PENDING', 'PROBLEMS', 'UNHANDLED', 'ACKNOWLEDGED') 
										: array('', 'OK', 'WARNING', 'CRITICAL', 'UNKNOWN', 'PENDING', 'PROBLEMS', 'UNHANDLED', 'ACKNOWLEDGED'); 	
	
	///////////////////////////////build filterdiv 
	$resultFilter= "

	
	<form id='resultfilterform' action='".$_SERVER['PHP_SELF']."' method='get'>
	  <div class='stateFilter'>
		<input type='hidden' name='type' value=\"".$plType."\">
		<label class='label note' for='resultfilter'>".gettext('Filter by State').": </label><br />
		<select id='resultfilter' name='state_filter' onChange='this.form.submit();'>
";

		foreach ($states as $val)
		{
			$selected = (isset($_GET['state_filter']) && $_GET['state_filter'] == $val) ? "selected='selected'" : '';
			$display_val = $val == '' ? 'None' : $val;
			$resultFilter.= "\t\t\t<option value=\"$val\" $selected>$display_val</option>\n";
		}

	$resultFilter.= "
		</select>
		</div> <!-- end stateFilter div -->
		
		<div class='nameFilter'>
		<label class='label note' for='name_filter'>".gettext('Search')." ".$ucType."name: </label><br />
		<input type='text' id='name_filter' name='name_filter' value='".$name_filter."'></input>
		<input type='submit' name='submitbutton' value='Filter' />
	  </div><!-- end nameFilter div -->
	</form>
	
";

	return $resultFilter; 
}//end function result_filter() 

//expects a number (percent value)
//returns an rgb string to use as a css style   "$r,$g,$b"
function color_code($num)
{
    //green is 0,255,0
    $num=intval($num);
    $r = 255;
    $g = 50;
    $b = 0;
    //if green >= 255, start subtracting red 
//    $g +=intval($num*2.5);
    $g +=intval($num*4);
	if($g>=255) 
	{
		$r-= ($g-255); 
		$g=255; 
	}	

    //return into a style : style="background-color: rgb('.$rgb.')
    $rgb="$r,$g,$b";
    //print '<div style="background-color: rgb('.$rgb.');">Test</div>';
    return $rgb;
}


//if status data is being cached, show a link with an option to clear the data and read fresh from status.dat 
function clear_cache_link() {

   $link =''; 

   if(function_exists('apc_fetch'))   {        
      $cache = apc_fetch('status_data_exists');
      if($cache==true) {
         $query = str_replace('&clearcache=true','',$_SERVER['QUERY_STRING']);
         $query = ($query == '') ? '?clearcache=true' :  htmlentities('?'.$query.'&clearcache=true');
         $link = "<div id='clearcache' class='label'>
                     <a href='".$_SERVER['PHP_SELF'].$query."' title='".gettext('Clear Cached Data')."'>".gettext('Refresh Data')."
                     </a></div>"; 

        }
   } 
   return $link;     
         
}

?>
