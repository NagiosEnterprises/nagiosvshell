<?php //read_status.php     This script processes status.dat file and assigns hosts and services into arrays


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


/* TODO 
 * - create status arrays for hostgroups and servicegroups 
 */

/* Parse STATUSFILE for status information, nagios information, as well as 
 * build the details array and collect comments
 */
function parse_status_file($statusfile = STATUSFILE) {

	$file = fopen($statusfile, "r") or die("Unable to open '$statusfile' file!");
	
	if(!$file)
	{
		die("File '$statusfile' not found!");
	}
	
	$status_collector = array();
	$details = array();
	$comments = array();
	$info = array();
	$service_counter = 0;

	list($matches, $curtype, $cursubtype) = array(NULL, NULL, NULL);	
	$kvp = array();
	
	while(!feof($file)) //read through file and assign host and service status into separate arrays 
	{
	
		$line = fgets($file); //Gets a line from file pointer.
		
		if (preg_match('/(host|service|program)(status|comment)/', $line, $matches)) {
			list($fullmatch, $cursubtype, $curtype) = $matches;
		 
		} elseif (preg_match('/^\s*info\s*{\s*$/', $line)) {
			$curtype = 'info';
		
		} elseif (preg_match('/^\s*}\s*$/', $line)) {
		
			if ($curtype == 'status') {			
				$details[$cursubtype][] = $kvp;
				
				if ($cursubtype == 'host') {
					$kvp = process_host_status_keys($kvp); 
					$status_collector[$cursubtype][$kvp['host_name']] = $kvp;
				}
				elseif ($cursubtype == 'service') {
					$kvp['serviceID']=$service_counter++; //added serviceID to details array 
					$kvp = process_service_status_keys($kvp);
					$status_collector[$cursubtype][] = $kvp;
					$status_collector['host'][$kvp['host_name']]['services'][] = $kvp;
				}
				else {	
					$status_collector[$cursubtype][] = $kvp;
				}
		
			} elseif ($curtype == 'comment') {
				$kvp['comment_data'] = htmlentities($kvp['comment_data'], ENT_QUOTES);
				$status_collector['host'][$kvp['host_name']]['comments'][] = $kvp;
				$comments[] = $kvp;
		
			} elseif ($curtype == 'info') {
				$info = $kvp;
	
			} else {
				// another type!
			}
		
			list($cursubtype, $curtype) = array(NULL, NULL);
			$kvp = array();
		
		} elseif ($curtype != NULL) {
			# Collect the key-value pairs for the definition
			list($key, $value) = explode('=', trim($line), 2);
			$kvp[trim($key)] = trim($value);
		
		} else {
			// outside of a status
		}
	}
	
	fclose($file);
	
	return array(
		'hosts' => $status_collector['host'], 
		'services' => $status_collector['service'], 
		'comments' => $comments, 
		'info' => $info, 
		'details' => $details);
}

/* Given the raw data for a collected host process it into usable information
 * Maps host states from integers into "standard" nagios values
 * Assigns to each collected service a hostID
 */
function process_host_status_keys($rawdata) {

	static $hostindex = 1;
	$processed_data = get_standard_values($rawdata, array('host_name', 'plugin_output', 'scheduled_downtime_depth'));
	
	$processed_data['hostID'] = 'Host'.$hostindex++;
	
	$host_states = array( 0 => 'UP', 1 => 'DOWN', 2 => 'UNREACHABLE', 3 => 'UNKNOWN' );
	$processed_data['current_state'] = state_map($rawdata['current_state'], $host_states);
	
	return $processed_data;
}

/* Given the raw data for a collected service process it into usable information
 * Maps service states from integers into "standard" nagios values
 * Assigns to each collected service a serviceID
 */
function process_service_status_keys($rawdata) {

	static $serviceindex = 0;
	$processed_data = get_standard_values($rawdata, array('host_name', 'plugin_output', 'scheduled_downtime_depth', 'service_description'));
	
	$processed_data['serviceID'] = 'service'.$serviceindex++;
	//print "$serviceindex<br />";
	$service_states = array( 0 => 'OK', 1 => 'WARNING', 2 => 'CRITICAL', 3 => 'UNKNOWN' );
	$processed_data['current_state'] = state_map($rawdata['current_state'], $service_states);
	//print_r($processed_data);
	//print "<br /><br />";
	return $processed_data;
}

/* given some raw data return an array of shared ("standard values") and 
 *  keys which need to be copied verbatim into the output
 */
function get_standard_values($rawdata, $identical_keys) {
	$standard_values = array();
	
	foreach($identical_keys as $key) { 
		$standard_values[$key] = $rawdata[$key];
	}
	
	$standard_values['attempt'] = $rawdata['current_attempt'].' / '.$rawdata['max_attempts'];
	$standard_values['duration'] = calculate_duration($rawdata['last_state_change']);
	$standard_values['last_check'] = date('M d H:i\:s\s Y', $rawdata['last_check']);
	
	return $standard_values;
}

/* Given an integer state and an associative array mapping integer states into
 *   human readable values, return the associated value to that state.  If no
 *   appropriate value is provided return 'UNKNOWN'
 */
function state_map($cur_state, $states) {
	return array_key_exists($cur_state, $states) ? $states[$cur_state] : 'UNKNOWN';
}

/* Given a timestamp calculate how long ago, in seconds, that timestamp was.
 * Returns a human readable version of that difference
 */
function calculate_duration($beginning) {
	$now = time();
	$duration = ($now - $beginning);
	//$retval = date('d\d-H\h-i\m-s\s', $duration);
	$retval = coarse_time_calculation($duration);
	return $retval;
}

function coarse_time_calculation($duration) {
    $seconds_per_minute = 60;
    $seconds_per_hour = $seconds_per_minute * $seconds_per_minute;
    $seconds_per_day = 24*$seconds_per_hour;

    $remaining_duration = $duration;
    $days = (int)($remaining_duration/$seconds_per_day);
    $remaining_duration -= $days*$seconds_per_day;
    $hours = (int)($remaining_duration/$seconds_per_hour);
    $remaining_duration -= $hours*$seconds_per_hour;
    $minutes = (int)($remaining_duration/$seconds_per_minute);
    $remaining_duration -= $minutes*$seconds_per_minute;
    $seconds = (int)$remaining_duration;

    $retval = '';
    if ($days > 0) { $retval .= sprintf('%d%s', $days,'d-'); }
    if ($hours > 0 || $days > 0) { $retval .= sprintf('%d%s', $hours, 'h-'); }
    if ($minutes > 0 || $days > 0 || $hours > 0) { $retval .= sprintf('%d%s', $minutes, 'm-'); }
    if ($seconds > 0 || $minutes > 0 || $days > 0 || $hours > 0) { $retval .= sprintf('%d%s', $seconds,'s'); }
    return $retval;
}

?>
