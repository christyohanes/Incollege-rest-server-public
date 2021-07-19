<?php

class ForgetPassword_model extends CI_Model
{
    public function getUser($user_id){
        return $this->db->get_where('users', ['id' => $user_id])->row_array();
    }

    public function updateUsers($data, $user_id)
    {
        $this->db->update('users', $data, ['id' => $user_id]);
        return $this->db->affected_rows();
    }
}