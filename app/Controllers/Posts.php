<?php

namespace App\Controllers;

use App\Models\PostsModel;
use Exception;

class Posts extends BaseController
{
    public function index()
    {
        $results = $this->getRequest($this->request);
        $posts = new PostsModel();

        $results["page"] = $results["page"] ?? 0;
        echo $posts->getAll($results["page"]);
    }

    public function likePost()
    {
        $input = $this->getRequestInput($this->request);


        if (!$input || $input["pId"] <= 0) :
            return $this->getResponse(['error' => 'Oops, está faltando alguns campos.'], ResponseInterface::HTTP_BAD_REQUEST);
        endif;


        $model = new PostsModel($input["pId"]);

        return json_encode([
            "success" => $model->postLike(),
            "likes" => $model->getPostLikes()
        ]);
    }
}
