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


//TODO 
	//modify to explode() lines as key and value, and use '=' as delimiter 
	//create status arrays for hostgroups and servicegroups 
	//put inside of a function to prevent variable overlap 

$file = fopen(STATUSFILE, "r") or exit("Unable to open 'status.dat' file!");

if(!$file)
{
	print 'file not found';
}

$hosts = array();  //global array of host status information used for main tables 
$services = array(); //global array of service status info for tables 
$hostcount = 0;
$servicecount = 0;

//keywords to pull from status file 
 $hostname = 'host_name=';
 $servicedes = 'service_description=';
 $currstate = 'current_state='; //status 
 $pluginout = 'plugin_output='; //status details 
 $lastcheck = 'last_check=';
 $ackcheck = 'been_acknowledged=';
 $discheck = 'active_checks_enabled=';
 $currattempt = 'current_attempt=';
 $maxattempt = 'max_attempts=';
 $perf_data = 'performance_data=';
 $laststate = 'last_state_change='; 
 $longplugin = 'long_plugin_output='; 
 $sched_downtime = 'scheduled_downtime_depth';


while(!feof($file)) //read through file and assign host and service status into separate arrays 
{

	$line = fgets($file); //Gets a line from file pointer. 
	if(ereg('hoststatus', $line) )
	{
		$case = 1; //enable grabbing of host variables
		$hostcount++;
		$hosts[$hostcount] = array(); //starts a new host array 
		 				
	}	
	if(ereg('servicestatus', $line) )
	{
		$case = 2; //enable grabbing of service variables
		$servicecount++;
		$services[$servicecount] = array(); //starts a new service array 
		 		
	}
	if(ereg('}', $line) )
	{	 
		$case = 0; //turn off switches once a definition ends 		
	}
	
	
	//grab variables according to the enabled boolean switch 
	if(isset($case) )
	{
		//print "case is set<br />";
	
		switch($case) 
		{
			
			case 0:
			//print "<p>Switches are off</p>";	//switches are off, do nothing 
			break;
			
	//#########################HOST STATUS ##############################		
			case 1: //grab host status lines and put into an array 
			//strpos to check for field line by line
		  	$hostposition = strpos($line,$hostname); //hostname
			if ($hostposition)
			{
				 $name = grab_value($line);	 
				 //print $name."<br />";
				 $hosts[$hostcount]['host_name'] = $name;		 	 
			}	
	
			$stateposition = strpos($line,$currstate); //current state 
			if($stateposition)
			{
				$state = grab_value($line);
				//print 'State is: '.$state."<br />";
				switch($state)
				{
					case 0:
					$state = 'UP';
					break;
					case 1:
					$state = 'DOWN';
					break;
					case 2:
					$state = 'UNREACHABLE';
					break;
					case 3:
					$state = 'UNKNOWN';
					break;
					default:
					$state = 'UNKNOWN';
					break;
				}					
				$hosts[$hostcount]['current_state'] = $state;
			}
			
			$attempts = strpos($line,$currattempt); //current attempt
			if($attempts)
			{
				 $num1 = grab_value($line);
			}	 		 			 	 	 			
			$maxatts = strpos($line, $maxattempt);  //max attempts 	
			if ($maxatts)
			{
				 $num2 = grab_value($line);
				// print 'Attempts: '.$num1.'/ '.$num2.'<br />';
				 $attempt = $num1.'/ '.$num2;
				 $hosts[$hostcount]['attempt'] = $attempt;
				 //print 'Attempts: '.$num1.'/'.$num2.'<br />';		 			 	 	 
			}			
			
			$laststatechange = strpos($line, $laststate);  //last state change	
			if ($laststatechange)
			{
			 	$now=time();					//calculating Duration for service 
			 	$dur = grab_value($line);
			 	
			 	//make function to calculate duration 
				$duration=($now - $dur);
				$fdir = date('d\d-H\h-i\m-s\s', $duration);
				//print 'Duration: '.$fdir; 	 
				$hosts[$hostcount]['duration'] = $fdir;	 	 
			}		
	
			$pluginoutput = strpos($line,$pluginout); //plugin output 
			$check = strpos($line, $longplugin); //check to make sure it's not the long plugin data 
			if($pluginoutput && (!$check) )
			{
				$output = grab_value($line);
				//print 'Status Information: '.$output."<br />";
				$hosts[$hostcount]['plugin_output'] = $output;
			}			
	
			$last_check = strpos($line,$lastcheck); //last check
			if($last_check)
			{
				 $l_check = trim(grab_value($line));
				// print "<p>$l_check:</p>";				 
				 $lastchk = date('M d H:i\:s\s Y', $l_check);
				 //print 'Last Check: '.$lastchk.'<br /><br />'; 
				 $hosts[$hostcount]['last_check'] = $lastchk;
			}
			$hosts[$hostcount]['hostID'] = 'Host'.$hostcount;	
			
			$sched_dt = strpos($line,$sched_downtime);
			if($sched_dt)
			{
				 $dt = grab_value($line);	 
				 //print $name."<br />";
				 $hosts[$hostcount]['scheduled_downtime_depth'] = $dt;		 	 
			}		
			
			break;
			
	//#########################################Service Status ######################		
	
			case 2: //grab service status lines and put into an array 
								
				//strpos to check for field line by line
		  	$hostposition = strpos($line,$hostname);
			if ($hostposition)
			{
				 $name = grab_value($line);	 
				 //print $name."<br />";
				 $services[$servicecount]['host_name'] = $name;		 	 
			}	
			$servdes = strpos($line, $servicedes);
			if($servdes)
			{
				$desc = grab_value($line);
				//print 'Service: '.$desc.'<br />';
				$services[$servicecount]['service_description'] = $desc;	
			}
			$stateposition = strpos($line,$currstate);
			if($stateposition)
			{
				$state = grab_value($line);
				//print 'State is: '.$state."<br />";
				switch($state)
				{
					case 0:
					$state = 'OK';
					break;
					case 1:
					$state = 'WARNING';
					break;
					case 2:
					$state = 'CRITICAL';
					break;
					case 3:
					$state = 'UNKNOWN';
					break;
					default:
					$state = 'UNKNOWN';
					break;
				}
				$services[$servicecount]['current_state'] = $state;
			}
			
			$attempts = strpos($line,$currattempt); //current attempt
			if($attempts)
			{
				 $num1 = grab_value($line);
			}	 		 			 	 	 			
			$maxatts = strpos($line, $maxattempt);  //max attempts 	
			if ($maxatts)
			{
				 $num2 = grab_value($line);
				// print 'Attempts: '.$num1.'/ '.$num2.'<br />';
				 $attempt = $num1.'/ '.$num2;
				 $services[$servicecount]['attempt'] = $attempt;
				 //print 'Attempts: '.$num1.'/'.$num2.'<br />';		 			 	 	 
			}			
			
			$laststatechange = strpos($line, $laststate);  //last state change	
			if ($laststatechange)
			{
			 	$now=time();					//calculating Duration for service 
			 	$dur = grab_value($line);
				$duration=($now - $dur);
				$fdir = date('d\d-H\h-i\m-s\s', $duration);
				//print 'Duration: '.$fdir; 	 
				$services[$servicecount]['duration'] = $fdir;	 	 
			}		
	
			$pluginoutput = strpos($line,$pluginout);
			$check = strpos($line, $longplugin); //check to make sure it's not the long plugin data 
			if($pluginoutput && (!$check) )
			{
				$output = grab_value($line);
				//print 'Status Information: '.$output."<br />";
				$services[$servicecount]['plugin_output'] = $output;
			}			
	
			$last_check = strpos($line,$lastcheck); //last check
			if($last_check)
			{
				 $val = trim(grab_value($line));
				 //$l_check = settype( grab_value($line), 'integer');
				// print "<p>$l_check</p>";
				 $lastchk = date('M d H:i\:s\s Y', $val);
				 //print 'Last Check: '.$lastchk.'<br /><br />'; 
				 $services[$servicecount]['last_check'] = $lastchk; 			 
			}	
			$services[$servicecount]['serviceID'] = 'service'.$servicecount;
			
			$sched_dt = strpos($line,$sched_downtime);
			if($sched_dt)
			{
				 $dt = grab_value($line);	 
				 //print $name."<br />";
				 $services[$servicecount]['scheduled_downtime_depth'] = $dt;		 	 
			}			
			
			break;	
			
			//case default:
				//print 'doing nothing';//do nothing
			//break;
		}	//end of SWITCH 
		
		}//test IF 	
	else
	{
		//print "case is not set<br />";
	}
	
} //end of WHILE 

fclose($file);

//print "Dumping Hosts<br />";	
//var_dump($hosts);
//print "Dumping Services<br />";		
//var_dump($services);	



	
	

?>