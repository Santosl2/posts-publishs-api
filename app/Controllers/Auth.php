<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Auth extends BaseController
{

    public function register()
    {
        $rules = [
            'username' => 'required|min_length[6]|max_length[24]|is_unique[users.username]',
            'email' => 'required|min_length[6]|max_length[100]|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]|max_length[72]'
        ];

        $input = $this->getRequestInput($this->request);

        if (!$this->validateRequest($input, $rules)) {
            return $this->getResponse(
                $this->validator->getErrors(),
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        if ($this->session->token) {
            return $this->getResponse(['NOT_FOUND' => 'Oops'], ResponseInterface::HTTP_NOT_FOUND);
        }

        $userModel = new UserModel();
        $userModel->save($input);


        return $this->getJWT(
            $input['email'],
            ResponseInterface::HTTP_CREATED
        );
    }

    /**
     * Login user
     * @return Response
     * @throws Exception
     */

    public function login()
    {

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|validateUser[username, password]'
        ];

        $errors = [
            'password' => [
                'validateUser' => "Oops, informações de login inválidas."
            ]
        ];

        $input = $this->getRequestInput($this->request);

        if (!$input) {
            return $this->getResponse(['MISSING_FIELDS' => 'Oops, está faltando alguns campos.'], ResponseInterface::HTTP_BAD_REQUEST);
        }

        if (!$this->validateRequest($input, $rules, $errors)) {
            return $this->getResponse(
                $this->validator->getErrors(),
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        if ($this->session->token) {
            return $this->getResponse(['NOT_FOUND' => 'Oops'], ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->getJWT($input['email']);
    }


    private function getJWT(string $email, int $responseCode = ResponseInterface::HTTP_OK)
    {
        try {
            $model = new UserModel();
            $user = $model->getUserByEmail($email);
            unset($user['password']);
            unset($user['id']);
            unset($user['created_at']);

            helper('jwt');

            $token = getSignedJWTForUser($email);
            $this->session->set('token', $token);
            return $this
                ->getResponse(
                    [
                        'message' => 'Sucesso!',
                        'user' => $user,
                        'access_token' => $token
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
}