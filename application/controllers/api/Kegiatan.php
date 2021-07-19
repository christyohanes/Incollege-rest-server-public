<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Kegiatan extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kegiatan_model', 'kegiatan');
    }

    public function index_get()
    {
        $userId = $this->get('user_id');

        $kegiatan = $this->kegiatan->getKegiatan($userId);

        foreach ($kegiatan as $row => $array) {
            $photoUrl = base_url() . 'assets/img/kegiatan/' . $kegiatan[$row]['photo'];
            $kegiatan[$row]['photo'] = $photoUrl;
        }

        if($kegiatan){
            $this->response([
                'status' => true,
                'message' => 'Pengambilan data berhasil',
                'data' => $kegiatan
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
            $config['upload_path'] = './assets/img/kegiatan/';
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

                if ($this->kegiatan->createKegiatan($data) > 0) {
                    $this->response([
                        'status' => true,
                        'message' => 'Kegiatan ditambahkan'
                    ], RestController::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal menambahkan kegiatan'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Upload gambar gagal'
                ], RestController::HTTP_BAD_REQUEST);
            }
        } else if($flag == 'DELETE') {
            $id = $this->post('id');

            if ($id === null) {
                $this->response([
                    'status' => false,
                    'message' => 'Coba lagi'
                ], RestController::HTTP_BAD_REQUEST);
            } else {
                $kegiatanImageName = $this->kegiatan->getKegiatanImage($id);
                if ($this->kegiatan->deleteKegiatan($id) > 0) {
                    //jika ada affected row
                    unlink(FCPATH . 'assets/img/kegiatan/' . $kegiatanImageName);
                    $this->response([
                        'status' => true,
                        'message' => 'Kegiatan Berhasil Dihapus'
                    ], RestController::HTTP_OK);
                } else {
                    //not found
                    $this->response([
                        'status' => false,
                        'message' => 'Pilih kegiatan yang ingin dihapus'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            }
        }
    }
}