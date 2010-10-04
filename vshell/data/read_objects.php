<?php //script reads objects.cache file and builds objects arrays from file 


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


$objs_file = fopen(OBJECTSFILE, "r") or exit("Unable to open objects.cache file!");


//establish sentry arrays 
$hosts_objs = array(); //used different naming to prevent overwriting status arrays 
$services_objs = array();
$hostgroups_objs = array();
$servicegroups_objs = array();
$contacts = array();
$contactgroups = array();
$timeperiods = array();
$commands = array();


//counters for iteration through file 
$hostcounter = 0;
$servicecounter = 0;
$hostgroupcounter = 0;
$servicegroupcounter = 0;
$contactcounter = 0;
$contactgroupcounter = 0;
$timeperiodcounter = 0;
$commandcounter = 0;
	
$case = 0;
	
while(!feof($objs_file)) //read through file and assign host and service status into separate arrays 
{

	//var_dump($line)."<br />";
	$line = fgets($objs_file); //Gets a line from file pointer.
	
	if(ereg('define host', $line) && !ereg('define hostgroup', $line))
	{
		$case = 1; //enable grabbing of host variables
		$hostcounter++;			
		$hosts_objs[$hostcounter] = array(); //starts a new host array 
		
		 				
	}	
	if(ereg('define service', $line) && !ereg('define servicegroup', $line) )
	{
		$case = 2; //enable grabbing of service variables
		$servicecounter++;
		$services_objs[$servicecounter] = array(); //starts a new service array 
		 		
	}
	
	if(ereg('define command', $line) )
	{
		$case = 3; //enable grabbing of variables
		$commandcounter++;
		$commands[$commandcounter] = array(); //starts a new commands array 		 		
	}
	
	if(ereg('define timeperiod', $line) )
	{
		$case = 4; //enable grabbing of variables
		$timeperiodcounter++;
		$timeperiods[$timeperiodcounter] = array(); //starts a new timeperiod array 		 		
	}
	
	if(ereg('define hostgroup', $line) )
	{
		$case = 5; //enable grabbing of variables
		$hostgroupcounter++;
		$hostgroups_objs[$hostgroupcounter] = array(); //starts a new hostgroup array 		 		
	}	
	
	if(ereg('define contact', $line) && !ereg('define contactgroup', $line) )
	{
		$case = 6; 
		$contactcounter++;
		$contacts[$contactcounter] = array(); 		 		
	}	
	
	if(ereg('define contactgroup', $line) )
	{
		$case = 7; //enable grabbing of service variables
		$contactgroupcounter++;
		$contactgroups[$contactgroupcounter] = array(); //starts a new service array 		 		
	}
	
	if(ereg('define servicegroup', $line) )
	{
		$case = 8; //enable grabbing of service variables
		$servicegroupcounter++;
		$servicegroups_objs[$servicegroupcounter] = array(); //starts a new service array 		 		
	}
	
	if(ereg('}', $line) )
	{	 
		$case = 0; //turn off switches once a definition ends 		
	}

	
	//grab variables according to the enabled boolean switch

	switch($case) 
	{
		case 0:
		//switches are off, do nothing 
		break;
				
		case 1: //host definition
		//do something
		if(!ereg('define host', $line) ) //eliminate definition line 
		{
			$strings = explode("\t", trim($line));			
			$key = $strings[0];
			$value = $strings[1];
			$hosts_objs[$hostcounter][$key]= $value;
		}
		break;
		
		case 2: //service definition
		if(!ereg('define service', $line) ) //eliminate definition line 
		{
			$strings = explode("\t", trim($line));
			$key = $strings[0];
			$value = $strings[1];
			$services_objs[$servicecounter][$key]=$value;
		}
		break;
		
		case 3: //command definition
		if(!ereg('define command', $line) ) //eliminate definition line 
		{
			$strings = explode("\t", trim($line));
			$key = $strings[0];
			$value = $strings[1];
			$commands[$commandcounter][$key]=$value;
		}
		break;
		
		case 4: //timeperiod definition
		if(!ereg('define timeperiod', $line) ) //eliminate definition line 
		{
			$strings = explode("\t", trim($line));
			$key = $strings[0];
			$value = $strings[1];
			$timeperiods[$timeperiodcounter][$key]=$value;
		}
		break;	
		
		case 5: //define hostgroups 
		if(!ereg('define hostgroup', $line) ) //eliminate definition line 
		{
			$strings = explode("\t", trim($line));
			$key = $strings[0];
			$value = $strings[1];
			$hostgroups_objs[$hostgroupcounter][$key]=$value;
		}	
		break;
		
		case 6: //define contact
		if(!ereg('define contact', $line) ) //eliminate definition line 
		{
			$strings = explode("\t", trim($line));
			$key = $strings[0];
			$value = $strings[1];
			$contacts[$contactcounter][$key]=$value;
		}	
		break;			
		
		case 7: //define contactgroup
		if(!ereg('define contactgroup', $line) ) //eliminate definition line 
		{
			$strings = explode("\t", trim($line));
			$key = $strings[0];
			$value = $strings[1];
			$contactgroups[$contactgroupcounter][$key]=$value;
		}
		break;
		
		case 8: //define servicegroup 
		if(!ereg('define servicegroup', $line) ) //eliminate definition line 
		{
			$strings = explode("\t", trim($line));
			$key = $strings[0];
			$value = $strings[1];
			$servicegroups_objs[$servicegroupcounter][$key]=$value;
		}
		break;
	}	
	

	
} //end of while

//print_r($hosts_objs); //tested, works
//var_dump($services_objs); //tested, works
//var_dump($hostgroups); //tested, works 
//var_dump($contacts); //tested, works 
//var_dump($contactgroups); //tested, works 
//var_dump($servicegroups); //tested, works 
//var_dump($commands); //tested, works 
//var_dump($timeperiods);
	

fclose($objs_file);	

















?>