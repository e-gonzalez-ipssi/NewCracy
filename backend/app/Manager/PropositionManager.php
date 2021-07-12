<?php 

namespace App\Manager;

use App\Entity\bd ;
use App\Entity\User;
use App\Entity\Post;
use App\Service\UserService;
use Exception;

class PropositionManager extends Manager {

    use TagManagerTrait;
    use LikeManagerTrait;
    use ReportManagerTrait;

    protected UserService $userService;

    public function __construct(bd $bd, UserService $userService)
    {
        parent::__construct($bd);

        $this->userService = $userService;
    }

    /**
     * Cette fonction permet de récupéré une proposition exploitable en donnant son id
     */
    public function getPropositionById(int $id): Post {
        $requete = "SELECT * FROM `Post` WHERE id = $id";
        $this->setQuery($requete);
        $result = $this->querySelect();

        if (count($result) < 1) {
            throw new Exception("proposition-not-founds");
        }

        if (count($result) > 1) {
            throw new Exception("error-in-request");
        }

        $author = $this->getAuthor($id);

        $tags = $this->getTags($id);

        $like = $this->getLikeNumber($id);
        $dislike = $this->getDislikeNumber($id);

        $propositions = $this->fromQueryToPropositions($result, $author, $tags, $like, $dislike);

        return $propositions[0];
    }

    /**
     * Cette fonction permet de récupéré une proposition exploitable en donnant son id
     */
    public function getPropositionByOrgId(int $orgId): array {
        $requete = "SELECT id_Post FROM `OrgPost` WHERE id_Organization = $orgId";
        $this->setQuery($requete);
        $result = $this->querySelect();

        $propositions = [];

        foreach ($result as $propositionId) {
            array_push($propositions, $this->getPropositionById($propositionId["id_Post"]));
        }

        return $propositions;
    }

    /**
     * Permet de créer une nouvelle proposition
     */
    public function createProposition(int $orgId, int $authorId, string $title, string $description): int{
        $timestamp = time();
        $requete = "INSERT INTO `Post` (`title`, `content`, `date`) VALUES ('$title', '$description', '$timestamp')";
        $this->setQuery($requete);
        $this->querySet();

        $requete = "SELECT id FROM `Post` WHERE date = $timestamp";
        $this->setQuery($requete);
        $result = $this->querySelect();

        $propositionId = $result[0]['id'];

        $requete = "INSERT INTO `OrgPost` (`id_Post`, `id_Organization`) VALUES ($propositionId, $orgId)";
        $this->setQuery($requete);
        $this->querySet();

        $requete = "INSERT INTO `UsersPost` (`id_Post`, `id_Users`) VALUES ($propositionId, $authorId);";
        $this->setQuery($requete);
        $this->querySet();

        return $propositionId;
    }

    /**
     * Fonction permettant de récupéré l'autheur d'une proposition
     */
    private function getAuthor(int $propositionId): User {
        $requete = "SELECT id_Users FROM `UsersPost` WHERE id_Post = $propositionId";
        $this->setQuery($requete);
        $result = $this->querySelect();

        if(count($result) < 1) {
            throw new Exception("no-author-to-proposition");
        }

        if(count($result) > 1) {
            throw new Exception("to-many-author-to-message");
        }

        return $this->userService->getUserById($result[0]["id_Users"]);
    }
}
