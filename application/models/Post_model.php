<?php

class Post_model extends CI_Model
{
    public function getPostByUserId($user_id = null)
    {
        if ($user_id === null) {
            return $this->db->query("
            SELECT post.id, post.user_id, post.kategori, post.judul, post.isi, post.waktu_post, post.photo as poster, users.name, users_detail.photo as user_photo FROM ((post 
            INNER JOIN users ON post.user_id = users.id) 
            INNER JOIN users_detail ON post.user_id = users_detail.user_id)
            ORDER BY waktu_post DESC") -> result_array();
        } else {
            return $this->db->query("
            SELECT post.id, post.user_id, post.kategori, post.judul, post.isi, post.waktu_post, post.photo as poster, users.name, users_detail.photo as user_photo FROM ((post 
            INNER JOIN users ON post.user_id = users.id) 
            INNER JOIN users_detail ON post.user_id = users_detail.user_id)
            WHERE post.user_id = $user_id
            ORDER BY waktu_post DESC") -> result_array();
        }
    }

    public function getPostByKategoriUserId($user_id, $kategori)
    {
        return $this->db->query("
        SELECT post.id, post.user_id, post.kategori, post.judul, post.isi, post.waktu_post, post.photo as poster, users.name, users_detail.photo as user_photo FROM ((post 
        INNER JOIN users ON post.user_id = users.id) 
        INNER JOIN users_detail ON post.user_id = users_detail.user_id)
        WHERE post.user_id = $user_id 
        AND kategori=$kategori
        ORDER BY waktu_post DESC") -> result_array();
    }

    public function getPostByKategori($kategori)
    {
        return $this->db->query("
        SELECT post.id, post.user_id, post.kategori, post.judul, post.isi, post.waktu_post, post.photo as poster, users.name, users_detail.photo as user_photo FROM ((post 
        INNER JOIN users ON post.user_id = users.id) 
        INNER JOIN users_detail ON post.user_id = users_detail.user_id) 
        WHERE kategori = $kategori
        ORDER BY waktu_post DESC") -> result_array();
    }

    public function createPost($data)
    {
        $this->db->insert('post', $data);
        return $this->db->affected_rows();
    }

    public function updatePost($data, $postId)
    {
        $this->db->update('post', $data, ['id' => $postId]);
        return $this->db->affected_rows();
    }

    public function updatePostNoPhoto($data, $postId)
    {
        $post = $this->db->get_where('post', ['id' => $postId])-> row_array();
        $imagePost = $post['photo'];

        $data += ['photo' => $imagePost];

        $this->db->update('post', $data, ['id' => $postId]);
        return $this->db->affected_rows();
    }

    public function getPostSearch($searchQuery)
    {
        $kategori = null;

        if($searchQuery == 'lomba'){
            $kategori = 1;
        } else if($searchQuery == 'recruitment'){
            $kategori = 2;
        } else if($searchQuery == 'workshop'){
            $kategori = 3;
        }

        if($kategori == null){
            return $this->db->query("
            SELECT post.id, post.user_id, post.kategori, post.judul, post.isi, post.waktu_post, post.photo as poster, users.name, users_detail.photo as user_photo FROM ((post 
            INNER JOIN users ON post.user_id = users.id) 
            INNER JOIN users_detail ON post.user_id = users_detail.user_id) 
            WHERE judul LIKE '%$searchQuery%'
            OR isi LIKE '%$searchQuery%'
            OR name LIKE '%$searchQuery%'
            ORDER BY waktu_post DESC") -> result_array();
        } else {
            return $this->db->query("
            SELECT post.id, post.user_id, post.kategori, post.judul, post.isi, post.waktu_post, post.photo as poster, users.name, users_detail.photo as user_photo FROM ((post 
            INNER JOIN users ON post.user_id = users.id) 
            INNER JOIN users_detail ON post.user_id = users_detail.user_id) 
            WHERE kategori = $kategori
            OR judul LIKE '%$searchQuery%'
            OR isi LIKE '%$searchQuery%'
            OR name LIKE '%$searchQuery%'
            ORDER BY waktu_post DESC") -> result_array();
        }
    }

    public function getPostImage($postId)
    {
        $post = $this->db->get_where('post', ['id' => $postId])->row_array();
        return $post['photo'];
    }

    public function deletePost($postId)
    {
        $this->db->delete('post', ['id' => $postId]);
        return $this->db->affected_rows();
    }
}