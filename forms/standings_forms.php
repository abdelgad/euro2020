<?php

//GENERATE MATCHS BUTTON PRESSED
if (isset($_POST['generateMatchsButton']))
{
    $groupLetter = $_POST['groupLetter'];
    $query = "SELECT NomEquipe FROM Equipes WHERE RefGroupe = :letter;";
    $prepQuery = $connection->prepare($query);
    $prepQuery->bindValue('letter', $groupLetter, PDO::PARAM_STR);
    $prepQuery->execute();

    $teams = $prepQuery->fetchAll(PDO::FETCH_COLUMN, 0);

    foreach ($teams as $key1 => $team1)
    {
        foreach ($teams as $key2 => $team2)
        {
            if ($key2 <= $key1)
            {
                continue;
            } else
            {
                $query = "INSERT INTO Matchs (RefEquipe1, RefEquipe2) values (:team1, :team2);";
                $prepQuery = $connection->prepare($query);
                $prepQuery->bindValue('team1', $team1, PDO::PARAM_STR);
                $prepQuery->bindValue('team2', $team2, PDO::PARAM_STR);
                $prepQuery->execute();
            }
        }
    }
}


//ENTER THE SCORE OF A MATCH SUBMITTED
if (isset($_POST['enterScoreButton']) && isset($_POST['scoreTeam1']) && isset($_POST['scoreTeam2']))
{
    if (is_numeric($_POST['scoreTeam1']) && is_numeric($_POST['scoreTeam2']))
    {
        $match = new Match();
        $match->setNumMatch($_POST['numMatch']);
        $match->setScoreEquipe1($_POST['scoreTeam1']);
        $match->setScoreEquipe2($_POST['scoreTeam2']);

        //Update the match score
        $query = "UPDATE Matchs SET ScoreEquipe1 = :scoreTeam1, ScoreEquipe2 = :scoreTeam2 WHERE NumMatch = :numMatch;";
        $prepQuery = $connection->prepare($query);
        $prepQuery->bindValue('scoreTeam1', $match->getScoreEquipe1(), PDO::PARAM_INT);
        $prepQuery->bindValue('scoreTeam2', $match->getScoreEquipe2(), PDO::PARAM_INT);
        $prepQuery->bindValue('numMatch', $match->getNumMatch(), PDO::PARAM_INT);
        $prepQuery->execute();

        //Update both teams stats
        //Update Team1 Stats
        $query = "SELECT * FROM Equipes WHERE NomEquipe IN (SELECT RefEquipe1 FROM Matchs WHERE NumMatch = :numMatch)";
        $prepQuery = $connection->prepare($query);
        $prepQuery->bindValue('numMatch', $match->getNumMatch(), PDO::PARAM_INT);
        $prepQuery->execute();
        $prepQuery->setFetchMode(PDO::FETCH_CLASS, 'Team');
        $team1 = $prepQuery->fetch();
        $team1->updateStats($match->getScoreEquipe1(), $match->getScoreEquipe2());
        $query = "UPDATE Equipes SET nbMatchJoue = :nbMatchJoue, nbMatchGagne = :nbMatchGagne, nbMatchNul = :nbMatchNul, nbMatchPerdu = :nbMatchPerdu, nbButMarque = :nbButMarque, nbButEnc = :nbButEnc, nbPoints = :nbPoints  WHERE NomEquipe = :NomEquipe";
        $prepQuery = $connection->prepare($query);
        $prepQuery->bindValue('nbMatchJoue', $team1->getnbMatchJoue());
        $prepQuery->bindValue('nbMatchGagne', $team1->getnbMatchGagne());
        $prepQuery->bindValue('nbMatchNul', $team1->getnbMatchNul());
        $prepQuery->bindValue('nbMatchPerdu', $team1->getnbMatchPerdu());
        $prepQuery->bindValue('nbButMarque', $team1->getnbButMarque());
        $prepQuery->bindValue('nbButEnc', $team1->getnbButEnc());
        $prepQuery->bindValue('nbPoints', $team1->getnbPoints());
        $prepQuery->bindValue('NomEquipe', $team1->getNomEquipe());
        $prepQuery->execute();

        //Update Team2 Stats
        $query = "SELECT * FROM Equipes WHERE NomEquipe IN (SELECT RefEquipe2 FROM Matchs WHERE NumMatch = :numMatch)";
        $prepQuery = $connection->prepare($query);
        $prepQuery->bindValue('numMatch', $match->getNumMatch(), PDO::PARAM_INT);
        $prepQuery->execute();
        $prepQuery->setFetchMode(PDO::FETCH_CLASS, 'Team');
        $team2 = $prepQuery->fetch();
        $team2->updateStats($match->getScoreEquipe2(), $match->getScoreEquipe1());
        $query = "UPDATE Equipes SET nbMatchJoue = :nbMatchJoue, nbMatchGagne = :nbMatchGagne, nbMatchNul = :nbMatchNul, nbMatchPerdu = :nbMatchPerdu, nbButMarque = :nbButMarque, nbButEnc = :nbButEnc, nbPoints = :nbPoints  WHERE NomEquipe = :NomEquipe";
        $prepQuery = $connection->prepare($query);
        $prepQuery->bindValue('nbMatchJoue', $team2->getnbMatchJoue());
        $prepQuery->bindValue('nbMatchGagne', $team2->getnbMatchGagne());
        $prepQuery->bindValue('nbMatchNul', $team2->getnbMatchNul());
        $prepQuery->bindValue('nbMatchPerdu', $team2->getnbMatchPerdu());
        $prepQuery->bindValue('nbButMarque', $team2->getnbButMarque());
        $prepQuery->bindValue('nbButEnc', $team2->getnbButEnc());
        $prepQuery->bindValue('nbPoints', $team2->getnbPoints());
        $prepQuery->bindValue('NomEquipe', $team2->getNomEquipe());
        $prepQuery->execute();
    } else
    {
        $messageForUser = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                          Veuillez remplir tous les champs du formulaire afin d\'ajouter un score
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>';
    }
}

/**
 * Fonction pour trier (par insertion) un tableau des objets de la classe Team à l'aide de la fonction cmp_obj()
 * @param $my_array //le tableau à trier
 * @return mixed //le tableau trié
 */
function insertion_Sort($my_array)
{
    for ($i = 0; $i < count($my_array); $i++)
    {
        $val = $my_array[$i];
        $j = $i - 1;
        while ($j >= 0 && Team::cmp_obj($my_array[$j], $val) < 0)
        {
            $my_array[$j + 1] = $my_array[$j];
            $j--;
        }
        $my_array[$j + 1] = $val;
    }
    return $my_array;
}