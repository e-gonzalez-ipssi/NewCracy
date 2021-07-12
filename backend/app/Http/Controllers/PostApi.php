<?php

namespace App\Http\Controllers;

use DateTime;
use App\Entity\User;
use Illuminate\Http\Request;
use Exception;

class PostApi extends Api
{
    private const NO_RIGHT = 1;
    private const IS_ADMIN = 2;
    private const IS_ORG_MEMBER = 3;

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

        $paramsClean = $this->getParams($params);

        if(array_key_exists('organisation', $paramsClean)) {
            $this->checkAccess($right, $paramsClean['organisation']);
        }
        else {
            $this->checkAccess($right);
        }

        return $paramsClean;
    }

    /**
     * Permet de faire des différents check de permission
     * - créer un nouveau case par nouveau type de permission
     */
    private function checkAccess(int $right, ?int $orgId = null) {
        switch ($right) {
            case self::IS_ORG_MEMBER:
                if (!$this->orgService->userIsInOrganisation($this->me, $orgId)) {
                    throw new Exception("error-permission");
                }
                break;
            case self::IS_ADMIN:
                if (!$this->me->isAdmin()) {
                    throw new Exception("error-permission");
                }
                break;
            case self::NO_RIGHT:
            default:
                return;
        }
    }

    /**
     * @route get(api/proposition/{id})
     * 
     * @param int $id l'id de l'utilisateur que l'on recherche
     */
    public function getProposition(int $id) {
        $this->initialize([], self::NO_RIGHT, false);

        $proposition = $this->propositionService->getPropositionById($id);
        return $this->returnOutput($proposition->arrayify());
    }

    /**
     * @route post(api/proposition/)
     */
    public function createProposition(Request $request) {
        $params = $this->initialize(
            [
                ["organisation", REQUIRED, TYPE_INT, $request->input('organisation')],
                ["title", REQUIRED, TYPE_STRING, $request->input('title')],
                ["content", NOT_REQUIRED, TYPE_STRING, $request->input('content'), ""],
                ["tags", NOT_REQUIRED, TYPE_STRING, $request->input('tags'), ""]
            ],
            self::IS_ORG_MEMBER
        );

        $org = $this->orgService->getOrganisationById($params['organisation']);
        $this->propositionService->createProposition($org, $this->me, $params['title'], $params['content'], $params['tags']);
        return $this->returnOutput($this->ack());
    }

    /**
     * @route get(api/proposition/{id}/tags)
     * 
     * @param int $id l'id de l'utilisateur que l'on recherche
     */
    public function getPropositionTags(int $id) {
        $this->initialize([], self::NO_RIGHT, false);

        $proposition = $this->propositionService->getPropositionById($id);

        $result = [];
        foreach($proposition->getTag() as $tag) {
            array_push($result, $tag->arrayify());
        }
        return $this->returnOutput($result);
    }

    /**
     * @route get(api/proposition/organisation/{orgId})
     * 
     * @param int $id l'id de l'utilisateur que l'on recherche
     */
    public function getPropositionByOrgId(int $orgId) {
        $this->initialize([], self::NO_RIGHT, false);

        $propositions = $this->propositionService->getPropositionByOrgId($orgId);

        $result = [];
        foreach($propositions as $proposition) {
            array_push($result, $proposition->arrayify());
        }

        return $this->returnOutput($result);
    }

    /**
     * @route post(api/proposition/{id}/like)
     * 
     * @param int $id l'id de l'utilisateur que l'on recherche
     */
    public function likeProposition(Request $request, int $id) {
        $this->initialize(
            [
                ["organisation", REQUIRED, TYPE_INT, $request->input('organisation')],
            ], self::IS_ORG_MEMBER, true);


        $proposition = $this->propositionService->getPropositionById($id);
        $this->propositionService->likeProposition($proposition, $this->me);

        return $this->returnOutput($this->ack());
    }
    
    /**
     * @route post(api/proposition/{id}/dislike)
     * 
     * @param int $id l'id de l'utilisateur que l'on recherche
     */
    public function dislikeProposition(Request $request, int $id) {
        $this->initialize(
            [
                ["organisation", REQUIRED, TYPE_INT, $request->input('organisation')],
            ], self::IS_ORG_MEMBER, true);

        $proposition = $this->propositionService->getPropositionById($id);
        $this->propositionService->dislikeProposition($proposition, $this->me);

        return $this->returnOutput($this->ack());
    }

    /**
     * @route get(api/proposition/{id}/vote)
     * 
     * @param int $id l'id de l'utilisateur que l'on recherche
     */
    public function getVote(Request $request, int $id) {
        $this->initialize(
            [
            ],  self::NO_RIGHT, true);

        $proposition = $this->propositionService->getPropositionById($id);
        $like = $proposition->getLike();
        $dislike = $proposition->getDislike();

        $likers = $this->propositionService->getLikers($proposition);
        $usersLike = [];

        /** @var User $liker */
        foreach($likers as $liker) {
            array_push($usersLike, $liker->arrayify());
        }

        $dislikers = $this->propositionService->getDislikers($proposition);
        $usersDislike = [];

        /** @var User $disliker */
        foreach($dislikers as $disliker) {
            array_push($usersDislike, $disliker->arrayify());
        }

        $return = [];

        $return["like"] = [
            "number" => $like,
            "User" => $usersLike,
        ];
        $return["dislike"] = [
            "number" => $dislike,
            "User" => $usersDislike,
        ];

        return $this->returnOutput($return);
    }

    /**
     * @route post(api/proposition/{id}/report)
     * 
     * @param int $id l'id de l'utilisateur que l'on recherche
     */
    public function reportProposition(Request $request, int $id) {
        $params = $this->initialize(
            [
                ["message", REQUIRED, TYPE_STRING, $request->input('message')],
            ],
            self::NO_RIGHT,
            true
        );

        $proposition = $this->propositionService->getPropositionById($id);

        $this->propositionService->reportProposition($proposition, $this->me, $params["message"]);

        return $this->returnOutput($this->ack());
    }

    /**
     * @route get(api/reports)
     * 
     * @param int $id l'id de l'utilisateur que l'on recherche
     */
    public function getReports(Request $request) {
        $this->initialize(
            [],
            self::IS_ADMIN,
            true
        );

        $reports = $this->propositionService->getReports();

        $return = [];

        foreach($reports as $report){
            $date = new DateTime();
            $date->format('U = Y-m-d H:i:s');
            $date->setTimestamp($report["Timestamp"]);

            $newReport = [
                "Proposition" => $report["Proposition"]->arrayify(),
                "User" => $report["User"]->arrayify(),
                "Message" => $report["Message"],
                "Date" => $date
            ];

            array_push($return, $newReport);
        }

        return $return;
    }

}
