<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Prestasi extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Prestasi_model', 'prestasi');
    }

    public function index_get()
    {
        $userId = $this->get('user_id');

        $prestasi = $this->prestasi->getPrestasi($userId);

        foreach ($prestasi as $row => $array) {
            $photoUrl = base_url() . 'assets/img/prestasi/' . $prestasi[$row]['photo'];
            $prestasi[$row]['photo'] = $photoUrl;
        }

        if($prestasi){
            $this->response([
                'status' => true,
                'message' => 'Pengambilan data berhasil',
                'data' => $prestasi
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ], RestController::HTTP_NOT_FOUND);
        }
    }


    public function index_post()
    {
        $flag = $this->post('flag');

        if($flag == 'INSERT')
        {
            $config['upload_path'] = './assets/img/prestasi/';
            $config['allowed_types'] = 'png|jpg|gif|jpeg';
            $config['max_size'] = '5000';
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('photo')) {
                $upload_image = $this->upload->data();
                $image_name = $upload_image["file_name"];

                $data = [
                    'user_id' => $this->post('user_id'),
                    'photo' => $image_name,
                    'judul' => $this->post('judul')
                ];

                if ($this->prestasi->createPrestasi($data) > 0) {
                    $this->response([
                        'status' => true,
                        'message' => 'Prestasi ditambahkan'
                    ], RestController::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal menambahkan prestasi'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Upload gambar gagal'
                ], RestController::HTTP_BAD_REQUEST);
            }
        }
    }

}