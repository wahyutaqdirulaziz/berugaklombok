<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{


    public function index()
    {
        $Data['title'] = 'BERUGAK IT LOMBOK';
        $Data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('user/index', $Data);
    }
}
