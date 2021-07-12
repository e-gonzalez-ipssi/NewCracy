<?php 

namespace App\Manager;

use Exception;

Trait LikeManagerTrait {

    /**
     * Permet de récupéré le nombre de like
     */
    private function getLikeNumber(int $propositionId): int {
        $requete = "SELECT * FROM `PostLike` WHERE vote = 1 and id_Post = $propositionId";
        $this->setQuery($requete);
        $result = $this->querySelect();

        return count($result);
    }

    /**
     * Permet de récupéré la liste de like
     */
    public function getLike(int $propositionId): array {
        $requete = "SELECT * FROM `PostLike` WHERE vote = 1 and id_Post = $propositionId";
        $this->setQuery($requete);
        $result = $this->querySelect();

        return $result;
    }

    /**
     * Permet de récupéré le nombre de dislike
     */
    private function getDislikeNumber(int $propositionId): int {
        $requete = "SELECT * FROM `PostLike` WHERE vote = 0 and id_Post = $propositionId";
        $this->setQuery($requete);
        $result = $this->querySelect();

        return count($result);
    }

    /**
     * Permet de récupéré la liste de like
     */
    public function getDislike(int $propositionId): array {
        $requete = "SELECT * FROM `PostLike` WHERE vote = 0 and id_Post = $propositionId";
        $this->setQuery($requete);
        $result = $this->querySelect();

        return $result;
    }

    public function likeProposition(int $propositionId, int $authorid): void {
        $requete = "INSERT INTO `PostLike` (`id_Post`, `id_Users`, `vote`) VALUES ('$propositionId', '$authorid', '1')";
        $this->setQuery($requete);
        $this->querySet();
    }

    public function dislikeProposition(int $propositionId, int $authorid): void {
        $requete = "INSERT INTO `PostLike` (`id_Post`, `id_Users`, `vote`) VALUES ('$propositionId', '$authorid', '0')";
        $this->setQuery($requete);
        $this->querySet();
    }

    public function removeLikeProposition(int $propositionId, int $authorid): void {
        $requete = "DELETE FROM `PostLike` WHERE `id_Post` = $propositionId and `id_Users` = $authorid";
        $this->setQuery($requete);
        $this->querySet();
    }
}
