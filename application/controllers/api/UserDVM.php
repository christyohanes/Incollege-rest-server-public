<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class UserDVM extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserDVM_model', 'userdvm');
    }

    public function index_put()
    {
        $id = $this->put('id');

        $data = [
            'deskripsi' => $this->put('deskripsi'),
            'visi' => $this->put('visi'),
            'misi' => $this->put('misi')
        ];
        
        if ($this->userdvm->updateUserDVM($data, $id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'berhasil'
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }
}