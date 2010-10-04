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



function grab_value($line) //grabs value after equal sign   ####REMOVE once this file is an include 
{
	$stat = substr($line, (strpos($line,'=')+1), (strlen($line)) );
	return $stat;
}


function get_auth() //returns array of authorization => users[array] 
{
	$cgi = fopen(CGICFG, "r") or exit("Unable to open 'cgi.cfg' file!");
	
	if(!$cgi)
	{
		die('cgi.cfg not found');
	}

	
	$keywords = array('host_commands', 'hosts', 'service_commands', 'services',
						'configuration_information', 'system_commands', 'system_information');
						
	
	while(!feof($cgi)) //read through file and assign host and service status into separate arrays 
	{

		$line = fgets($cgi); //Gets a line from file pointer. 
		foreach($keywords as $kword)
		{
			//filter out authorization cfg from file 
			if(ereg($kword, $line) && trim($line[0])!='#' )
			{		
					$userlist = grab_value($line); //csv namelist 
					$users = explode(',', $userlist); //turns namelist into array 
					foreach($users as $u)
					{
						$u = trim($u);
					}
					$perms[$kword] = $users;
					 				
			}		//end of IF conditional	
		}//end of FOREACH 	
	}//end of WHILE 
	fclose($cgi);
	return $perms;
}//end of FUNCTION  


/////////////////GLOBAL PERMISSIONS ARRAY 
$permissions = get_auth();
//print_r($permission);


?>