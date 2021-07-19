<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Ormawa extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ormawa_model', 'ormawa');
    }

    public function index_get()
    {
        $flag = $this->get('flag');

        if ($flag == 'FAKULTAS') {
            $fakultas = $this->get('fakultas');
            $ormawa = $this->ormawa->getOrmawa($fakultas);
            if ($ormawa) {
                foreach ($ormawa as $row => $array) {
                    $photoUrl = base_url() . 'assets/img/user-photo/' . $ormawa[$row]['user_photo'];
                    $ormawa[$row]['user_photo'] = $photoUrl;
                }
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
        } else {
            $universitas = $this->get('universitas');
            $ormawa = $this->ormawa->getOrmawaUniversitas($universitas);
            if ($ormawa) {
                foreach ($ormawa as $row => $array) {
                    $photoUrl = base_url() . 'assets/img/user-photo/' . $ormawa[$row]['user_photo'];
                    $ormawa[$row]['user_photo'] = $photoUrl;
                }
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
}
