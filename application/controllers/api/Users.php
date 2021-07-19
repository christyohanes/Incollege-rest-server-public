<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Users extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model', 'user');
    }

    public function index_get()
    {
        $email = $this->get('email');
        if ($email === null) {
            $user = $this->user->getUser();
        } else {
            $user = $this->user->getUser($email);
        }

        $user['password'] = "hayo mau tau ya?";

        if ($user) {
            $this->response([
                'status' => true,
                'data' => $user
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No users were found'
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    public function index_post()
    {
        $authData = [
            'email' => $this->post('email'),
            'password' => $this->post('password'),
        ];

        $queryResult = $this->user->loginUser($authData);

        if ($queryResult->num_rows() == 0) {
            $this->response([
                'status' => false,
                'message' => 'Pastikan email yang dimasukkan benar'
            ], RestController::HTTP_NOT_FOUND);
        } else {
            $data = $queryResult->row_array();
            if (strcmp($data['password'], $authData['password']) === 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Login berhasil',
                    'data' => [
                        'id' => $data['id'],
                        'email' => $data['email'],
                        'name' => $data['name']
                    ]
                ], RestController::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Password salah'
                ], RestController::HTTP_BAD_REQUEST);
            }
        }
    }
}
