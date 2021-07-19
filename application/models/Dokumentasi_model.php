<?php

class Dokumentasi_model extends CI_Model
{
    public function getUserDokumentasi($userId)
    {
        return $this->db->get_where('dokumentasi', ['user_id' => $userId])->result_array();
    }

    public function getDokumentasiImage($id)
    {
        $dokumentasi = $this->db->get_where('dokumentasi', ['id' => $id])->row_array();
        return $dokumentasi['photo'];
    }

    public function deletePengurus($id)
    {
        $this->db->delete('dokumentasi', ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function createDokumentasi($data)
    {
        $this->db->insert('dokumentasi', $data);
        return $this->db->affected_rows();
    }
}
