<?php
namespace App\Controllers;

use App\Models\PostsModel;
use Exception;
class Posts extends BaseController
{
    public function index(){
        $posts = new PostsModel();

        var_dump($posts->getAll());
    }
}
