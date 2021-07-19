<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Post extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Post_model', 'post');
    }

    public function index_get()
    {
        $user_id = $this->get('user_id');
        //tipe jabatan
        $kategori = $this->get('kategori');

        if ($user_id !== null && $kategori !== null) {
            //mengambil post dengan kategori dan user mana
            $post = $this->post->getPostByKategoriUserId($user_id, $kategori);
        } else if ($user_id !== null && $kategori === null) {
            //mengambil post berdasarkan user
            $post = $this->post->getPostByUserId($user_id);
        } else if ($kategori !== null && $user_id === null) {
            //mengambil post berdasarkan kategori
            $post = $this->post->getPostByKategori($kategori);
        } else {
            //mengambil semua post
            $post = $this->post->getPostByUserId();
        }

        foreach ($post as $row => $array) {
            $photoUrl = base_url() . 'assets/img/user-photo/' . $post[$row]['user_photo'];
            $post[$row]['user_photo'] = $photoUrl;

            $posterUrl = base_url() . 'assets/img/post/' . $post[$row]['poster'];
            $post[$row]['poster'] = $posterUrl;
        }

        if ($post) {
            $this->response([
                'status' => true,
                'message' => 'Pengambilan data berhasil',
                'data' => $post
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

        if ($flag == "INSERT") {
            $config['upload_path'] = './assets/img/post/';
            $config['allowed_types'] = 'png|jpg|gif|jpeg';
            $config['max_size'] = '5000';
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('photo')) {
                $upload_image = $this->upload->data();
                $image_name = $upload_image["file_name"];

                $date_now = date("Y-m-d H:i:s");

                $data = [
                    'user_id' => $this->post('user_id'),
                    'kategori' => $this->post('kategori'),
                    'photo' => $image_name,
                    'judul' => $this->post('judul'),
                    'isi' => $this->post('isi'),
                    'waktu_post' => $date_now
                ];

                if ($this->post->createPost($data) > 0) {
                    $this->response([
                        'status' => true,
                        'message' => 'Post ditambahkan'
                    ], RestController::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal menambahkan post'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Upload gambar gagal'
                ], RestController::HTTP_BAD_REQUEST);
            }
        } else if ($flag == 'UPDATE') {
            $postId = $this->post('id');

            $config['upload_path'] = './assets/img/post/';
            $config['allowed_types'] = 'png|jpg|gif|jpeg';
            $config['max_size'] = '5000';
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('photo')) {
                $postImageName = $this->post->getPostImage($postId);
                unlink(FCPATH . 'assets/img/post/' . $postImageName);
                $upload_image = $this->upload->data();
                $image_name = $upload_image["file_name"];

                $data = [
                    'user_id' => $this->post('user_id'),
                    'kategori' => $this->post('kategori'),
                    'photo' => $image_name,
                    'judul' => $this->post('judul'),
                    'isi' => $this->post('isi')
                ];

                if ($postId) {
                    if ($this->post->updatePost($data, $postId) > 0) {
                        $this->response([
                            'status' => true,
                            'message' => 'Update post berhasil'
                        ], RestController::HTTP_CREATED);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Gagal update post'
                        ], RestController::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Post tidak ditemukan'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Upload gambar gagal'
                ], RestController::HTTP_BAD_REQUEST);
            }
        } else if ($flag == 'UPDATE_NO_PHOTO') {
            $postId = $this->post('id');

            $data = [
                'user_id' => $this->post('user_id'),
                'kategori' => $this->post('kategori'),
                'judul' => $this->post('judul'),
                'isi' => $this->post('isi')
            ];

            if ($postId) {
                if ($this->post->updatePostNoPhoto($data, $postId) > 0) {
                    $this->response([
                        'status' => true,
                        'message' => 'Update post berhasil'
                    ], RestController::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal update post'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Post tidak ditemukan'
                ], RestController::HTTP_BAD_REQUEST);
            }
        } else if ($flag == 'DELETE') {
            $postId = $this->post('id');

            if ($postId === null) {
                $this->response([
                    'status' => false,
                    'message' => 'Coba lagi'
                ], RestController::HTTP_BAD_REQUEST);
            } else {
                $postImageName = $this->post->getPostImage($postId);
                if ($this->post->deletePost($postId) > 0) {
                    //jika ada affected row
                    unlink(FCPATH . 'assets/img/post/' . $postImageName);
                    $this->response([
                        'status' => true,
                        'message' => 'Post Berhasil Dihapus'
                    ], RestController::HTTP_OK);
                } else {
                    //not found
                    $this->response([
                        'status' => false,
                        'message' => 'Pilih post yang ingin dihapus'
                    ], RestController::HTTP_BAD_REQUEST);
                }
            }
        }
    }
}
