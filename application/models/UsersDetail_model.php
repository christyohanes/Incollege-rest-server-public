<?php

class UsersDetail_model extends CI_Model
{
    public function getUserDetail($id)
    {
        $userArray = $this->db->get_where('users', ['id' => $id])->row_array();
        if ($userArray) {
            $userId = $userArray['id'];
            return $this->db->get_where('users_detail', ['user_id' => $userId])->row_array();
        } else {
            return $this->db->get_where('users', ['id' => $id])->row_array();
        }
    }

    public function updateUserDetail($data, $id)
    {
        $this->db->update('users_detail', $data, ['user_id' => $id]);
        return $this->db->affected_rows();
    }
    
}
