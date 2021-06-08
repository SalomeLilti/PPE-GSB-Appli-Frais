<?php
/**
 * Vue du bouton Suivre le paiement des fiches de frais
 * 
 * @category  PPE
 * @package   GSB
 * @author    Salomé LILTI
 */
?>

<form method="post" action="index.php?uc=suivrePaiementFF&action=miseEnPaiement"      
      role="form">
    <div class="col-md-4">
        <h3>Sélectionner un visiteur et un mois : </h3>
    </div>
    <div class="col-md-4">

        <!-- liste déroulante visiteur-->
        <div class="form-group">
            <label for="lstVisiteurs" accesskey="n">Visiteur : </label>
            <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                    <?php
                    foreach ($visiteurs as $unVisiteur) {
                        $visiteurs = $unVisiteur['id'];
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];
                        if ($unVisiteur == $visiteurASelectionner) {
                            ?>
                        <option selected value="<?php echo $visiteurs ?>">
                        <?php echo $nom . ' ' . $prenom ?> </option>
                            <?php
                        } else {
                            ?>
                        <option value="<?php echo $visiteurs ?>">
                        <?php echo $nom . ' ' . $prenom ?> </option>
                            <?php
                        }
                    }
                    ?> 
            </select>
        </div>
        <!-- liste déroulante mois-->
        <div class="form-group">
            <label for="lstMois" accesskey="n">Mois : </label>
            <select id="lstMois" name="lstMois" class="form-control">
                    <?php
                    foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $moisASelectionner) {
                            ?>
                        <option selected value="<?php echo $mois ?>">
                        <?php echo $numMois . '/' . $numAnnee ?> </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $mois ?>">
                        <?php echo $numMois . '/' . $numAnnee ?> </option>
                        <?php
                    }
                }
                ?> 
            </select>
        </div> 
        <input id="ok" type="submit" value="Valider" class="btn btn-success" 
               role="button">
    </div>
</form>     
