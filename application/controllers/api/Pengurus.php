<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Pengurus extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pengurus_model', 'pengurus');
    }

    public function index_get()
    {
        $id = $this->get('id');
        //tipe jabatan
        $tj = $this->get('tj');
        if ($tj === null) {
            $pengurus = $this->pengurus->getPengurus($id);
        } else {
            $pengurus = $this->pengurus->getPengurus($id, $tj);
        }

        foreach ($pengurus as $row => $array) {
            $photoUrl = base_url() . 'assets/img/pengurus/' . $pengurus[$row]['photo'];
            $pengurus[$row]['photo'] = $photoUrl;
        }

        if ($pengurus) {
            $this->response([
                'status' => true,
                'message' => 'Pengambilan data berhasil',
                'data' => $pengurus
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
        $id = $this->post('id');
        $flag = $this->post('flag');

        if ($flag == "DELETE") {
            if ($id === null) {
                $this->response([
                    'status' => false,
                    'message' => 'Coba lagi'
                ], RestController::HTTP_BAD_REQUEST);
            } else {
                $pengurusImageName = $this->pengurus->getPengurusImage($id);
                if ($this->pengurus->deletePengurus($id) > 0) {
                    //jika ada affected row
                    unlink(FCPATH . 'assets/img/pengurus/' . $pengurusImageName);
                    $this->response([
                        'status' => true,
                        'message' => 'Pengurus Berhasil Dihapus'
                    ], RestController::HTTP_OK);
                } else {
                    //not found
                    $this->response([
                        'status' => false,
                        'message' => 'Pilih pengurus yang ingin dihapus'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            }
        } else {
            $config['upload_path'] = './assets/img/pengurus/';
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
                    'nama' => $this->post('nama'),
                    'jabatan' => $this->post('jabatan'),
                    'tipe_jabatan' => $this->post('tipe_jabatan')
                ];

                if ($this->pengurus->createPengurus($data) > 0) {
                    $this->response([
                        'status' => true,
                        'message' => 'Pengurus ditambahkan'
                    ], RestController::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal menambahkan pengurus'
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