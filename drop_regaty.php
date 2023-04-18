<?php
session_start();
if ((!isset($_SESSION['is_logged']))||($_SESSION['is_logged']!=true)||($_SESSION['typ']!="admin")){
                   header('Location: index.php');
                   exit();
                   }
if(!isset($_POST['usun_nazwa'])){
 header('Location: index.php');
                   exit();   
}

?>


<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8" />
    <title>Regatownik</title>
    <meta
      name="description"
      content="Zaplanuj swoje regaty. Znajdziesz tu wszystkie zawody regatowe na wodach oceanów z ostatnich lat."
    />
    <meta
      name="keywords"
      content="regaty, zawody, porty, mistrzostwa, oceany, sporty wodne"
    />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="fontello/css/fontello.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;500&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="zaglowka.png">
  </head>
  
  
<body>
    <div id="container">
  
    <?php
        $u_nazwa = $_POST['usun_nazwa'];
		$u_nazwa = filter_var($u_nazwa, FILTER_SANITIZE_EMAIL);
		$u_rok = $_POST['usun_rok'];
		$u_rok = filter_var($u_rok, FILTER_SANITIZE_EMAIL);
        $dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
	    try{
	        
	        $pdo = new PDO($dsn, 'dnowicka', '1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	        
	        //sprawdzenie czy takie zawody są
	        $query_czy_sa_regaty = $pdo->prepare('SELECT * FROM Regaty WHERE nazwa = :nazwa and rok =:rok');
	        $query_czy_sa_regaty->bindValue(':nazwa', $u_nazwa, PDO::PARAM_STR);
	        $query_czy_sa_regaty->bindValue(':rok', $u_rok, PDO::PARAM_STR);
	        $query_czy_sa_regaty->execute();
	        $czy_sa = $query_czy_sa_regaty->fetch();
	        
	        if(!$czy_sa){
	           echo '<div class="communicate">Zawodów '.$u_nazwa.' z roku '.$u_rok.' nie ma w bazie.</div>';
			    
	        }else{
	        
	        //Usuwanie
	        $query_uregatyT = $pdo->prepare('DELETE FROM Trasa WHERE regaty = :nazwa AND rok = :rok');
	        $query_uregatyT->bindValue(':nazwa', $u_nazwa, PDO::PARAM_STR);
	        $query_uregatyT->bindValue(':rok', $u_rok, PDO::PARAM_STR);
	        $query_uregatyT->execute();
	        
	        $query_uregatyU = $pdo->prepare('DELETE FROM Uczestnik WHERE regaty = :nazwa AND rok = :rok');
	        $query_uregatyU->bindValue(':nazwa', $u_nazwa, PDO::PARAM_STR);
	        $query_uregatyU->bindValue(':rok', $u_rok, PDO::PARAM_STR);
	        $query_uregatyU->execute();
	       
	        $query_uregaty = $pdo->prepare('DELETE FROM Regaty WHERE nazwa = :nazwa AND rok = :rok');
	        $query_uregaty->bindValue(':nazwa', $u_nazwa, PDO::PARAM_STR);
	        $query_uregaty->bindValue(':rok', $u_rok, PDO::PARAM_STR);
	        $query_uregaty->execute();
	        
	       
	         echo '<div class="communicate">Usunięto zawody '.$u_nazwa.' z roku '.$u_rok.' wraz z danymi o ich uczestnikach.</div>';
	        }
	     }catch(PDOException $e){
	            $wszystko_OK=false;
	            echo "<br/>padłem. Brak połączenia z bazą.";
	            //Dla deva, usuń!:
	            echo '<br/>Info: '.$e;
	            exit();
	        }
    ?>
    </div>
</body>
</html>