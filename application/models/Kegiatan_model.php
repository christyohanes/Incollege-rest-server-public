<?php

class Kegiatan_model extends CI_Model
{
    public function createKegiatan($data)
    {
        $this->db->insert('kegiatan', $data);
        return $this->db->affected_rows();
    }

    public function getKegiatan($userId)
    {
        return $this->db->query("SELECT * FROM kegiatan WHERE user_id = $userId ORDER BY id DESC") -> result_array();
    }

    public function getKegiatanImage($id)
    {
        $kegiatan = $this->db->get_where('kegiatan', ['id' => $id])->row_array();
        return $kegiatan['photo'];
    }

    public function deleteKegiatan($id)
    {
        $this->db->delete('kegiatan', ['id' => $id]);
        return $this->db->affected_rows();
    }

}