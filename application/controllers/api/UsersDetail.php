<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class UsersDetail extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UsersDetail_model', 'userdetail');
    }

    public function index_get()
    {
        $id = $this->get('id');

        $user = $this->userdetail->getUserDetail($id);
        $photoUrl = base_url() . 'assets/img/user-photo/' . $user['photo'];
        $user['photo'] = $photoUrl;

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
        $config['upload_path'] = './assets/img/user-photo/';
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
                'deskripsi' => $this->post('deskripsi'),
                'visi' => $this->post('visi'),
                'misi' => $this->post('misi')
            ];

            $user = $this->userdetail->getUserDetail($data['user_id']);
            if ($user) {
                $old_image_name = $user['photo'];
                unlink(FCPATH . './assets/img/user-photo/' . $old_image_name);

                if ($this->userdetail->updateUserDetail($data, $user['user_id']) > 0) {
                    $this->response([
                        'status' => true,
                        'message' => 'Update Berhasil'
                    ], RestController::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal mengupdate'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'User Tidak Ada'
                ], RestController::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal upload gambar'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }
}
