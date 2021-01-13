<?php 

include 'Manager.php';
include '../entity/Organisation.php';

class OrganisationManager extends Manager {

    /**
     * Cette fonction permet de setup la query pour créer un Organisation
     * 
     * @return string Cette fonction retourne ou un message d'erreur ou un message disant que tout c'est bien passer
     * 
     */
    public function createOrganisation(string $nom, string $description, string $lienSite , string $userName): array {
        /** @var string $newQuery */
        /** @var string $request */
        /** @var string $request2 */
        $userName = "test";
        
        $request = "SELECT * FROM `Organisation`  WHERE nom = $nom;";
        $this->setQuery($request);
        $result = $this->find();

        if(count($result) >= 1){
            throw new Exception("error-creation-organisation-failed");
        }

        $request2 = "INSERT INTO `estAdmin` (`idUtilisateur`, `idOrganisation`) VALUES (
            (SELECT idUtilisateur FROM Utilisateur WHERE nom = $userName), 
            (SELECT idOrganisation FROM Organisation WHERE nom = $nom));";
        $this->setQuery($request2);
        $this->find();

        $newQuery = "INSERT INTO `Organisation` (`nom`, `description`, `lienSite`) VALUES ($nom, $description, $lienSite);";
        $this->setQuery($newQuery);
        $this->find();

        return $this->ack("L'Organisation a bien été ajouté a la base de donnée");
    }

    /**
     * Cette fonction permet de récupéré un Organisation
     * 
     * @param int $id L'id de l'Organisation que l'on recherche
     * 
     * @return Organisation Cette fonction retourne l'Organisation rechercher
     * 
     * @throw Exception Relève une expetion si l'Organisation n'a pas été trouvé
     */
    public function getOrganisationById(int $id): Organisation {
        $newQuery = "SELECT `id`, `nom`, `description`, `lienSite` FROM `Organisation` WHERE id = $id";
        $this->setQuery($newQuery);

        $result = $this->find();

        if(count($result) < 1) {
            throw new Exception("error-Organisation-not-found");
        }

        return $this->fromQueryToOrganisation($result);
    }

    /**
     * Cette fonction permet de récupéré un Organisation
     * 
     * @param int $id L'id de l'Organisation que l'on recherche
     * 
     * @return array Cette fonction retourne la liste d'Organisation possédant le nom rechercher
     * 
     * @throw Exception Relève une expetion si l'Organisation n'a pas été trouvé
     */
    public function getOrganisationByName(string $nom): array {
        $newQuery = "SELECT `id`, `nom`, `description`, `lienSite` FROM `Organisation` WHERE nom = $nom";
        $this->setQuery($newQuery);

        $result = $this->find();

        if(count($result) < 1) {
            throw new Exception("error-organisation-not-found");
        }

        return $this->fromQueryToOrganisations($result);
    }

    
    /**
     * Cette fonction permet de supprimer une Organisation
     * 
     * @return string Cette fonction retourne ou un message d'erreur ou un message disant que tout c'est bien passer
     * 
     */
    public function deleteOrganisation(string $nom, string $userName): Organisation {
        /** @var string $newQuery */
        $userName = "test";

        $newQuery = "DELETE FROM `estAdmin`  WHERE idOrganisation = (SELECT idOrganisation FROM Organisation WHERE nom = $nom) AND 
        idUtilisateur = (SELECT IdUtilisateur FROM Utilisateur WHERE nom = $userName) ;";
        $this->setQuery($newQuery);
        $this->find();

        $request = "DELETE FROM `Organisation`  WHERE nom = $nom ;";
        $this->setQuery($request);
        $this->find();

        return $this->ack("L'Organisation a bien été supprimé a la base de donnée");
    }

    private function fromQueryToOrganisation($result): Organisation {
        return new Organisation(
            $result["id"],
            $result["nom"],
            $result["description"],
            $result["lienSite"],
        );;
    }
    private function fromQueryToOrganisations($result): array {
        $organisations = [];
        foreach($result as $organisation) {
            $newOrganisation = new Organisation(
                $organisation["id"],
                $organisation["nom"],
                $organisation["description"],
                $organisation["lienSite"],
            );
            array_push($organisations, $newOrganisation);
        }
        return $organisations;
    }
}