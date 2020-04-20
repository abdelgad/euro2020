<?php


class Match
{
    private $NumMatch;
    private $RefEquipe1;
    private $RefEquipe2;
    private $ScoreEquipe1;
    private $ScoreEquipe2;

    /**
     * Match constructor.
     * @param $NumMatch
     * @param $RefEquipe1
     * @param $RefEquipe2
     * @param $ScoreEquipe1
     * @param $ScoreEquipe2
     */
    public function __construct($NumMatch = null, $RefEquipe1 = null, $RefEquipe2 = null, $ScoreEquipe1 = null, $ScoreEquipe2 = null)
    {
        if($NumMatch != NULL) $this->NumMatch = $NumMatch;
        if($RefEquipe1 != NULL) $this->RefEquipe1 = $RefEquipe1;
        if($RefEquipe2 != NULL) $this->RefEquipe2 = $RefEquipe2;
        if($ScoreEquipe1 != NULL) $this->ScoreEquipe1 = $ScoreEquipe1;
        if($ScoreEquipe2 != NULL) $this->ScoreEquipe2 = $ScoreEquipe2;
    }

    /**
     * @return null
     */
    public function getNumMatch()
    {
        return $this->NumMatch;
    }

    /**
     * @param null $NumMatch
     */
    public function setNumMatch($NumMatch): void
    {
        $this->NumMatch = $NumMatch;
    }

    /**
     * @return null
     */
    public function getRefEquipe1()
    {
        return $this->RefEquipe1;
    }

    /**
     * @param null $RefEquipe1
     */
    public function setRefEquipe1($RefEquipe1): void
    {
        $this->RefEquipe1 = $RefEquipe1;
    }

    /**
     * @return null
     */
    public function getRefEquipe2()
    {
        return $this->RefEquipe2;
    }

    /**
     * @param null $RefEquipe2
     */
    public function setRefEquipe2($RefEquipe2): void
    {
        $this->RefEquipe2 = $RefEquipe2;
    }

    /**
     * @return null
     */
    public function getScoreEquipe1()
    {
        return $this->ScoreEquipe1;
    }

    /**
     * @param null $ScoreEquipe1
     */
    public function setScoreEquipe1($ScoreEquipe1): void
    {
        $this->ScoreEquipe1 = $ScoreEquipe1;
    }

    /**
     * @return null
     */
    public function getScoreEquipe2()
    {
        return $this->ScoreEquipe2;
    }

    /**
     * @param null $ScoreEquipe2
     */
    public function setScoreEquipe2($ScoreEquipe2): void
    {
        $this->ScoreEquipe2 = $ScoreEquipe2;
    }





}