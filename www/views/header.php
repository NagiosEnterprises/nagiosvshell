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


function display_header($page_title='Nagios Visual Shell')
{
	$js_path = BASEURL.'js/';
	$jquery_path = $js_path.'jquery-1.4.4.min.js';
	$header_js_path = $js_path.'header.inc.js';
	$css_path = BASEURL.'css/style.css';
	$navlinks = build_nav_links();
	$coreurl = COREURL;

	$header = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://dublincore.org">
<title>'.$page_title.'</title>

<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="schema.DC" href="http://dublincore.org/2008/01/14/dcelements.rdf#" />
<link rel="schema.DCTERMS" href="http://dublincore.org/2008/01/14/dcterms.rdf#" />
<meta name="description" content="Nagios" />
<meta name="keywords" content="Nagios" />  
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="content-language" content="en" />
<meta name="site" content="Nagios" />

<link rel="stylesheet" href="'.$css_path.'" type="text/css" media="screen" />
<style type="text/css">
<!-- use external stylesheet to control page style -->
</style>

<script type="text/javascript" src="'.$jquery_path.'"></script>
<script type="text/javascript" src="'.$header_js_path.'"></script>
<script type="text/javascript">
$(document).ready(function() {
  // Handler for .ready() called.
	hide();
});
</script>

</head>
<body>
	<div id="logoDiv"><a href="index.php"><img src="views/images/vshell.png" /></a></div>
	<div class="corelink">
		<a class="label" href="'.$coreurl.'" target="_blank" title="'.gettext('Access Nagios Core').'">'.gettext('Access Nagios Core').'</a>
	</div>
	'.clear_cache_link().'

	<div class="topnav">
		'.$navlinks.'
	</div>


<div class="main">
   
';
	return $header;
}

?>
