<?php 

namespace App\Manager;

use App\Entity\Tag;
use Exception;

Trait TagManagerTrait {

    /**
     * Permet de récupéré la liste des tags d'une proposition
     */
    public function getTags(int $postId): array {
        $requete = "SELECT id_Post FROM `PostTags` WHERE id_Post = $postId";
        $this->setQuery($requete);
        $result = $this->querySelect();

        $tagsList = $this->createTags($result);

        return $tagsList;
    }

    public function getOrganisationTags(int $orgId): array {
        $requete = "SELECT id FROM `Tags` WHERE id_Organization = $orgId";
        $this->setQuery($requete);
        $result = $this->querySelect();

        $tagsList = $this->createTags($result);

        return $tagsList;
    }

    private function createTags(array $result): array{
        $tags = [];
        foreach($result as $tag) {
            $tagId = $tag["id"];

            $requete = "SELECT * FROM `Tags` WHERE id = $tagId";
            $this->setQuery($requete);
            $result = $this->querySelect();

            $newTag = new Tag(
                $result[0]["id"],
                $result[0]["nom"]
            );
            array_push($tags, $newTag);
        }
        return $tags;
    }

    public function addTagToOrganisation(int $orgId, string $tagName) {
        $requete = "INSERT INTO `Tags` (`tagsName`, `id_Organization`) VALUES ('$tagName', '$orgId')";
        $this->setQuery($requete);
        $this->querySet();

        $requete = "SELECT `id` FROM `Tags` WHERE `tagsName` = '$tagName' and `id_Organization` = '$orgId'";
        $this->setQuery($requete);
        $result = $this->querySelect();

        if(count($result) < 1) {
            throw new Exception("tag-not-created");
        }
    }

    public function getTagId(int $orgId, string $tagName): int{
        $requete = "SELECT `id` FROM `Tags` WHERE `tagsName` = '$tagName' and `id_Organization` = '$orgId'";
        $this->setQuery($requete);
        $result = $this->querySelect();

        return $result[0]['id'];
    }

    public function addTagToProposition(int $propositionId, int $tagId) {
        $requete = "INSERT INTO `PostTags` (`id_Tags`, `id_Post`) VALUES ('$tagId', '$propositionId')";
        $this->setQuery($requete);
        $this->querySet();
    }
}
