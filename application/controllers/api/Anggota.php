<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Anggota extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Anggota_model', 'anggota');
    }

    public function index_get()
    {
        $user_id = $this->get('user_id');

        $anggota = $this->anggota->getAnggota($user_id);

        foreach ($anggota as $row => $array) {
            $photoUrl = base_url() . 'assets/img/anggota/' . $anggota[$row]['photo'];
            $anggota[$row]['photo'] = $photoUrl;
        }

        if ($anggota) {
            $this->response([
                'status' => true,
                'message' => 'Pengambilan data berhasil',
                'data' => $anggota
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
                $anggotaImageName = $this->anggota->getAnggotaImage($id);
                if ($this->anggota->deleteAnggota($id) > 0) {
                    //jika ada affected row
                    unlink(FCPATH . 'assets/img/anggota/' . $anggotaImageName);
                    $this->response([
                        'status' => true,
                        'message' => 'Anggota Berhasil Dihapus'
                    ], RestController::HTTP_OK);
                } else {
                    //not found
                    $this->response([
                        'status' => false,
                        'message' => 'Pilih anggota yang ingin dihapus'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            }
        } else {
            $config['upload_path'] = './assets/img/anggota/';
            $config['allowed_types'] = 'png|jpg|gif|jpeg';
            $config['max_size'] = '5000';
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('photo')) {
                $upload_image = $this->upload->data();
                $image_name = $upload_image["file_name"];

                $data = [
                    'user_id' => $this->post('user_id'),
                    'nama' => $this->post('nama'),
                    'angkatan' => $this->post('angkatan'),
                    'photo' => $image_name
                ];

                if ($this->anggota->createAnggota($data) > 0) {
                    $this->response([
                        'status' => true,
                        'message' => 'Anggota ditambahkan'
                    ], RestController::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal menambahkan anggota'
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
