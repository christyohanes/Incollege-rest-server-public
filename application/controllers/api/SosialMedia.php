<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class SosialMedia extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SosialMedia_model', 'sosmed');
    }

    public function index_put(){
        $user_id = $this->put('user_id');

        $data = [
            'instagram' => $this->put('instagram'),
            'youtube' => $this->put('youtube'),
            'email' => $this->put('email')
        ];
        
        if ($this->sosmed->updateSosialMedia($data, $user_id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'Sosial media berhasil diupdate'
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Update sosial media gagal'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function index_get()
    {
        $user_id = $this->get("user_id");

        $user = $this->sosmed->getSosialMedia($user_id);

        if ($user) {
            $this->response([
                'status' => true,
                'data' => $user
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Isi dengan link sosial media organisasi'
            ], RestController::HTTP_NOT_FOUND);
        }
    }
}