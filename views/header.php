<?php //header.php  this page contains all of the html head information, used as an include file 



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



//begin browser output  
 
?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://dublincore.org">
<title><?php echo $page_title; ?></title>

<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="schema.DC" href="http://dublincore.org/2008/01/14/dcelements.rdf#" />
<link rel="schema.DCTERMS" href="http://dublincore.org/2008/01/14/dcterms.rdf#" />
<meta name="description" content="Nagios" />
<meta name="keywords" content="Nagios" />  
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="content-language" content="en" />
<meta name="site" content="Nagios" />
<script type="text/javascript" src="<?php echo BASEURL.'js/jquery-1.4.4.min.js'; ?>"></script>

<script type="text/javascript">

		
/*nav dropdown functions */

/* browser detection */
if (document.layers) {
	visible = 'show';
	hidden = 'hide';
}
if (document.all || document.getElementById) {
	visible = 'visible';
	hidden = 'hidden';
}

function showDropdown(id)
{
	if (document.layers) 
	{
		//alert('there are layers');
		menu = document.layers[id];
	}
	if(document.getElementById)
	{
		menu = document.getElementById(id);
	}
	if(menu)
	{
		//alert(menu);
		menu.style.visibility = visible;		
	} 

}

function hideDropdown(id)
{
	menu = document.getElementById(id);
	if(menu)
	{
		//alert(menu);
		menu.style.visibility = hidden;		
	}

}
/*this function toggles the grids and configuration tables */
function showHide(id)
{
	//alert(id);
	var divID = "#"+id;
	$(divID).slideToggle("fast");
	   
}
/*this function hides the grids and configuration tables that can be toggled*/
function hide()
{
	//alert('this is a functional alert');
	$("div.hidden").hide();
}


</script>

<link rel="stylesheet" href="<?php echo BASEURL.'styles/style.css'; ?>" type="text/css" media="screen" />
<style type="text/css">
/* use external stylesheet to control page style */
</style>


</head>
<body onload="hide()">
	<div class="corelink">
		<a class="label" href="<?php echo COREURL; ?>" target="_blank" title="Access Nagios Core">Access Nagios Core</a>
	</div>

	<div class="topnav">
		<?php //main mav stuff can go here, not sure what yet
		//echo "Main nav";
		build_nav_links(); //see display_functions.php for this function 
		?>
	</div>


<div class="main">
