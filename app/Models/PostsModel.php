<?php
namespace App\Models;

use Config\Database;
use CodeIgniter\Model;
use Exception;

class PostsModel extends Model
{
    public function __construct(int $id = 0)
    {
        if($id > 0) $this->postId = $id;
        
        $this->adapter = Database::connect(); 

        helper('jwt');
        $encodedToken = getJWTFromRequest($_SERVER['HTTP_AUTHORIZATION']);
        $this->user = validateJWTFromRequest($encodedToken);
        
        
    }

    /**
     * Get all posts
     */
    public function getAll(){
        $query = $this->adapter->query("SELECT p.id, p.content,
         u.username, u.profileImg FROM posts p 
        JOIN users u ON p.publishedBy = u.id");

        return json_encode($query->getResultArray());
    }
/**
 * verify posts exists
 */
    private function postsExists(){
        
        $query = $this->adapter->query("SELECT COUNT(*) as numRows 
        FROM posts WHERE id = $this->postId");
       
        $row = $query->getRow(1);
        
       return intval($row->numRows) > 0;
        
    }
    /**
     * Verify users liked the post
     */
    private function verifyLiked(){
        $uId = $this->user['id'];
        if($uId <= 0 || !$this->postsExists($this->postId)) return;

        $query = $this->adapter->query("SELECT COUNT(*) as numRows FROM posts_likes
        WHERE userId = $uId AND postId = $this->postId GROUP BY postId");

        $row = $query->getRow(1);

        return intval($row->numRows) > 0;
    }

    /**
     * Like and Unlike post
     */

    public function postLike(){
        $uId = $this->user['id'];
        $pId = $this->postId;
        
        if($this->verifyLiked()){

            $this->adapter->query("DELETE FROM posts_likes
             WHERE userId = $uId AND postId = $pId");

             return false;
        }

        $this->adapter->query("INSERT INTO posts_likes (postId, userId) 
        VALUES ($pId, $uId)");

        return true;
    }
}
