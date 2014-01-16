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
        $limit = $this->input->get('limit');
        $this->limit_filter = empty($limit) ? RESULTLIMIT : $limit;
    }

    protected function process_start_filter()
    {
        $start = $this->input->get('start');
        $this->start_filter = intval($start);
    }
}

/* End of file vs_controller.php */
/* Location: ./application/core/VS_Controller.php */
