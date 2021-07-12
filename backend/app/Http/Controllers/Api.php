<?php

namespace App\Http\Controllers;

use App\Entity\User;
use App\Entity\bd;
use App\Entity\Post;
use App\Manager\OrganisationManager;
use App\Manager\PropositionManager;
use App\Manager\UserManager;
use App\Service\ConnexionService;
use App\Service\UserService;
use App\Service\OrganisationService;
use App\Service\PropositionService;
use Exception;
use Laravel\Lumen\Routing\Controller as BaseController;


include "Constant.php";

class Api extends BaseController
{
    protected ?User $me = null;
    protected UserService $userService;
    protected OrganisationService $orgService;
    protected ConnexionService $connexionService;
    protected PropositionService $propositionService;

    protected function __construct() 
    {
        // ici on instancieras TOUT les services et manager
        $bd = new bd();
        $userManager = new UserManager($bd);
        $orgManager = new OrganisationManager($bd);
        $this->userService = new UserService($userManager);
        $this->connexionService = new ConnexionService($this->userService, $userManager);
        $this->orgService = new OrganisationService($orgManager, $this->userService);
        $propositionManager = new PropositionManager($bd, $this->userService);
        $this->propositionService = new PropositionService($propositionManager, $this->orgService, $this->userService);
    }

    protected function getParams($params) {
        if(empty($params)){
            return [];
        }

        foreach ($params as $param) {
            // check si le paramêtre est REQUIRED ou non
            if ($param[1]) {
                //check sur les types
                $value = $this->checkType($param[2], $param[3]);
                if (is_null($value)) {
                    throw new Exception("param-is-required");
                }
            }
            
            if (is_null($param[3])) {
                if (isset($param[4])) {
                    $result [$param[0]] = $param[4];
                }
                else {
                    $result [$param[0]] = null;
                }
            }
            else {
                //check sur les types
                $value = $this->checkType($param[2], $param[3]);
                // on ajoute le paramêtre a la liste
                $result [$param[0]] = $value;
            }
        }

        return $result;
    }

    private function checkType(int $typeRequired, $value) {
        if ($typeRequired === TYPE_INT) {
            $value = intval($value);
            if(!is_int($value) && !is_null($value)) {
                throw new Exception("type-is-invalid");
            }
        }

        if ($typeRequired === TYPE_STRING) {
            if(!is_string($value) && !is_null($value)) {
                throw new Exception("type-is-invalid");
            }
        }

        if ($typeRequired === TYPE_INT_NOT_NEGATIF_OR_NULL) {
            $value = intval($value);
            if(!is_int($value) && !is_null($value) && $value <= 0) {
                throw new Exception("type-is-invalid");
            }
        }

        if ($typeRequired === TYPE_BOOLEAN) {
            $tinyint = (int) filter_var($value, FILTER_VALIDATE_BOOLEAN);
            if($tinyint == 1) {
                $value = true;
            }
            elseif($tinyint == 0) {
                $value = false;
            }
            else {
                throw new Exception("type-is-invalid");
            }
        }

        if ($typeRequired === TYPE_MAIL) {
            if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                throw new Exception("type-is-invalid");
            }

            if(!is_string($value) && !is_null($value)) {
                throw new Exception("type-is-invalid");
            }
        }

        if ($typeRequired === TYPE_PASSWORD) {
            // Vérifier si le mot de passe a bien une sécurité minimum (exemple : 8 characteres (8-20) , 1 chiffres, 1 majuscules)
            $regex = "((?=.*\\d)(?=.*[A-Z]).{8,20})";
            $verif_pass = strlen($value) >= 8;
            $regex_pass = preg_match($regex, $value);

            if(!$verif_pass || !$regex_pass ){
                throw new Exception("check-password-security");
            }

            if(!is_string($value) && !is_null($value)) {
                throw new Exception("type-is-invalid");
            }
        }

        return $value;
    }

    protected function returnOutput($result){
            return response()->json(
                [
                    "value" => $result,
                ]
            );
    }

    protected function ack() {
        return ["ack" => TRUE];
    }
}
