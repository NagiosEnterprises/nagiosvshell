<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function array_to_xml($data, &$xml)
{
    foreach ($data as $key => $value) {
        $key = is_numeric($key) ? 'item'.$key : $key;

        if(is_array($value)) {
            $subnode = $xml->addChild("$key");
            array_to_xml($value, $subnode);
        } else {
            $xml->addChild("$key", "$value");
        }
    }
}

/* End of file array_to_xml_helper.php */
/* Location: ./application/helpers/array_to_xml_helper.php */
