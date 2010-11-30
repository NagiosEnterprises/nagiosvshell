<?php 

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

// Check for Alternative PHP Cache enabled on the system
function useAPC() {
  $useAPC = FALSE;

  // if we have PHP and APC
  $havePHP = (1 === version_compare(PHP_VERSION, '5.2.0') ? true : false);
  $haveAPC = (extension_loaded('apc') && 1 === version_compare(phpversion('apc'), '3.0.13') ? true : false);

  if ($havePHP && $haveAPC) {
    // if APC and upload tracking is enabled
    if (ini_get('apc.enabled')) {
      //fb("APC enabled.. rolling");
      $useAPC = TRUE;

      if (ini_get('apc.rfc1867')) {
        // get the stats
        $key = ini_get('apc.rfc1867_prefix') . $_REQUEST['apcid'];
        $stats = apc_fetch($key);

        fb($stats, "APC stats");
      }
    }
  }
  return $useAPC;
}

// Read nagios information from APC (if enabled) or the backing files on disk
// keyword is one of 'objects', 'status', or 'perms'
// backing_file is one of the constants defined in constants.inc.php
// cache_keys are the names of the arrays to be restored at the end of this function
//   These keys are the names of the globals previously defined (mostly) in data.inc.php
function cache_or_disk($keyword, $backing_file, $cache_keys) {

//  fb("cache_or_disk({$keyword}, {$backing_file}, ...");
//  fb($cache_keys, "cache_keys");

  $known_keywords = array('objects', 'status', 'perms');
  if (!in_array($keyword, $known_keywords)) {
    // XXX do something better
    fb($keyword, "Unknown keyword");
    die("Unknown keyword '$keyword'");
  }

  global $useAPC;

  // Make the keys each global for modification
  //array_walk($cache_keys, create_function('$v,$k', 'eval(\'global $\'.$v.\';\');'));
  foreach($cache_keys as $key) {
    $globalize = 'global $'.$key.';';
    eval($globalize);
    //fb("Globalized \$$key");
  }

  list($start_time, $end_time) = array(NULL, NULL);
  if ($useAPC) {

    //fb(apc_cache_info('user'), "user cache info");

    // Check for the last time the file was read and cached and compare it to the 
    // last time it was updated
    // The success variable determins if the last read key was in the cache
    $last_disk_read = apc_fetch('last_'.$keyword.'_read', $success);
    $file_modified_time = filemtime($backing_file);

    $read_file = $last_disk_read - $file_modified_time < 0;

//    fb("success: {$success}; last read timestamp {$last_disk_read}, last modified timestamp {$file_modified_time}");
//    fb($last_disk_read - $file_modified_time, "modification difference");
   
    $start_time = microtime(TRUE);

    $cacheFail = FALSE;
    if ($success && !$read_file) {

      // Loop through each variables cached value.  If a read fails note it and
      // read from disk
      foreach($cache_keys as $key) {
        $cmd = '$'.$key.' = apc_fetch(\''.$key.'\', $success);';
        //fb($cmd, "cache load command");
        eval($cmd);
        if (!$success) {
          $cacheFail = TRUE;
          //fb("Cache Fail for key {$key}!");
          break;
        }
      }

      if (!$cacheFail) { 
        // Every key was found in cache
        //fb("$keyword data from cache!");
      }
    }

    // The cache is enabled and of the following three conditions occurred
    //  There was a cache miss
    //  The data file is newer than the cached version
    if (!$success || $read_file || $cacheFail) {

      read_disk($keyword, $cache_keys);

      foreach($cache_keys as $key) {
        eval('apc_store(\''.$key.'\', $'.$key.');'); 
      }

      apc_store('last_'.$keyword.'_read', time());

      //fb("$keyword data from disk!");
    }
  } else {
    read_disk($keyword, $cache_keys);

    //fb("APC failed... loaded $keyword from disk");
  }

  $end_time = microtime(TRUE);
  //fb($end_time - $start_time, "Elapsed load time");
}

// If we have to read from disk, call the appropriate parsing
// function
function read_disk($keyword, $cache_keys) {
  include("read_$keyword.php");

  // Make the keys each global for modification
  //array_walk($cache_keys, create_function('$v,$k', 'eval(\'global $\'.$v.\';\');'));
  foreach($cache_keys as $key) {
    $globalize = 'global $'.$key.';';
    eval($globalize);
    //fb("Globalized \$$key");
  }


  // Call the appropriate parse_ _file() function
  array_walk($cache_keys, create_function('&$v,$k', '$v = \'$\'.$v;'));

  $disk_read_string = 'list(';
  $disk_read_string .= join(", ", $cache_keys);
  $disk_read_string .= ') = parse_'.$keyword.'_file();';

  eval($disk_read_string);
}

?>
