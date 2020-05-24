<?php

$adresse = '';

function debug($var) {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}


// Connexion à la BDD
$pdo = new PDO('mysql:host=localhost;dbname=immobilier',  
                'root',  
                '', 
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
                )
);

$resultat = $pdo->query("SELECT * FROM logement");


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Liste des logements</title>
	
	<!-- CDN Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	
	<style>
		.photo {
			width: 200px;
		}
		table {
			border-collapse: collapse;
		}
		table, th, tr, td {
			border: 1px solid;
		}
	</style>
</head>

<body>
    <div class="container">
        <h1 class="mt-4">Liste des logements</h1>
            <table>
                <tr>
                    <th>Titre</th>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Code postal</th>
                    <th>Surface</th>
                    <th>Prix</th>
                    <th>Type</th>
                    <th>Photo</th>
                    <th>Voir</th>
                </tr>

                <?php

                while ($logement = $resultat->fetch(PDO::FETCH_ASSOC)) {

                    // debug($logement);
                    echo '<tr>'; 
                        echo '<td>' . substr($logement['titre'], 0, 6) . '...' . '</td>';
                        echo '<td>' . substr($logement['adresse'], 0, 10) . '...' . '</td>'; 
                        echo '<td>' . $logement['ville'] . '</td>';
                        echo '<td>' . $logement['cp'] . '</td>';
                        echo '<td>' . $logement['surface'] . 'm2</td>';
                        echo '<td>' . $logement['prix'] . '€</td>';
                        echo '<td>' . $logement['type'] . '</td>';
                        echo '<td><img src="' . $logement['photo'] . '"  class="photo"></td>';
                        echo '<td><a href="detail_logement.php?id_logement=' . $logement['id_logement'] . '">voir</a></td>';
                    echo '</tr>';	
                    
                }
                ?>
    </div>
</table>

</body>
</html>