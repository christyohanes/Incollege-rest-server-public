<?php

class UserDVM_model extends CI_Model
{
    public function updateUserDVM($data, $id)
    {
        $userArray = $this->db->get_where('users', ['id' => $id])->row_array();
        $userId = $userArray['id'];

        $user = $this->db->get_where('users_detail', ['user_id' => $userId])->row_array();
        $data += array('user_id' => $user['user_id'], 'photo' => $user['photo']);
        
        $this->db->update('users_detail', $data, ['user_id' => $id]);
        return $this->db->affected_rows();
    }
}
