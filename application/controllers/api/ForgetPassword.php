<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class ForgetPassword extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ForgetPassword_model', 'fp');
    }

    public function index_put()
    {
        $user_id = $this->put('user_id');
        $newPassword = $this->put('new_password');

        $user = $this->fp->getUser($user_id);
        $userEmail = $user['email'];
        $userName = $user['name'];

        $data = [
            'email' => $userEmail,
            'password' => $newPassword,
            'name' => $userName
        ];

        if ($this->fp->updateUsers($data, $user_id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'Berhasil'
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }
}
