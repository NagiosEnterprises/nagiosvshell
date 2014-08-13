<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Given a timestamp calculate how long ago, in seconds, that timestamp was.
 * Returns a human readable version of that difference
 */
function calculate_duration($beginning)
{
    $now = time();
    $duration = ($now - $beginning);
    $retval = coarse_time_calculation($duration);

    return $retval;
}

function coarse_time_calculation($duration)
{
    $seconds_per_minute = 60;
    $seconds_per_hour = $seconds_per_minute * $seconds_per_minute;
    $seconds_per_day = 24*$seconds_per_hour;

    $remaining_duration = $duration;
    $days = (int) ($remaining_duration/$seconds_per_day);
    $remaining_duration -= $days*$seconds_per_day;
    $hours = (int) ($remaining_duration/$seconds_per_hour);
    $remaining_duration -= $hours*$seconds_per_hour;
    $minutes = (int) ($remaining_duration/$seconds_per_minute);
    $remaining_duration -= $minutes*$seconds_per_minute;
    $seconds = (int) $remaining_duration;

    $retval = '';

    if ($days > 0) {
        $retval .= sprintf('%d%s', $days,'d-');
    }

    if ($hours > 0 || $days > 0) {
        $retval .= sprintf('%d%s', $hours, 'h-');
    }

    if ($minutes > 0 || $days > 0 || $hours > 0) {
        $retval .= sprintf('%d%s', $minutes, 'm-');
    }

    if ($seconds > 0 || $minutes > 0 || $days > 0 || $hours > 0) {
        $retval .= sprintf('%d%s', $seconds,'s');
    }

    return $retval;
}

/**
*	splits line of status file into key value pair and trims strings
*	@param string $line the line being processed by the parser
*	@return mixed $array string $key, string $value
*/
function get_key_value($line)
{
    $strings = explode('=', $line,2);
    $key = isset($strings[0]) ? trim($strings[0]) : '';
    $value = isset($strings[1]) ? trim($strings[1]) : '';

    return array($key,$value);

}

/** Given the raw data for a collected host process it into usable information
 * Maps host states from integers into "standard" nagios values
 * Assigns to each collected service a hostID
 */
// function process_host_status_keys($rawdata)
function process_host_status_keys(&$data)
{
    //static $hostindex = 1;

   // $data['hostID'] = 'Host'.$hostindex++;

    $host_states = array( 0 => 'UP', 1 => 'DOWN', 2 => 'UNREACHABLE', 3 => 'UNKNOWN' );

    //added conditions for pending state -MG
    if ($data['current_state'] == 0 && $data['last_check'] == 0) {
        $data['current_state'] = 'PENDING';
        $data['plugin_output']="No data received yet";
        $data['duration']="N/A";
        $data['attempt']="N/A";
        $data['last_check']="N/A";
    } else {
        $data['current_state'] = state_map($data['current_state'], $host_states);
        //display values
        $data['attempt'] = $data['current_attempt'].' / '.$data['max_attempts'];
        $data['duration'] = calculate_duration($data['last_state_change']);
        $data['last_check'] = date('M d H:i\:s\s Y', intval($data['last_check']));
    }
    //return $processed_data;
}

/* Given the raw data for a collected service process it into usable information
 * Maps service states from integers into "standard" nagios values
 * Assigns to each collected service a serviceID
 */
function process_service_status_keys(&$data)
{
  //  static $serviceindex = 0;

  //  $data['serviceID'] = 'service'.$serviceindex++;
    $service_states = array(
        0 => 'OK',
        1 => 'WARNING',
        2 => 'CRITICAL',
        3 => 'UNKNOWN'
    );

    //added conditions for pending state -MG
    if ($data['current_state'] == 0 && $data['last_check'] == 0) {
        $data['current_state'] = 'PENDING';
        $data['plugin_output']="No data received yet";
        $data['duration']="N/A";
        $data['attempt']="N/A";
        $data['last_check']="N/A";
    } else {
        $data['current_state'] = state_map($data['current_state'], $service_states);
        //UI display values
        $data['attempt'] = $data['current_attempt'].' / '.$data['max_attempts'];
        $data['duration'] = calculate_duration($data['last_state_change']);
        $data['last_check'] = date('M d H:i\:s\s Y', intval($data['last_check']));
    }
}

/* Given an integer state and an associative array mapping integer states into
 *   human readable values, return the associated value to that state.  If no
 *   appropriate value is provided return 'UNKNOWN'
 */
function state_map($cur_state, $states)
{
    return array_key_exists($cur_state, $states) ? $states[$cur_state] : 'UNKNOWN';
}

/* End of file data_utils_helper.php */
/* Location: ./application/helpers/data_utils_helper.php */
