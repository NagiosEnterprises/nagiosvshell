<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class VS_Controller extends CI_Controller
{
    protected $host_filter;
    protected $name_filter;
    protected $state_filter;
    protected $start_filter;
    protected $limit_filter;

    public function __construct()
    {
        parent::__construct();

        $this->process_result_filters();
        $this->process_pagination_filters();
    }

    protected function process_result_filters()
    {
        $this->process_name_filter();
        $this->process_state_filter();
        $this->process_host_filter();
    }

    protected function process_host_filter()
    {
        $this->host_filter = $this->input->get('host_filter');
    }

    protected function process_name_filter()
    {
        $input = $this->input->get('name_filter');
        $this->name_filter = process_name_filter($input);
    }

    protected function process_state_filter()
    {
        $input = $this->input->get('state_filter');
        $this->state_filter = process_state_filter($input);
    }

    protected function process_pagination_filters()
    {
        $this->process_start_filter();
        $this->process_limit_filter();
    }

    protected function process_limit_filter()
    {
        if ($this->input->post('pagelimit') !== False) {
            setcookie('limit', $this->input->post('pagelimit'));
            $limit = $this->input->post('pagelimit');
        } else {
            $limit = isset($_COOKIE['limit']) ? $_COOKIE['limit'] : RESULTLIMIT;
        }
        $this->limit_filter = $limit;
    }

    protected function process_start_filter()
    {
        $start = $this->input->get('start');
        $this->start_filter = intval($start);
    }

    protected function output($data)
    {
        $format = strtolower($this->input->get('format'));

        if( $format == 'xml' ){
            $this->output_xml($data);
        } else {
            $this->output_json($data);
        }
    }

    protected function output_xml($data)
    {
        header('Content-Type: text/xml');
        $this->load->helper('array_to_xml');

        $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><api_data></api_data>");
        array_to_xml($data, $xml);
        $output = $xml->asXML();

        echo $output;
    }

    protected function output_json($data)
    {
        header('Content-Type: application/json');

        if( empty($data) ){
            echo '{}';
        } elseif (function_exists('json_encode')){
            echo json_encode($data);
        } else {
            $this->load->library('JSON');

            $json = new JSON();

            if(!is_array($data) && method_exists($data, 'to_array')){
                $data = $data->to_array();
            }

            $output = $json->encode($data);
            echo $output;
        }
        
    }

}

/* End of file vs_controller.php */
/* Location: ./application/core/VS_Controller.php */
