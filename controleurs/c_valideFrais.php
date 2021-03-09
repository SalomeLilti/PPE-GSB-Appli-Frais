<?php

/** 
 * Affichage du visiteur, du mois selectionne et des frais
 * 
 * @category  PPE
 * @package   GSB
 * @author   Salomé LILTI
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$mois= getMois(date('d/m/Y'));
$etat='VA';
$nbJustificatifs=0;
switch ($action) {
case 'choisirVisiteurMois':
    $visiteurs= $pdo->getChoisirVisiteur(); //je crée une fonction qui permettra au comptable de choisir un visiteur
    $lesClesV = array_keys($visiteurs);
    $visiteurASelectionner = $lesClesV[0]; //0 veut dire que par defaut la premiere sera selectionnée
    $lesMois=getLes12DerniersMois($mois); //je cree une fonction qui permettra de choisir un mois parmi les 12 derniers
    $lesCles = array_keys($lesMois);
    $moisASelectionner = $lesCles[0];
    include 'vues/v_choisirVisiteurMois.php';
    break;
case 'voirValideFrais':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $visiteurs= $pdo->getChoisirVisiteur();
    $lesClesV = array_keys($visiteurs);
    $visiteurASelectionner = $idVisiteur;
    $lstMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois=getLes12DerniersMois($mois); 
    $moisASelectionner = $lstMois;
    $lesFraisForfait=$pdo->getLesFraisForfait($idVisiteur,$lstMois); //Je declare des nvlles variables où je mets ces fonctions pr afficher la fiche de frais
    $lesFraisHorsForfait=$pdo->getLesFraisHorsForfait($idVisiteur,$lstMois);
    $infosFicheFrais=$pdo->getLesInfosFicheFrais($idVisiteur, $lstMois);
    if (!is_array($infosFicheFrais)){
        ajouterErreur('Pas de fiche de frais pour ce mois');
        include 'vues/v_erreurs.php';
        include 'vues/v_choisirVisiteurMois.php';
    }else{
        include 'vues/v_valideFrais.php';
    }  
    break;
case 'corrigerFraisForfait':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $visiteurs= $pdo->getChoisirVisiteur();
    $visiteurASelectionner = $idVisiteur;
    $lstMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois=getLes12DerniersMois($mois); 
    $moisASelectionner = $lstMois;
    $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    if (lesQteFraisValides($lesFrais)){
        $pdo->majFraisForfait($idVisiteur, $lstMois, $lesFrais);
        ajouterErreur('La modification a bien été prise en compte');
            include 'vues/v_erreurs.php';
    }else{
        ajouterErreur('Les valeurs des frais doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    $lesFraisForfait=$pdo->getLesFraisForfait($idVisiteur,$lstMois);
    $lesFraisHorsForfait=$pdo->getLesFraisHorsForfait($idVisiteur,$lstMois);
    include 'vues/v_valideFrais.php';
    break;
case 'corrigerFraisHorsForfait':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $visiteurs= $pdo->getChoisirVisiteur();
    $visiteurASelectionner = $idVisiteur;
    $lstMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois=getLes12DerniersMois($mois); 
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $moisASelectionner = $lstMois;
    $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
    $montant = filter_input(INPUT_POST, 'montant', FILTER_SANITIZE_STRING);
    $idFHF = filter_input(INPUT_POST, 'idFHF', FILTER_SANITIZE_STRING);
    if (isset($_POST['corriger'])){
        if (nbErreurs()!=0){
            ajouterErreur('La saisie comporte des erreurs');
            include 'vues/v_erreurs.php';
        }else{
            $pdo->majFraisHorsForfait($idVisiteur, $lstMois, $libelle,$date,$montant,$idFHF);
            ajouterErreur('La modification a bien été prise en compte');
            include 'vues/v_erreurs.php';
        }
        $lesFraisForfait=$pdo->getLesFraisForfait($idVisiteur,$lstMois);
        $lesFraisHorsForfait=$pdo->getLesFraisHorsForfait($idVisiteur,$lstMois);
        $nbJustificatifs= filter_input(INPUT_POST, 'nbJust', FILTER_SANITIZE_STRING);
        //include 'vues/v_valideFrais.php';
    }
    if (isset($_POST['reporter'])){
        $mois=getMoisSuivant($lstMois);
        if ($pdo->estPremierFraisMois($idVisiteur, $mois)){
        $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
        }
        $pdo->creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant);//Les infos qu'on a modifiées, les mettre ds la nvlle fiche
        $pdo->majLibelleFraisHorsForfait($idVisiteur,$lstMois, $idFHF);      
    }
    $lesFraisForfait=$pdo->getLesFraisForfait($idVisiteur,$lstMois);
    $lesFraisHorsForfait=$pdo->getLesFraisHorsForfait($idVisiteur,$lstMois);
    include 'vues/v_valideFrais.php';
    break;
case 'validerFiche':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $visiteurs= $pdo->getChoisirVisiteur();
    $visiteurASelectionner = $idVisiteur;
    $lstMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois=getLes12DerniersMois($mois); 
    $moisASelectionner = $lstMois;
    $sommeFHF= $pdo->getMontantFHF($idVisiteur, $lstMois);
    $sommeFF= $pdo->getMontantFF($idVisiteur, $lstMois);
    //$calculMontantValide=$pdo->calculMontantValide($idVisiteur, $lstMois);//fonction a creer
    $montantValide=$sommeFF+$sommeFHF;
    $etat='VA';
    $pdo->majEtatFicheFrais($idVisiteur, $lstMois, $etat);
    include 'vues/v_ficheValidee.php';
    break;
//a faire le 01/03/21: bouton reinitialiser*3 et nb justificatifs
}