<?php

class Team
{
    private $NomEquipe;
    private $NomFichierDrapeau;
    private $RefGroupe;
    private $nbMatchJoue = 0;
    private $nbMatchGagne = 0;
    private $nbMatchNul = 0;
    private $nbMatchPerdu = 0;
    private $nbButMarque = 0;
    private $nbButEnc = 0;
    private $nbPoints = 0;

    /**
     * Team constructor.
     * @param $NomEquipe
     * @param $NomFichierDrapeau
     * @param $RefGroupe
     * @param $nbMatchJoue
     * @param $nbMatchGagne
     * @param $nbMatchNul
     * @param $nbMatchPerdu
     * @param $nbButMarque
     * @param $nbButEnc
     * @param $nbPoints
     */
    public function __construct($NomEquipe = null, $NomFichierDrapeau = null, $RefGroupe = null, $nbMatchJoue = null, $nbMatchGagne = null, $nbMatchNul = null, $nbMatchPerdu = null, $nbButMarque = null, $nbButEnc = null, $nbPoints = null)
    {
        if ($NomEquipe != NULL) $this->NomEquipe = $NomEquipe;
        if ($NomFichierDrapeau != NULL) $this->NomFichierDrapeau = $NomFichierDrapeau;
        if ($RefGroupe != NULL) $this->RefGroupe = $RefGroupe;
        if ($nbMatchJoue != NULL) $this->nbMatchJoue = $nbMatchJoue;
        if ($nbMatchGagne != NULL) $this->nbMatchGagne = $nbMatchGagne;
        if ($nbMatchNul != NULL) $this->nbMatchNul = $nbMatchNul;
        if ($nbMatchPerdu != NULL) $this->nbMatchPerdu = $nbMatchPerdu;
        if ($nbButMarque != NULL) $this->nbButMarque = $nbButMarque;
        if ($nbButEnc != NULL) $this->nbButEnc = $nbButEnc;
        if ($nbPoints != NULL) $this->nbPoints = $nbPoints;
    }


    /**
     * Fonction pour comparer deux equipes sur base de la priorité nbPoints ==> différence de buts ==> nbButMarque
     * @param $team1 //prémiere équipe à comparer
     * @param $team2 //deuxième équipe à comparer avec la prémière
     * @return int -1 si la prèmière équipe est plus petite que la deuxième, 1 si c'est l'inverse, 0 si égaux
     */
    static function cmp_obj($team1, $team2)
    {
        if ($team1->nbPoints > $team2->nbPoints) return 1;
        elseif ($team1->nbPoints < $team2->nbPoints) return -1;
        else
        {
            if (($team1->nbButMarque - $team1->nbButEnc) > ($team2->nbButMarque - $team2->nbButEnc)) return 1;
            elseif (($team1->nbButMarque - $team1->nbButEnc) < ($team2->nbButMarque - $team2->nbButEnc)) return -1;
            else
            {
                if ($team1->nbButMarque > $team2->nbButMarque) return 1;
                elseif ($team1->nbButMarque < $team2->nbButMarque) return -1;
                else return 0;
            }
        }
    }

    function updateStats($scoreThisTeam, $scoreOppositeTeam)
    {
        $this->nbMatchJoue++;
        $this->nbButMarque += $scoreThisTeam;
        $this->nbButEnc += $scoreOppositeTeam;

        if ($scoreThisTeam > $scoreOppositeTeam)
        {
            $this->nbMatchGagne++;
            $this->nbPoints += 3;
        }
        elseif ($scoreThisTeam < $scoreOppositeTeam)
        {
            $this->nbMatchPerdu++;
        }
        else
        {
            $this->nbMatchNul++;
            $this->nbPoints += 1;
        }
    }

    /**
     * @return null
     */
    public function getNomEquipe()
    {
        return $this->NomEquipe;
    }

    /**
     * @param null $NomEquipe
     */
    public function setNomEquipe($NomEquipe): void
    {
        $this->NomEquipe = $NomEquipe;
    }

    /**
     * @return null
     */
    public function getNomFichierDrapeau()
    {
        return $this->NomFichierDrapeau;
    }

    /**
     * @param null $NomFichierDrapeau
     */
    public function setNomFichierDrapeau($NomFichierDrapeau): void
    {
        $this->NomFichierDrapeau = $NomFichierDrapeau;
    }

    /**
     * @return null
     */
    public function getRefGroupe()
    {
        return $this->RefGroupe;
    }

    /**
     * @param null $RefGroupe
     */
    public function setRefGroupe($RefGroupe): void
    {
        $this->RefGroupe = $RefGroupe;
    }

    /**
     * @return int
     */
    public function getNbMatchJoue(): int
    {
        return $this->nbMatchJoue;
    }

    /**
     * @param int $nbMatchJoue
     */
    public function setNbMatchJoue(int $nbMatchJoue): void
    {
        $this->nbMatchJoue = $nbMatchJoue;
    }

    /**
     * @return int
     */
    public function getNbMatchGagne(): int
    {
        return $this->nbMatchGagne;
    }

    /**
     * @param int $nbMatchGagne
     */
    public function setNbMatchGagne(int $nbMatchGagne): void
    {
        $this->nbMatchGagne = $nbMatchGagne;
    }

    /**
     * @return int
     */
    public function getNbMatchNul(): int
    {
        return $this->nbMatchNul;
    }

    /**
     * @param int $nbMatchNul
     */
    public function setNbMatchNul(int $nbMatchNul): void
    {
        $this->nbMatchNul = $nbMatchNul;
    }

    /**
     * @return int
     */
    public function getNbMatchPerdu(): int
    {
        return $this->nbMatchPerdu;
    }

    /**
     * @param int $nbMatchPerdu
     */
    public function setNbMatchPerdu(int $nbMatchPerdu): void
    {
        $this->nbMatchPerdu = $nbMatchPerdu;
    }

    /**
     * @return int
     */
    public function getNbButMarque(): int
    {
        return $this->nbButMarque;
    }

    /**
     * @param int $nbButMarque
     */
    public function setNbButMarque(int $nbButMarque): void
    {
        $this->nbButMarque = $nbButMarque;
    }

    /**
     * @return int
     */
    public function getNbButEnc(): int
    {
        return $this->nbButEnc;
    }

    /**
     * @param int $nbButEnc
     */
    public function setNbButEnc(int $nbButEnc): void
    {
        $this->nbButEnc = $nbButEnc;
    }

    /**
     * @return int
     */
    public function getNbPoints(): int
    {
        return $this->nbPoints;
    }

    /**
     * @param int $nbPoints
     */
    public function setNbPoints(int $nbPoints): void
    {
        $this->nbPoints = $nbPoints;
    }
}