<?php


class Team
{
    private $NomEquipe;
    private $NomFichierDrapeau;
    private $RefGroupe;
    private $nbMatchJoue;
    private $nbMatchGagne;
    private $nbMatchNul;
    private $nbMatchPerdu;
    private $nbButMarque;
    private $nbButEnc;
    private $nbPoints;

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
    public function __construct($NomEquipe = null, $NomFichierDrapeau = null, $RefGroupe = null, $nbMatchJoue = 0, $nbMatchGagne = 0, $nbMatchNul = 0, $nbMatchPerdu = 0, $nbButMarque = 0, $nbButEnc = 0, $nbPoints = 0)
    {
        if($NomEquipe != NULL) $this->NomEquipe = $NomEquipe;
        if($NomFichierDrapeau != NULL) $this->NomFichierDrapeau = $NomFichierDrapeau;
        if($RefGroupe != NULL) $this->RefGroupe = $RefGroupe;
        $this->nbMatchJoue = $nbMatchJoue;
        $this->nbMatchGagne = $nbMatchGagne;
        $this->nbMatchNul = $nbMatchNul;
        $this->nbMatchPerdu = $nbMatchPerdu;
        $this->nbButMarque = $nbButMarque;
        $this->nbButEnc = $nbButEnc;
        $this->nbPoints = $nbPoints;
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