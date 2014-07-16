<?php
    
    $error = array(
        'type' => 'error',
        'message' => $heading . '. '. $message
    );

    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
    header('Content-Type: application/json');

    if(function_exists('json_encode')){
        echo json_encode($error);
    } else {
        $CI = &get_instance();
        $CI->load->library('JSON');
        $json = new JSON();
        $output = $json->encode($error);
        echo $output;
    }

/* End of file error_404.php */
