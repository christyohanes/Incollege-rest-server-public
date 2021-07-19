<?php

class Pengurus_model extends CI_Model
{
    public function getPengurus($id, $tj = null)
    {
        if($tj === null){
            return $this->db->get_where('pengurus', ['user_id' => $id])->result_array();
        } else {
            return $this->db->get_where('pengurus', ['user_id' => $id, 'tipe_jabatan' => $tj])->result_array();
        }
    }

    public function deletePengurus($id)
    {
       $this->db->delete('pengurus', ['id'=>$id]);
       return $this->db->affected_rows();
    }

    public function getPengurusImage($id)
    {
        $pengurus = $this->db->get_where('pengurus', ['id' => $id])->row_array();
        return $pengurus['photo'];
    }

    public function createPengurus($data)
    {
        $this->db->insert('pengurus', $data);
        return $this->db->affected_rows();
    }
}
