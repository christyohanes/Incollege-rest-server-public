<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Dokumentasi extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dokumentasi_model', 'dok');
    }

    public function index_get()
    {
        $userId = $this->get('user_id');

        $user = $this->dok->getUserDokumentasi($userId);

        foreach ($user as $row => $array) {
            $photoUrl = base_url() . 'assets/img/dokumentasi/' . $user[$row]['photo'];
            $user[$row]['photo'] = $photoUrl;
        }

        if ($user) {
            $this->response([
                'status' => true,
                'message' => 'Pengambilan data berhasil',
                'data' => $user
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

        if($flag == "DELETE"){
            $id = $this->post('id');

            if ($id === null) {
                $this->response([
                    'status' => false,
                    'message' => 'Coba lagi'
                ], RestController::HTTP_BAD_REQUEST);
            } else {
                $dokumentasiImageName = $this->dok->getDokumentasiImage($id);
                if ($this->dok->deletePengurus($id) > 0) {
                    //jika ada affected row
                    unlink(FCPATH . 'assets/img/dokumentasi/' . $dokumentasiImageName);
                    $this->response([
                        'status' => true,
                        'message' => 'Dokumentasi Berhasil Dihapus'
                    ], RestController::HTTP_OK);
                } else {
                    //not found
                    $this->response([
                        'status' => false,
                        'message' => 'Pilih dokumentasi yang ingin dihapus'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            }
        } else {
            $config['upload_path'] = './assets/img/dokumentasi/';
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
                ];

                if ($this->dok->createDokumentasi($data) > 0) {
                    $this->response([
                        'status' => true,
                        'message' => 'Dokumentasi ditambahkan'
                    ], RestController::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal menambahkan dokumentasi'
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