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


function fetch_icons($array)
{
	$sd = isset($array['service_description') ? $array['service_description') : NULL; 
	
	$hosticon = return_icon_link($array['host_name']);
	$comments = comment_icon($array['host_name'], $td);
	$h_comments = comment_icon($array['host_name']);
	$service_dt = downtime_icon($array['scheduled_downtime_depth']);
	$host_dt = downtime_icon(get_host_downtime($array['host_name']) );
	//notifications disabled?
	//passive checks? 
	//flapping? 

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










?>