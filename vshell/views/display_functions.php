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



//used for initial development testing, no longer in use 
//
function build_table_data($array) //expecting global $hosts, $services, $servicegroups, $hostgroups 
{
	foreach($array as $a)
	{
		print "<tr>\n";		
		foreach($a as $item)
		{
			//print "<td>$key</td><td>$value<td/>\n";	
			if($item!="")
			{
				print "<td>$item<td/>\n";	
			}	
		}
		print "</tr>\n";
	}
	print "</table>\n";
}

/////////////////////////////////////////////////////////////
//used to view object configurations 
//expects $array from objects file such as $hosts_objs 
//        $arg is the argument taken from the browser.  example object=services_objs 
function build_object_list($array, $arg) //expecting arrays from read_objects.php file 
{
	global $authorizations;
	$count = 0;

	foreach($array as $a)
	{
		//default for no permissions 
		$title = '';
		$linkkey = '';
			//change variables based on type of object being viewed 
		switch($arg)
		{
			case 'hosts_objs':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['host_name'];
				$linkkey = 'host'.$a['host_name'];
				$link = htmlentities(BASEURL.'index.php?cmd=gethostdetail&arg='.$name);
				$title = "Host: <a href='$link' title='Host Details'>$name</a>";
			}
			//else{ continue; }
			break;
			
			case 'services_objs':
			if($authorizations['configuration_information']==1)
			{
				$count++;
				$name=$a['service_description'];
				$linkkey = 'service'.$count;
				$host = $a['host_name'];
				$hlink = htmlentities(BASEURL.'index.php?cmd=gethostdetail&arg='.$host);
				$link = htmlentities(BASEURL.'index.php?cmd=getservicedetail&arg='.$linkkey);
				$title = "Service:<a href='$link' title='Service Details'>$name</a><br />
								Host: <a href='$hlink' title='Host Details'>$host</a>";	
			}							
			break;
			
			case 'commands':
			if(($authorizations['host_commands']==1 && $authorizations['service_commands'])
			||$authorizations['system_commands']==1 )
			{
				$name=$a['command_name'];
				$title = "Command: $name";
				$linkkey = $name;
			}
			break;
			
			case 'hostgroups_objs':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['hostgroup_name'];
				$title = "Group Name: $name";
				$linkkey = 'hg'.$name;
			}
			break;
			
			case 'servicegroups_objs':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['servicegroup_name'];
				$title = "Group Name: $name";
				$linkkey = 'sg'.$name;
			}
			break;
			
			case 'timeperiods':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['timeperiod_name'];
				$title = "Timeperiod: $name";
				$linkkey = 'tp'.$name;
			}
			break;
			
			case 'contacts':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['contact_name'];
				$title = "Contact: $name";
				$linkkey = $name;
			}
			break;
			
			case 'contactgroups':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['contactgroup_name'];
				$title = "Contact Group: $name";
				$linkkey = $name;
			}
			break;
			
			default:
			$title = 'Access Denied<br />';
			$linkkey = 'You do not have permissions to view this information';
			break;
			 
		}	
		
		$id = preg_replace('/\./', '_', $linkkey); //replacing dots with underscores
		$id = preg_replace('/\ /', '_', $id);    //replacing spaces with underscores
		//using HEREDOC string syntax 
		$confighead=<<<CONFIG
				
		<h6>{$title} <a class='label' onclick='showHide("{$id}")' href='javascript:void(0)'>
		<img class='label' src="views/images/expand.gif" title="Show Config" alt="Image" height="12" width="12" />
		</a></h6> 
				
		<div class='hidden' id='{$id}'>
		<table class="objectList"> 
		<tr><th>Config</th><th>Value</th></tr>
		
CONFIG;
//end HEREDOC 
		if($title!='') //only display if authorized 
		{
			print $confighead;
			//print raw config data into a table 
			foreach($a as $key => $value)
			{	
				
				print "<tr class='objectList'>
							<td>$key</td>
							<td>$value</td>
						</tr>\n";			
			}
			print "</table></div><br />";
		}
	}	
}

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
	
	//NAV LINKS	 are added to a floated <ul>
	print '<ul class="nav">'; 	
	print '<li class="nav"><a href="index.php" class="nav" rel="internal">Tactical Overview</a></li>'; //default tactical overview link 
		
	if(isset($keys['hosts'], $keys['services'])) 
	{		
		print "<li class='nav'><a href='".$base."view=hosts' class='nav' rel='internal'>Hosts</a></li>"; //hosts
		print "<li class='nav'><a href='".$base."view=services' class='nav' rel='internal'>Services</a></li>"; //services
		print "<li class='nav'><a href='".$base."view=hostgroups' class='nav' rel='internal'>Hostgroups</a></li>"; //hostgroups
		print "<li class='nav'><a href='".$base."view=servicegroups' class='nav' rel='internal'>Servicegroups</a></li>"; //servicegroups
	}


	
	/////////////OBJECT VIEWS 
	if(isset($keys['configuration_information'])) //assuming full admin  
	{
		print "<li class='nav'><a class='nav' onmouseover='showDropdown(\"confDrop\")' onmouseout='hideDropdown(\"confDrop\")' href='javascript:void(0)'>Configurations</a>
		<div onmouseover='showDropdown(\"confDrop\")' onmouseout='hideDropdown(\"confDrop\")' id='confDrop'><ul>";	
	
	
	
		print "<li><a class='nav' href='".$base."object=hosts_objs'>Hosts</a></li>\n"; //hosts
		print "<li><a class='nav' href='".$base."object=services_objs'>Services</a></li>\n"; //services
		print "<li><a class='nav' href='".$base."object=hostgroups_objs'>Hostgroups</a></li>\n"; //hostgroups
		print "<li><a class='nav' href='".$base."object=servicegroups_objs'>Servicegroups</a></li>\n"; //servicegroups
		print "<li><a class='nav' href='".$base."object=timeperiods'>Timeperiods</a></li>\n"; //timeperiods
		print "<li><a class='nav' href='".$base."object=contacts'>Contacts</a></li>\n"; //contacts
		print "<li><a class='nav' href='".$base."object=contactgroups'>Contactgroups</a></li>\n"; //contactgroups
		
				//COMMAND VIEW 
		if(isset($keys['host_commands'],$keys['service_commands'], $keys['system_commands']))
		{	
			//make link for commands 
			print "<li><a href='".$base."object=commands' class='nav'>Commands</a></li>\n"; //commands config
		}	
	 
		print '</ul></div></li>';
	}
	
	//Nagios Core System links dropdown menu 
	if(isset($keys['system_commands']))
	{
		print "<li class='nav'><a class='nav' onmouseover='showDropdown(\"sysDrop\")' 
				onmouseout='hideDropdown(\"sysDrop\")' href='javascript:void(0)'>System Commands</a>
				<div onmouseover='showDropdown(\"sysDrop\")' onmouseout='hideDropdown(\"sysDrop\")' id='sysDrop'><ul>";
		print "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=3'>Comments</a></li>\n";
		print "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=6'>Downtime</a></li>\n";	
		print "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=0'>Process Info</a></li>\n";
		print "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=4'>Performance Info</a></li>\n";
		print "<li><a class='nav' target='_blank' href='".CORECGI."extinfo.cgi?type=7'>Scheduling Queue</a></li>\n";
		print "</ul></div></li>\n";  
	}
	//Nagios Core Reports.  Leaving authorization up to Core for running reports 
		print "<li class='nav'><a class='nav' onmouseover='showDropdown(\"reportDrop\")' 
				onmouseout='hideDropdown(\"reportDrop\")' href='javascript:void(0)'>Reports</a>
				<div onmouseover='showDropdown(\"reportDrop\")' onmouseout='hideDropdown(\"reportDrop\")' id='reportDrop'><ul>";
		print "<li><a class='nav' target='_blank' href='".CORECGI."avail.cgi'>Availability</a></li>\n";
		print "<li><a class='nav' target='_blank' href='".CORECGI."trends.cgi'>Trends</a></li>\n";	
		print "<li><a class='nav' target='_blank' href='".CORECGI."history.cgi?host=all'>Alert History</a></li>\n";
		print "<li><a class='nav' target='_blank' href='".CORECGI."summary.cgi'>Alert Summary</a></li>\n";
		print "<li><a class='nav' target='_blank' href='".CORECGI."histogram.cgi'>Alert Histogram</a></li>\n";
		print "<li><a class='nav' target='_blank' href='".CORECGI."notifications.cgi?contact=all'>Notifications</a></li>\n";
		print "<li><a class='nav' target='_blank' href='".CORECGI."showlog.cgi'>Event Log</a></li>\n";
		
		print "</ul></div></li>\n";   //make dropdown list 
	
	
	
	
	 	
	print '</ul>'; //close main nav list 
	
	
	//For future developments..... 
	/*
	if(isset($keys['system_information']))
	{
		//make link 
		echo "system information is viewable"; 
	}	
	*/ 
		
	
}//end function 

//used for initial creation of nav links, but no longer used 
//
function build_link($type, $name, $class, $target)
{
	$ucname = ucfirst($name);
	$base = BASEURL.'index.php?';
	//heredoc string syntax 
	$link=<<<LINK
	
	<li class="$class"><a href="$base$type=$name" class="$class" rel="$target">$ucname</a></li>	
LINK;

	echo $link;
			
}

////////////////////////////////////
//
//FUNCTION: get_color_code()
//
//DESC:  returns a style class based on host/service status
//
//ARG: expecting a single host or service array 
//
//RETURNS: css style for table row 
//
//USAGE: $tr_class = get_color_code($host)
//
function get_color_code($array) 
{
	switch($array['current_state'])
	{
		case 'OK':
		$tr = 'ok';
		break;
		
		case 'UP':
		$tr = 'ok';
		break;
		
		case 'DOWN':
		$tr = 'down';
		break;
		
		case 'WARNING':
		$tr = 'warning';
		break;
		
		case 'UNKNOWN':
		$tr = 'unknown';
		break;
		
		case 'UNREACHABLE':
		$tr = 'unreachable';
		break;
		
		case 'CRITICAL':
		$tr = 'critical';
		break;
		
		default:
		$tr = 'unknown';
		break;
	
	} 
	return $tr;
}

function get_host_status_color($hostname)
{
	global $hosts;
	$color = '';
	if(isset($hosts))
	{
		foreach($hosts as $host)
		{
			if(trim($hostname) == trim($host['host_name']))
			{
				$color = get_color_code($host);
			}
		}
		 
	}
	return $color;
}

/////////////////////////////////////////////
//expecting host name 
//returns an image icon for the status table if icon exists 
function return_icon_link($hostname)
{

	global $hosts_objs;
	$link = '';
	foreach($hosts_objs as $host)
	{
		if(trim($hostname) == trim($host['host_name']) && isset($host['icon_image']) )
		{
			$icon = $host['icon_image'];
			$link = '<img class="tableIcon" border="0" width="15" height="15" title="" alt="Icon" src="views/images/logos/'.trim($host['icon_image']).'">';
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
	$hostcomments = check_comments($host);
	if($hostcomments)
	{
		global $comments;
		foreach($comments as $comment)
		{
			if(trim($comment['host_name'])==trim($host) )
			{
				$author = $comment['author'];
				$entrytime = date('M d H:i\:s\s', trim($comment['entry_time']));
				$data = $comment['comment_data'];
				if(isset($comment['service_description']))
				{ 
					$desc = $comment['service_description'].' : '; 
				}
				else
				{ 
					$desc = '';  
				}
				
				$cid = $comment['comment_id'];		
				$row = "<tr><td>$author</td><td>$entrytime</td><td>$desc $data</td><td>
							<a href='".CORECMD."cmd_typ=2&com_id=$cid' title='Delete Comment'>
							<img class='iconLink' src='views/images/delete.png' alt='Delete' width='15' height='15' /></a></td></tr>\n";
				print $row;
			}	//end if 
		}//end foreach 
		 
	}//end if 
	else
	{
		print "<tr><td colspan='3'>There are no comments associated with this host</td></tr>";
	}
}

//////////////////////////////////////////////////////
//expecting a host name
//used on host details page 
function get_service_comments($host='', $service='')
{
	$hostcomments = check_comments($host, $service);
	if($hostcomments)
	{
		global $comments;
		foreach($comments as $comment)
		{
			if(isset($comment['service_description']))
			{
				if(trim($comment['host_name'])==trim($host) && trim($comment['service_description']) ==trim($service) )
				{
					$author = $comment['author'];
					$entrytime = date('M d H:i\:s\s', trim($comment['entry_time']));
					$data = $comment['comment_data'];
							
					$cid = $comment['comment_id'];		
					$row = "<tr><td>$author</td><td>$entrytime</td><td>$data</td><td>
								<a href='".CORECMD."cmd_typ=4&com_id=$cid' title='Delete Comment'>
								<img class='iconLink' src='views/images/delete.png' alt='Delete' width='15' height='15' /></a></td></tr>\n";
					print $row;
				}	//end if 
			}//end if isset 
		}//end foreach 
		 
	}//end if 
	else
	{
		print "<tr><td colspan='3'>There are no comments associated with this host</td></tr>";
	}
}

///////////////////////////http://localhost/var/www/http_public/nagpui/views/images/hascomments.png
//Expecting the 'scheduled_downtime_depth' index of a host or service 
//Returns either an empty string or an img link to the icon 
function downtime_icon($arg)
{
	if(trim($arg)>0)
	{
		$img = '<img src="views/images/downtime.png" title="In Downtime" class="tableIcon" alt="DT" height="12" width="12" />'; 
	}
	else
	{
		$img = '';
	}
	return $img;
}


//expecting values passed from hosts.php or services.php 
//creates page numbers based on how many results are being processed for the tables 
function do_pagenumbers($pageCount,$start,$limit,$resultsCount,$type)
{	

	print "<div class='pagenumbers'>";
	$begin = 0;
	$cp_start = isset($_GET['start']) ? htmlentities($_GET['start']) : 0;

	// Build the pagination back arrow
	$back_arrow = NULL;
	$back_arrow_entities = '&laquo;';
	if ($cp_start > 0) {
		//fb($cp_start, "cp_start");
		$link ="index.php?view=$type&start=".($cp_start-$limit);
		$back_arrow = "<a href='$link' class='pagenumbers'>$back_arrow_entities</a>";
	} else { 
		$back_arrow = "<span class='deselect'>$back_arrow_entities</span>";
	}
	print "$back_arrow";

	// Build the direct page links
	for($i=0;$i<=$pageCount;$i++)
	{
		//if end is greater than total results, set end to be the resultCount  
		//$end = ($begin + $limit) < $resultsCount ? ($begin + $limit) : $resultsCount;
		$link = "index.php?view=$type&start=$begin";
		$page = $i+1;
		//check if the link is the current page  
		//if we're on current page, don't print a link 
		if($cp_start == $begin) print "<span class='deselect'> $page </span>";
		else print "<a class='pagenumbers' href='$link'> $page </a>"; 
		//print "Page $i, start= $begin, end = $end <br />";	
		//submit a hidden post page number 	
		$begin = ($begin + $limit) < $resultsCount ? ($begin + $limit) : $resultsCount;
	}

	// Build the pagination forward arrow
	$forward_arrow = NULL;
	$forward_arrow_entities = '&raquo;';
	if ($cp_start + $limit < $resultsCount) {
		$link = "index.php?view=$type&start=".($cp_start+$limit);
		$forward_arrow = "<a href='$link' class='pagenumbers'>$forward_arrow_entities</a>";
	} else {
		$forward_arrow = "<span class='deselect'>$forward_arrow_entities</span>";
	}
	print $forward_arrow;

	print "</div>";
}	//end do_pagenumbers()	

//creates notes and page limit form above host and service tables 
function do_result_notes($start,$limit,$resultsCount,$type)
{
	//check maximum display number for page 
	$end = (($start+$limit)<$resultsCount) ? ($start+$limit) : $resultsCount; 
	print "	
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
				print "<option value=$possible_limit $selected>$possible_limit</option>\n";
			}

	print"</select>
			<input type='submit' name='submit' value='Set Limit' />		
		</form></div>";	
} //end do_result_notes() 


?>
