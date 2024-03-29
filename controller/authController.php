<?php
namespace CONTROLLER;

/**
 * Class AuthController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * The AuthController is the endpoint used for API authentication.
 */
class AuthController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    /**
     * AuthController constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * authorize() is an endpoint that prints out an API key if the authorization is successful
     * @param string $username
     * @param string $password
     */
    public function authorize(string $username, string $password) {
        $this->setRequestMethodLevel(1);

        try {
            /**
             * @var \MODEL\AuthModel $model
             */
            $model = $this->getModel("AuthModel");

            $isAuthenticated = $model->authenticateUser($username, $password);

            $token = NULL;

            if(!$isAuthenticated){
                $this->exitResponse(403, "Username and password does not match a registered user");
            }

            $token = $model->createToken($username);

            exit(json_encode(["token" => $token, "status" => true]));

        } catch (\Exception $exception) {
            exit($exception);
        }
    }

    /**
     * @param string $token
     */
    public function validate(string $token) {
        $this->setRequestMethodLevel();

        try {
            /**
             * @var \MODEL\AuthModel $model
             */
            $model = $this->getModel("AuthModel");

            $token = $model->validateToken($token);

            exit(json_encode(["valid" => $token, "status" => true]));

        } catch (\Exception $exception) {
            exit($exception);
        }
    }

}