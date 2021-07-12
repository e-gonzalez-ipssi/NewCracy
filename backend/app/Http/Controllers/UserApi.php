<?php

namespace App\Http\Controllers;

use App\Entity\User;
use Illuminate\Http\Request;

class UserApi extends Api
{
    private const NO_RIGHT = 1;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fonction qui permet d'initialiser la requete
     *  - initilise les paramêtres requis ou non pour faire fonctionner l'api
     *  - fait les checks de permissions d'utilisation
     *  - vérifie si l'utilisateur a besoin d'être connecté ou pas
     */
    private function initialize(
        array $params = [],
        int $right = self::NO_RIGHT,
        bool $isConnected = true
    ): array {
        if ($isConnected) {
            $this->me = $this->connexionService->getCurrentUser();
        }

        $this->checkAccess($right, $this->me);

        $paramsClean = $this->getParams($params);

        return $paramsClean;
    }

    /**
     * Permet de faire des différents check de permission
     * - créer un nouveau case par nouveau type de permission
     */
    private function checkAccess(int $right, ?User $me) {
        switch ($right) {
            case self::NO_RIGHT:
            default:
                return;
        }
    }

    /**
     * @route get(api/user/{id})
     * 
     * @param int $id l'id de l'utilisateur que l'on recherche
     * 
     * @return  mixed les informations de l'utilisateur au format JSON
     */
    public function getUser(int $id) {
        $params = $this->initialize([], self::NO_RIGHT, false);

        $user = $this->userService->getUserById($id);
        return $this->returnOutput($user->arrayify());
    }

    /**
     * @route get(api/me)
     * 
     * @return mixed les informations de l'utilisateur au format JSON
     */
    public function getMe() {
        $this->initialize([], self::NO_RIGHT, true);

        return $this->returnOutput($this->me->arrayify());
    }

    /**
     * @route post(api/connect/)
     * 
     * 
     * @return  mixed les informations de l'utilisateur au format JSON
     */
    public function connect(Request $request) {
        $params = $this->initialize(
            [
                ["mail", REQUIRED, TYPE_MAIL, $request->input('mail')],
                ["password", REQUIRED, TYPE_PASSWORD, $request->input('password')],
            ],
            self::NO_RIGHT,
            false,
        );

        $this->connexionService->connexion($params['mail'], $params['password']);
        return $this->returnOutput($this->ack());
    }

    /**
     * @route post(api/disconnect/)
     * 
     * 
     * @return  mixed les informations de l'utilisateur au format JSON
     */
    public function disconnect(Request $request) {
        $params = $this->initialize(
            [],
            self::NO_RIGHT,
            true,
        );
        
        $this->connexionService->deconnexion();
        return $this->returnOutput($this->ack());
    }

    /**
     * @route post(api/register/)
     * 
     * 
     * @return  mixed les informations de l'utilisateur au format JSON
     */
    public function register(Request $request) {
        $params = $this->initialize(
            [
                ["lastName", REQUIRED, TYPE_STRING, $request->input('lastName')],
                ["firstName", REQUIRED, TYPE_STRING, $request->input('firstName')],
                ["mail", REQUIRED, TYPE_MAIL, $request->input('mail')],
                ["pwd", REQUIRED, TYPE_PASSWORD, $request->input('pwd')],
                ["confirmPwd", REQUIRED, TYPE_PASSWORD, $request->input('confirmPwd')],
            ],
            self::NO_RIGHT, 
            false,
        );
        
        $this->connexionService->inscription(
            $params['lastName'],
            $params['firstName'],
            $params['pwd'],
            $params['confirmPwd'],
            $params['mail']
        );
        return $this->returnOutput($this->ack());
    }

}
