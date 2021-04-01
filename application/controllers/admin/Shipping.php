<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shipping extends CI_Controller
{

    private $redirectUrl = NULL;

    public function __construct()
    {
        parent::__construct();

        check_login_user();
        $this->load->library('session');
        // $this->load->library('shipping');
        // $this->load->helper('image');
        // $this->load->model('common_model');
        $this->load->model('shipping_model');
    }
    public function syncdataro()
    {
        // echo "ada";



        $syncprovince = $this->shipping_model->sync_ro_province();
        return $syncprovince;
    }
}
