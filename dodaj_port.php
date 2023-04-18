<?php
session_start();
if ((!isset($_SESSION['is_logged']))||($_SESSION['is_logged']!=true)||($_SESSION['typ']!="admin")){
                   header('Location: index.php');
                   exit();
                   }
if(!isset($_POST['add_port'])){
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
        <a href="index.php">
        <div id="topbar">
            <div id="logo_kotwica">

                <div id="logo">
                    Regat&#x2388;wnik
                <!--&#x2638-->
                <!--&#x2388-->
                </div>
                

                <div id="kotwica">
                            <i class="icon-anchor"></i>

                </div>
                
                <div style="clear:both;"></div>

            </div>
            </a>
            
            <div id="menu">
                <a href="zapisz.php"><div class="menu_rect">Zapisz się na regaty!</div></a>
                <a href="organizuj.php"><div class="menu_rect">Dla organizatorów</div></a>
                                   <?php
                    if ((isset($_SESSION['is_logged']))&&($_SESSION      ['is_logged']==true)&&($_SESSION['typ']=="admin")){
                   echo ' <a href="admin.php"><div class="menu_rect">Admin</div></a>';
                   }
                    ?>
     
               
                
            </div>

            <div id="menu_osobowe">
            <a href='zarejestruj.php'><div class='menu_rect_o'>Zarejestruj się</div></a>
            
            <?php
                    
                   if ((isset($_SESSION['is_logged']))&&($_SESSION      ['is_logged']==true)){
                   
                   echo "<a href='wyloguj.php'><div class='menu_rect_o'>Wyloguj</div></a>";
                   }else{
                   echo "<a href='zaloguj.php'><div class='menu_rect_o'>Zaloguj</div></a>";
                   }
                   
            ?>
            
            </div>

            <div style="clear:both;"></div>
        </div>


        <div id="content">
<?php
        $add_port = $_POST['add_port'];
		$add_port = htmlentities($add_port, ENT_QUOTES, "UTF-8");
		$add_ocean = $_POST['add_ocean'];
		$add_ocean = htmlentities($add_ocean, ENT_QUOTES, "UTF-8");
		$add_szer = $_POST['add_szer'];
		$add_szer = htmlentities($add_szer, ENT_QUOTES, "UTF-8");
		if($add_szer==""){
		echo '<div class="communicate">Szerokość geograficzna jest wymagana.</div>';
		exit();
		}
		$add_szerP = $_POST['add_szerP'];
		$add_dl = $_POST['add_dl'];
		$add_dl = htmlentities($add_dl, ENT_QUOTES, "UTF-8");
		if($add_dl==""){
		echo '<div class="communicate">Długość geograficzna jest wymagana.</div>';
		exit();
		}
		$add_dlP = $_POST['add_dlP'];
        $dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
        $szerokosc = $add_szer.$add_szerP;
        $dlugosc = $add_dl.$add_dlP;
	    try{
	        $pdo = new PDO($dsn, 'dnowicka', '1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	        
	        //sprawdzenie czy taki port jest
	        $query_port = $pdo->prepare('SELECT * FROM Port WHERE nazwa = :nazwa');
	        $query_port->bindValue(':nazwa', $add_port, PDO::PARAM_STR);
	        $query_port->execute();
	        $port = $query_port->fetch();
	        
	        if($port){
	           echo '<div class="communicate">Port o nazwie '.$add_port.' już istnieje.</div>';
			    
	        }else{
	        
	        //Dodaj port
	        
	        $query_addport = $pdo->prepare('INSERT INTO PORT VALUES (:nazwa, :ocean, :szer, :dl)');
	        $query_addport->bindValue(':nazwa', $add_port, PDO::PARAM_STR);
	      $query_addport->bindValue(':ocean', $add_ocean, PDO::PARAM_STR);
	      $query_addport->bindValue(':szer', $szerokosc, PDO::PARAM_STR);
	      $query_addport->bindValue(':dl', $dlugosc, PDO::PARAM_STR);
	        $query_addport->execute();
	       
	         echo '<div class="communicate">Dodano port '.$add_port.'.</div>';
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
        
        


        <div id="footer">
            Regatnik - zaplanuj regaty! &copy; Wszelkie prawa zatrzeżone
        </div>

    </div>
</body>

</html>