<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Search extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Post_model', 'post');
    }

    public function index_get()
    {
        $searchQuery = $this->get('query');

        $post = $this->post->getPostSearch($searchQuery);

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
                'message' => 'Post tidak ditemukan'
            ], RestController::HTTP_NOT_FOUND);
        }
    }
}
