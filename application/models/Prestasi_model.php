<?php

class Prestasi_model extends CI_Model
{
    public function createPrestasi($data)
    {
        $this->db->insert('prestasi', $data);
        return $this->db->affected_rows();
    }

    public function getPrestasi($userId)
    {
        return $this->db->query("SELECT * FROM prestasi WHERE user_id = $userId ORDER BY id DESC") -> result_array();
    }
}