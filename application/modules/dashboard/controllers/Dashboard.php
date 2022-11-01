<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Console Pendaftaran';

        $data['content'] = '';
        $page = 'dashboard/index';
        echo modules::run('template/loadview_console', $data, $page);
    }

    public function cekDataKodebooking()
    {
        $kodebooking = $_POST['kodebooking'];
        $databooking = $this->db->get_where('simrsj_webservice.antrean', ['kodebooking' => $kodebooking])->row_array();
        echo json_encode($databooking);
    }
}
