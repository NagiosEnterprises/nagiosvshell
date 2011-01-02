<?php

/* Given a timestamp calculate how long ago, in seconds, that timestamp was.
 * Returns a human readable version of that difference
 */
function calculate_duration($beginning)
{
        $now = time();
        $duration = ($now - $beginning);
        //$retval = date('d\d-H\h-i\m-s\s', $duration);
        $retval = coarse_time_calculation($duration);
        return $retval;
}

function coarse_time_calculation($duration)
{
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
