<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shipping extends CI_Controller
{

    private $redirectUrl = NULL;

    public function __construct()
    {
        parent::__construct();

        check_login_user();
        $this->load->library('session');
        $this->load->model('shipping_model');
    }
    public function syncdataro()
    {
        $syncprovince = $this->shipping_model->sync_ro_province();
        if ($syncprovince == 1) {
            $synccity = $this->shipping_model->sync_city_ro();
            if ($synccity == 1) {
                echo "success";
            } else {
                echo "failed";
            }
        } else {
            echo "failed";
        }
    }
    public function getprovinceautocomplete()
    {
        $keyword = $this->input->get('searchTerm');
        $getprovince = $this->shipping_model->get_list_province_ro('id', 'ASC', '', '', $keyword);
        $arraydata=array();
        if(count($getprovince)>0){
            foreach ($getprovince as  $value) {
                # code...
                $select=array(
                    'val'=>$value->id_ro_province,
                    'label'=> $value->name
                );
                array_push($arraydata,$select);
            }
        }
        $json=array(
            "results"=>$arraydata,
            "total"=>count($arraydata)
        );
        echo json_encode($json);
    }
    public function getcityautocomplete()
    {
        $keyword = $this->input->get('searchTerm');
        $id_province= $this->input->get('id_province');
        $getprovince = $this->shipping_model->get_list_city_ro('id', 'ASC', '', '', $keyword,$id_province);
        $arraydata = array();
        
        if (count($getprovince) > 0) {
            foreach ($getprovince as  $value) {
                # code...
                $select = array(
                    'val' => $value->id_ro_city,
                    'label' => $value->city,
                    'postal_code'=>$value->pcode
                );
                array_push($arraydata, $select);
            }
        }
        $json = array(
            "results" => $arraydata,
            "total" => count($arraydata)
        );
        echo json_encode($json);
    }
}
