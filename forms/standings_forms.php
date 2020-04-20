<?php


//GENERATE MATCHS BUTTON PRESSED
if(isset($_POST['generateMatchsButton']))
{
    $groupLetter = $_POST['groupLetter'];
    $query = "select NomEquipe from Equipes where RefGroupe = :letter;";
    $prepQuery = $connection->prepare($query);
    $prepQuery->bindValue('letter', $groupLetter, PDO::PARAM_STR);
    $prepQuery->execute();

    $teams = array();
    while ($ligneEquipe = $prepQuery->fetch(PDO::FETCH_ASSOC))
    {
        $teams[] = $ligneEquipe['NomEquipe'];
    }
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
        $query = "UPDATE Matchs SET ScoreEquipe1 = :scoreTeam1, ScoreEquipe2 = :scoreTeam2 WHERE NumMatch = :numMatch;";
        $prepQuery = $connection->prepare($query);
        $prepQuery->bindValue('scoreTeam1', $_POST['scoreTeam1'], PDO::PARAM_INT);
        $prepQuery->bindValue('scoreTeam2', $_POST['scoreTeam2'], PDO::PARAM_INT);
        $prepQuery->bindValue('numMatch', $_POST['numMatch'], PDO::PARAM_INT);
        $prepQuery->execute();
    }
    else
    {
        //TODO: Afficher message remplir tous les champs
    }
}