<?php

$message = '';
$photo = '';
$nom_photo = '';

// Connexion à la BDD
$pdo = new PDO('mysql:host=localhost;dbname=immobilier',  
                'root',  
                '', 
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
                )
);

/*echo '<pre>';
    print_r ($_POST);
echo '</pre>';*/

if (!empty($_POST)){  // si le formulaire a été envoyé
    // Contrôle des 8 champs :
    if(!isset($_POST['titre']) || strlen($_POST['titre']) < 2 || strlen($_POST['titre']) > 20){
        $message .= '<div>Le titre doit comporter entre 2 et 20 caractères.</div>';
    }  
    if (!isset($_POST['adresse']) || strlen($_POST['adresse']) < 4 || strlen($_POST['adresse']) > 50) {
        $message .= '<div>L\'adresse doit contenir entre 4 et 50 caractères.</div>';
    }
    if (!isset($_POST['ville']) || strlen($_POST['ville']) < 1 || strlen($_POST['ville']) > 20) {
        $message .= '<div>La ville doit contenir entre 1 et 20 caractères.</div>';
    }
    if(!isset($_POST['cp']) || !preg_match('#^[0-9]{5}$#', $_POST['cp'])){
        $message .= '<div>Le code postale n\'est pas valide.</div>';
    } 
    if(!isset($_POST['surface']) || !is_numeric($_POST['surface']) || $_POST['surface'] <= 0 || $_POST['surface'] >=10000){
        $message .= '<div>La surface doit être nombre comprise entre 1 et 10000.</div>';
    }
    if(!isset($_POST['prix']) || !is_numeric($_POST['prix']) || $_POST['prix'] <= 0 || $_POST['prix'] >=1000000){
        $message .= '<div>Le prix doit être nombre compris entre 1 et 1000000.</div>';
    }

    if (empty($message)){ 

        //debug($_FILES);
        if (!empty($_FILES['photo']['name'])) { // A voir : Trouver comment creer le dossier upload à partir d'ici

            $nom_photo = 'logement' . '_' . time();

			$photo = 'images/' . $nom_photo . '.jpg'; 

			copy($_FILES['photo']['tmp_name'], $photo); 
		}

        foreach($_POST as $indice => $valeur){  
            $_POST[$indice] = htmlspecialchars($valeur, ENT_QUOTES);
        }

        // Insertion an BDD avec une requête préparée :
        $resultat = $pdo->prepare("INSERT INTO logement (titre, adresse, ville, cp, surface, prix, type, photo) VALUES(:titre, :adresse, :ville, :cp, :surface, :prix, :type, :photo)");
        $succes = $resultat->execute(array(
            ':titre'    => $_POST['titre'],
            ':adresse'  => $_POST['adresse'],
            ':ville'    => $_POST['ville'],
            ':cp'       => $_POST['cp'],
            ':surface'  => $_POST['surface'],
            ':prix'     => $_POST['prix'],
            ':type'     => $_POST['type'],
            ':surface'  => $_POST['surface'],
            ':photo'    => $photo,
        ));  

        // Message de réussite ou d'erreur
        if($succes){
            $message .= '<div>Le logement a bien été ajouté.</div>';
        }else{
            $message .= '<div>Erreur lors de l\'enregistrement...</div>';
        }
    }  // FIN du if(empty($message))
}  // FIN de if(!empty($_POST))

 // Formulaire HTML
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Formulaire Logement</title>
	
	<!-- CDN Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Nouveau logement</h1>
        <?php echo $message; ?>
        <form method="post" enctype="multipart/form-data" action="">
        
            <div>
                <div>
                    <label for="titre">Titre :</label>
                </div>
                <div>
                    <input type="text" name="titre" id="titre" value="<?php echo $_POST['titre'] ?? ''; ?>">
                </div>
            </div>

            <div>
                <div><label for="adresse">Adresse :</label></div>
                <div><textarea name="adresse" id="adresse"><?php echo $_POST['adresse'] ?? ''; ?></textarea></div>
            </div>

            <div>
                <div><label for="ville">Ville :</label></div>
                <div><input type="text" name="ville" id="ville" value="<?php echo $_POST['ville'] ?? '';  ?>"></div>
            </div>

            <div>
                <div><label for="cp">Code postal :</label></div>
                <div><input type="text" name="cp" id="cp" value="<?php echo $_POST['code_postal'] ?? '';  ?>"></div>
            </div>

            <div>
                <div><label for="surface">Surface :</label></div>
                <div><input type="text" name="surface" id="surface" value="<?php echo $_POST['surface'] ?? '';  ?>"></div>
            </div>

            <div>
                <div><label for="prix">Prix :</label></div>
                <div><input type="text" name="prix" id="prix" value="<?php echo $_POST['prix'] ?? '';  ?>"></div>
            </div>
        
            <div>
                <div>
                    <label for="">Type :</label>
                </div>
                <div>
                    <input type="radio" name="type" id="type" value="location" checked> Location
                    <input type="radio" name="type" id="type" value="vente" <?php if(isset($_POST['type']) && $_POST['type'] == 'vente') echo 'checked' ?> > Vente
                </div>
            </div>

            <div class="input-group">
                    <label for="photo">Photo :</label><br>
                    <input type="file" name="photo" id="photo" accept=".jpg, .jpeg, .png"> <!-- A suivre -->
                    
            </div>

            <div>
                <input type="submit" value="Envoyer">
            </div>
    
        </form>
    </div>
</body>
</html>

<!--
    Exercice 3 : Je n'ai pas réussi les vérifications multiples sur les fichiers images(extensions, poids du fichier etc). Ligne 147 je voulai commencé par MAX_FILE_SIZE pour le poids mais une fois rentré dans l'input mes copy/images ne s'enregistrai plus. J'ai laissé "accept" pour les types de fichier accepté mais pas sur que cela soit appliqué ! Je voulais ensuite travailler la suite dans "if (!empty($_FILES['photo']['name']))" ligne 45 mais je n'ai pas eu le temps de terminer.

    Exercice 5 : Je n'ai pas trouvé la solution pour crée le dossier upload directement via PHP, je l'ai donc crée manuellement, j'imagine qu'une fois encore une manip était nécessaire dans "if (!empty($_FILES['photo']['name']))" ligne 45 mais après 30min dessus je suis passé à autre chose.

-->