<?php
namespace App\Models;

use Config\Database;
use CodeIgniter\Model;
use Exception;

class PostsModel extends Model
{
    public function __construct()
    {
        $this->adapter = Database::connect(); 
    }

    public function getAll(){

        $query = $this->adapter->query("SELECT * FROM posts");
        return $query->getResultArray();
    }
}
