<?php

class SosialMedia_model extends CI_Model
{
    public function updateSosialMedia($data, $user_id)
    {
        $this->db->update('sosial_media', $data, ['user_id' => $user_id]);
        return $this->db->affected_rows();
    }

    public function getSosialMedia($user_id)
    {
        return $this->db->get_where('sosial_media', ['user_id' => $user_id]) -> row_array();
    }
}