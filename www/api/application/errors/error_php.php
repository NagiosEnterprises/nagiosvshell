<?php
    
    $error = array(
        'type' => 'error',
        'message' => 'An internal error has occured while processing the request.'
    );

    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
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

/* End of file error_php.php */
