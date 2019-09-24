<?php
defined('BASEPATH') or exit('No direct script access allowed');

class depan extends CI_Controller
{

    public function index()
    {
        $Data['title'] = 'BERUGAK IT';
        $this->load->view('depan/index', $Data);
    }
}
