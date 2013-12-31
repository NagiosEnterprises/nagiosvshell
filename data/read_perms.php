<?php //read_perms.php script grabs user permission data from cgi.cfg file 


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


function parse_perms_file($permsfile = CGICFG) //returns array of authorization => users[array] 
{
	$cgi = fopen($permsfile, "r") or exit("Unable to open '$permsfile' file!");
	
	if(!$cgi)
	{
		die('cgi.cfg not found');
	}

	$keywords = array('host_commands', 'hosts', 'service_commands', 'services',
			'configuration_information', 'system_commands', 'system_information', 'read_only');
	$keyword_regex = '/('.join('|', $keywords).')/';

	while(!feof($cgi)) //read through file and assign host and service status into separate arrays 
	{
		$line = fgets($cgi); //Gets a line from file pointer. 

		if (!preg_match('/^\s*#/', $line) && preg_match($keyword_regex, $line, $keyword_matches)) {
			$perm = $keyword_matches[1];
			
			list($actual_perm, $userlist) = explode('=', trim($line), 2);
			
			$permusers = explode(',', $userlist);
			//XXX change this, create_function too slow and consumes HUGE memory
			//array_walk($permusers, create_function('&$v', 'trim($v);'));
			if(is_array($permusers)) 
				array_walk($permusers,'trim'); 
			$perms[$actual_perm] = $permusers; //XXX move all to NagiosUser in future versions
		}

	}
	fclose($cgi);

	return $perms;
}

?>
