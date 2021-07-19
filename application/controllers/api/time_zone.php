<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class time_zone extends RestController
{
    public function index_get()
    {
        $date_now = date("Y-m-d H:i:s");
        $this->response([
            'status' => true,
            'message' => 'Pengambilan data berhasil',
            'data' => $date_now
        ], RestController::HTTP_OK);
    }
}