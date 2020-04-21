<?php


//function update_team_stats(Match $match)
//{
//    $query = "SELECT * FROM Equipes WHERE RefEquipe IN (SELECT RefEquipe1 FROM Matchs WHERE NumMatch = ?)";
//    $prepQuery = $connection->prepare($query);
//    $prepQuery->bindValue('team1', $team1, PDO::PARAM_STR);
//}

//GENERATE MATCHS BUTTON PRESSED
if(isset($_POST['generateMatchsButton']))
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
            if($key2 <= $key1)
            {
                continue;
            }
            else
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
if(isset($_POST['enterScoreButton']) && isset($_POST['scoreTeam1']) && isset($_POST['scoreTeam2']))
{
    if(!empty($_POST['scoreTeam1']) && !empty($_POST['scoreTeam2']))
    {
        $match = new Match();
        $match->setNumMatch($_POST['numMatch']);
        $match->setScoreEquipe1($_POST['scoreTeam1']);
        $match->setScoreEquipe2($_POST['scoreTeam2']);

        $query = "UPDATE Matchs SET ScoreEquipe1 = :scoreTeam1, ScoreEquipe2 = :scoreTeam2 WHERE NumMatch = :numMatch;";
        $prepQuery = $connection->prepare($query);
        $prepQuery->bindValue('scoreTeam1', $match->getScoreEquipe1(), PDO::PARAM_INT);
        $prepQuery->bindValue('scoreTeam2', $match->getScoreEquipe2(), PDO::PARAM_INT);
        $prepQuery->bindValue('numMatch', $match->getNumMatch(), PDO::PARAM_INT);
        $prepQuery->execute();

//        update_team_stats($match);
    }
    else
    {
        //TODO: Afficher message remplir tous les champs
    }
}