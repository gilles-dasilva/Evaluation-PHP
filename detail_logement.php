<?php


$contenu = '';


function debug($var) {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}


$pdo = new PDO('mysql:host=localhost;dbname=immobilier',  
                'root',  
                '', 
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
                )
);

// debug($_GET);


if (isset($_GET['id_logement'])) {

	$_GET['id_logement'] = htmlspecialchars($_GET['id_logement'], ENT_QUOTES);
	$resultat = $pdo->prepare("SELECT * FROM logement WHERE id_logement = :id_logement");
	$resultat->execute(array(
		':id_logement' => $_GET['id_logement']	
	));

	if ($resultat->rowCount() == 0) { 
		$contenu .= '<p>Logement inexistant...</p>';
	} else {

		$detail_logement = $resultat->fetch(PDO::FETCH_ASSOC);
		//debug($logement);

		$contenu .= '<div><img src="'. $detail_logement['photo'] .'"></div>';
		$contenu .=  '<h1>' . $detail_logement['titre'] . '</h1>';
		$contenu .=  '<p>Adresse : ' . $detail_logement['adresse'] . '</p>';
		$contenu .=  '<p>Ville : ' . $detail_logement['ville'] . '</p>';
        $contenu .=  '<p>Code Postal : ' . $detail_logement['cp'] . '</p>';
		$contenu .=  '<p>Surface : ' . $detail_logement['surface'] . ' m2</p>';
        $contenu .=  '<p>Prix : ' . $detail_logement['prix'] . ' €</p>';
		$contenu .=  '<p>Type : ' . $detail_logement['type'] . '</p>';
        
	}

} // fin du if (isset($_GET['id_logement']))


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>Détail du Logement</title>
	
	<!-- CDN Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	

</head>
<body>
    <div class="container">
	    <h1 class="mt-4">Détail du Logement</h1>
	    <?php echo $contenu; ?>
    </div>
</body>
</html>