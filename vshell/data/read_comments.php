<?php //this file reads for any host/service comments and generates their global arrays 


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



//reads status.dat file for host/service comments and returns them in an array 
function read_comments()
{
	$f = fopen(STATUSFILE, "r") or exit("Unable to open status.dat file!");
	$comments = array();
	$counter=0; //array index counter 
	$case = 0;
	while(!feof($f)) //read through file and assign host and service status into separate arrays 
	{
		
		$line = fgets($f); //Gets a line from file pointer. 
		if(ereg('servicecomment', $line) || ereg('hostcomment', $line) )
		{
			//echo "starting grab?<br /><br />";
			$case = 1; //enable grabbing of host variables
			$counter++;			
			$comments[$counter] = array(); //starts a new host array 		 				
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
				if(!ereg('servicecomment', $line) && !ereg('hostcomment', $line) ) //eliminate definition line 
				{
					//echo "should be grabbing a line<br />";
					$strings = explode('=', trim($line));			
					$key = $strings[0];
					$value = $strings[1];
					//added conditional to count for additional '=' signs in line
					if(isset($strings[2]))
					{
						$i=2;
						while(isset($strings[$i]))
						{
							$value.='='.$strings[$i];
							$i++;
						}
					}
					$comments[$counter][$key]= $value;
					//THESE VALUES WILL NEED TO BE TRIMMED of whitespace!!! 
				}
				break;
				
			}	//end of switch 	
	
	}//end of WHILE 
	fclose($f);//close file 
	return $comments;
}//end of function 

//GLOBAL comments array 
$comments = read_comments();

//print_r($comments);








?>