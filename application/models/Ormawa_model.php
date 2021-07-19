<?php

class Ormawa_model extends CI_Model
{
    public function getOrmawa($fakultas)
    {
        return $this->db->query("
        SELECT users_detail.user_id, users.name, users_detail.photo as user_photo, 
        users_detail.deskripsi, users_detail.visi, users_detail.misi, users.email, 
        sosial_media.instagram, sosial_media.youtube, sosial_media.email as sosial_media_email, users_scope.fakultas FROM (((users_detail 
        INNER JOIN users ON users_detail.user_id = users.id) 
        INNER JOIN sosial_media ON users_detail.user_id = sosial_media.user_id) 
        INNER JOIN users_scope ON users_detail.user_id = users_scope.user_id) 
        WHERE fakultas = $fakultas") -> result_array();
    }

    public function getOrmawaUniversitas($universitas)
    {
        return $this->db->query("
        SELECT users_detail.user_id, users.name, users_detail.photo as user_photo, 
        users_detail.deskripsi, users_detail.visi, users_detail.misi, users.email, 
        sosial_media.instagram, sosial_media.youtube, sosial_media.email as sosial_media_email, users_scope.fakultas FROM (((users_detail 
        INNER JOIN users ON users_detail.user_id = users.id) 
        INNER JOIN sosial_media ON users_detail.user_id = sosial_media.user_id)
        INNER JOIN users_scope ON users_detail.user_id = users_scope.user_id)
        WHERE fakultas = $universitas") -> result_array();
    }
    
    public function getOrmawaDetail($user_id){
        return $this->db->query("
        SELECT users_detail.user_id, users.name, users_detail.photo as user_photo, 
        users_detail.deskripsi, users_detail.visi, users_detail.misi, users.email, 
        sosial_media.instagram, sosial_media.youtube, sosial_media.email as sosial_media_email, users_scope.fakultas FROM (((users_detail 
        INNER JOIN users ON users_detail.user_id = users.id) 
        INNER JOIN sosial_media ON users_detail.user_id = sosial_media.user_id)
        INNER JOIN users_scope ON users_detail.user_id = users_scope.user_id)
        WHERE users_detail.user_id = $user_id") -> row_array();
    }
}