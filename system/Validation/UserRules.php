<?php

namespace CodeIgniter\Validation;

use App\Models\UserModel;
use Exception;

class UserRules
{
    public function validateUser(string $str, string $fields, array $data): bool
    {
        try {
            $model = new UserModel();
            $user = $model->getUserByEmail($data['email']);
            return password_verify($data['password'], $user['password']);
        } catch (Exception $e) {
            return false;
        }
    }

    public function verifyIsEqual(string $str, string $inputs, array $data)
    {
        $fields = explode(',', str_replace(" ", "", $inputs));


        if ($data[$fields[0]] != $data[$fields[1]]) :
            return false;
        endif;


        return true;
    }
}
