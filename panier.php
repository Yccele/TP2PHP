<?php 

/* 

OLIFERTCHOUK Cindy
CIANO Enzo
PFISTER Cécile 
Répertoire : sta6

*/

// Démarrage de la session
session_start();

// Si la variable $_POST["envoi"] existe (si un bouton a été cliqué)
if (isset($_POST["envoi"])) {

  switch ($_POST["envoi"]) {

    case 'AJOUTER':              
      
      if (!empty($_POST["code"]) && !empty($_POST["article"]) && !empty($_POST["prix"])) {
        // On incrémente une variable de session 
        if (isset($_SESSION['i'])) {
          $_SESSION['i']++;
        } else {
          $_SESSION['i'] = 1;
        }
        // Création d'un tableau multidimensionnel pour stocker tous les livres ajoutés et leurs infos
        $_SESSION['panier'][$_SESSION['i']] = array('code' => htmlspecialchars($_POST["code"]), 'article' => htmlspecialchars($_POST["article"]), 'prix' => htmlspecialchars($_POST["prix"]));

        $msg = "Article ajouté";
        
      } else {
        $msg = "Champs manquants";
      }

      break;

    case 'VERIFIER':
      // Initialisation du total
      $total = 0;

      // Création du tableau récapitulatif à afficher 
      $table = "<table border='1'><tbody><tr><td colspan='3'><b>Recapitulatif de votre commande</b></td></tr>";
      $table .= " <tr><th>&nbsp;code&nbsp;</th><th>&nbsp;article&nbsp;</th><th>&nbsp;prix&nbsp;</th></tr>";

      if (isset($_SESSION['i'])) {
        // Boucle pour afficher toutes les lignes de commande
        for ($count = 1; $count <= $_SESSION['i']; $count++) { 

          // Vérifie si les champs existent et ne sont pas vides
          if (!empty($_SESSION['panier'][$count]['code']) && !empty($_SESSION['panier'][$count]['article']) && !empty($_SESSION['panier'][$count]['prix'])) {

            $table .= " <tr><td>" . $_SESSION['panier'][$count]['code'] . "</td> <td>" .$_SESSION['panier'][$count]['article'] . "</td><td>" .$_SESSION['panier'][$count]['prix'] . "</td></tr>";
            // Calcul du prix total  
            $total = $total + $_SESSION['panier'][$count]['prix'];
          }          
        } 
      }

      $table .= "<tr> <td colspan='2'> PRIX TOTAL </td> <td>" .  $total . "</td></tr>";
      $msg = $table;
      break;

    case 'ENREGISTRER':

      // Ouverture du fichier ou création si non existant
      $fichier = fopen("./Ecriture/commande.txt", 'w+');

      if (isset($_SESSION['i'])) {
        // Boucle sur le nombre de lignes de commande
        for ($j = 1; $j <= $_SESSION['i']; $j++) {
          // Boucle sur les données saisies
          foreach ($_SESSION['panier'][$j] as $key => $value) {
            // On écrit dans le fichier txt
            fputs($fichier, $value . "; ");          
          }
          fputs($fichier, "\n");
        }
      }
      fclose($fichier);
      // Message de confirmation
      $msg = "Commande enregistrée";
      break;

    case 'LOGOUT':
      session_destroy();

      $fichier = fopen("./Ecriture/commande.txt", 'w+');
      // Efface le fichier.txt
      ftruncate($fichier, 0);
      // ftruncate($fichier, rand(1, filesize("./Ecriture/commande.txt")));
      fclose($fichier);
      $msg = "La session est détruite, le fichier commande.txt est effacé.";
      break;      
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Gestion de panier</title>
  </head>
  <body>    
    <form action="" method="post">    
      <fieldset>
        <legend><b>Commande de livres</b></legend>
        <table>
          <tbody>
            <tr>
              <th>code : </th>
              <td> <input type="text" name="code" /></td>
            </tr>
            <tr>
              <th>article : </th>
              <td><input type="text" name="article" /></td>
            </tr>
            <tr>
              <th>prix :</th>
              <td> <input type="number" name="prix" /></td>
            </tr>
            <tr>
              <td colspan="3">
                <input type="submit" name="envoi" value="AJOUTER" />
                <input type="submit" name="envoi" value="VERIFIER" />
                <input type="submit" name="envoi" value="ENREGISTRER" />
                <input type="submit" name="envoi" value="LOGOUT" />
              </td>
            </tr>
          </tbody>
        </table>
      </fieldset>
      <br>
    </form>
    <?php 
      if (isset($msg)) {
        echo $msg;
      }
    ?>
  </body>
</html>


