<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //$this->load->model(array('vendor_auto_login_model'));
        $this->load->helper(array('cookie', 'security', 'url'));
        $this->load->library(array('session'));

        // すべてのページからログインチェック
        $this->doCheckLogin();

        $data['authentication'] = array(
            'login_id' => $this->session->authentication['user_id'],
            'login_name' => $this->session->authentication['user_name'],
        );

        $this->load->vars($data);
    }

    function doCheckLogin()
    {
        $login_id = $this->session->authentication['user_id'];

        if (empty($login_id)) {
            redirect(base_url('/login'));
        }
    }
}
