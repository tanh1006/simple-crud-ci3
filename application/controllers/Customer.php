<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->Model('Mcustomer');
    }

    public function index()
    {
        $this->load->view('customer/list');
    }

    public function all()
    {
        try {
            $params = $this->input->get();

            $customers = $this->Mcustomer->filter($params);

            foreach ($customers as &$customer) {
                $customer['update_col'] = "<a class='update-link' href=\"#form-modal\" data-email='". $customer['email'] ."' data-name='". $customer['name'] ."' data-id='".$customer['id']."' rel=\"modal:open\">Update</a>";
                $customer['delete_col'] = "<a class='delete-link' href='#' data-id='".$customer['id']."'>Delete</a>";
            }

            $content = [
                'draw' => $this->input->post('draw'),
                "recordsTotal" => $this->Mcustomer->count(),
                "recordsFiltered" => $this->Mcustomer->count(),
                'data' => $customers
            ];

            $result = $content;
        } catch (\Exception $ex) {
            $result = [
                'error' => 1,
                'msg' => $ex->getMessage()
            ];
        }

        echo json_encode($result);
        exit();
    }

    public function store() {
        try {
            $params = $this->input->post();

            $this->load->library('form_validation');
            $this->form_validation->set_data($params);
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

            if ($this->form_validation->run() == false) {
                $msgs = $this->form_validation->error_array();
                throw new Exception(implode('\n', $msgs));
            }

            if(!$params['id']) {
                $params['created_at'] = date('Y-m-d H:i:s');
                $newId = $this->Mcustomer->save($params);
                $result = [
                    'error' => 0,
                    'data' => [
                        'id' => $newId
                    ]
                ];
            } else {
                $this->Mcustomer->update($params);
                $result = [
                    'error' => 0,
                    'data' => []
                ];
            }
        } catch (\Exception $ex) {
            $result = [
                'error' => 1,
                'msg' => $ex->getMessage()
            ];
        }

        echo json_encode($result);
        exit();
    }

    public function remove() {
        try {
            $id = $this->input->post('id');
            $this->Mcustomer->delete($id);

            $result = [
                'error' => 0,
                'data' => [
                ]
            ];
        } catch (\Exception $ex) {
            $result = [
                'error' => 1,
                'msg' => $ex->getMessage()
            ];
        }

        echo json_encode($result);
        exit();
    }
}