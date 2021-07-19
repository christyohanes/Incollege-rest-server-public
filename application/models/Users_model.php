<?php

class Users_model extends CI_Model
{
    public function getUser($email = null)
    {
        if ($email === null) {
            return $this->db->get('users')->result_array();
        } else {
            return $this->db->get_where('users', ['email' => $email])->row_array();
        }
    }

    public function loginUser($authData)
    {
        return $this->db->get_where('users', ['email' => $authData['email']]);
    }

    public function getUserFP($email)
    {
        return $this->db->get_where('users', ['email' => $email])->row_array();
    }
}
