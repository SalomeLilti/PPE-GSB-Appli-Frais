<?php

/** 
 * Suivi du paiement des fiches de frais par le comptable
 * @category  PPE
 * @package   GSB
 * @author   Salomé LILTI
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
case 'choisirFicheFrais':
    
    var_dump($action);
    break;
}