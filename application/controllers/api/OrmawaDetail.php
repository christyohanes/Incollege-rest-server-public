<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class OrmawaDetail extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ormawa_model', 'ormawa');
    }

    public function index_get()
    {
        $user_id = $this->get('user_id');
        $ormawa = $this->ormawa->getOrmawaDetail($user_id);
        if ($ormawa) {
            $photoUrl = base_url() . 'assets/img/user-photo/' . $ormawa['user_photo'];
            $ormawa['user_photo'] = $photoUrl;
            if ($ormawa) {
                $this->response([
                    'status' => true,
                    'message' => 'Pengambilan data berhasil',
                    'data' => $ormawa
                ], RestController::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ], RestController::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ], RestController::HTTP_NOT_FOUND);
        }
    }
}
