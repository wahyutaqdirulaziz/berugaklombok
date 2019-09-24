<?php
defined('BASEPATH') or exit('No direct script access allowed');

class auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->form_validation->set_rules(
            'email',
            'Email',
            'trim|required',

        );
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $Data['title'] = 'BERUGAK IT';
            $this->load->view('templates/auth_header', $Data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {

            $this->login();
        }
    }

    private function login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        //cek user yang terdaftar
        if ($user) {
            ///user belum di activasi
            if ($user['is_active'] == 1) {
                //cek password
                if (password_verify($password, $user['password'])) {
                    $Data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];
                    $this->session->set_userdata($Data);
                    redirect('user');
                } else {

                    $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">
                    pasword anda salah!
                  </div> ');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">
            email ini belum di activasi!
          </div> ');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">
            email anda belum terdaftar silahkan daftar akun dulu!
          </div> ');
            redirect('auth');
        }
    }


    public function registration()
    {

        $this->form_validation->set_rules('name', 'Name', 'required|trim', [
            'required' => 'masukan nama anda'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'email yang anda masukan sudah di gunakan',
            'required' => 'masukan email anda'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'password anda tidak sama', 'min_length' => 'password anda telalu pendek '
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]',);






        if ($this->form_validation->run() == false) {

            $Data['title'] = 'BERUGAK IT';
            $this->load->view('templates/auth_header', $Data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $Data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'date_created' => time()
            ];
            $this->db->insert('user', $Data);
            $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">
            Akun anda berhasil di buat silahkan masuk untuk belajar di berugak it lombok!
          </div> ');
            redirect('auth');
        }
    }
    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">
            kamu sudah keluar!
          </div> ');
        redirect('auth');
    }
}
