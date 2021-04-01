<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Shipping_model extends CI_Model
{
    private $baseurl_rajaongkir = "https://api.rajaongkir.com/starter/";
    private $api_key_ro = "eb69544fc1b353eafdd402d0e734528d";
    function __construct()
    {
        $CI = &get_instance();
        parent::__construct();
        // $CI->load->library('shipping');
    }

    function RajaOngkir($data, $type)
    {
        $parameter = "";
        $ro_id_provinsi = "";
        if ($type == "provinsi") {
            $url = $this->baseurl_rajaongkir . "province";
        } else if ($type == "kota") {
            $url = $this->baseurl_rajaongkir . "city?province" . $ro_id_provinsi;
        } else {
            $url = $this->baseurl_rajaongkir . "cost";
        }

        $curl = curl_init();
        if ($type == "cost") {
            $datasend = "origin=" . $data["id_kota_pengirim"] . "&destination=" . $data["id_kota_tujuan"] . "&weight=" . $data["berat"] . "&courier=" . $data["id_kurir"];
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $datasend,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: " . $this->api_key_ro
                ),
            ));
        } else {
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key: " . $this->api_key_ro
                ),
            ));
        }


        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false; //"cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
    function sync_ro_province()
    {
        $getprovince = $this->RajaOngkir(array(), "provinsi");
        $arrayproinvce = json_decode($getprovince, true);

        $dataprovince = array(
            "id_ro_province" => "",
            "name" => "",
        );
        if ($this->db->insert_batch('tbl_brands', $dataprovince)) {
            return true;
        } else {
            return false;
        }
    }
}
