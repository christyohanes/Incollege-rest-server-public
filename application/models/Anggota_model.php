<?php

class Anggota_model extends CI_Model
{
    public function getAnggota($id)
    {
        return $this->db->get_where('anggota', ['user_id' => $id])->result_array();
    }

    public function getAnggotaImage($id)
    {
        $anggota = $this->db->get_where('anggota', ['id' => $id])->row_array();
        return $anggota['photo'];
    }

    public function deleteAnggota($id)
    {
       $this->db->delete('anggota', ['id'=>$id]);
       return $this->db->affected_rows();
    }

    public function createAnggota($data)
    {
        $this->db->insert('anggota', $data);
        return $this->db->affected_rows();
    }
}
