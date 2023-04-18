<?php
session_start();
if ((!isset($_SESSION['is_logged']))||($_SESSION['is_logged']!=true)||($_SESSION['typ']!="admin")){
                   header('Location: index.php');
                   exit();
                   }
if(!isset($_POST['up_email'])){
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
        $up_email = $_POST['up_email'];
		$up_email = filter_var($up_email, FILTER_SANITIZE_EMAIL);
		$up_typ = $_POST['up_typ'];
		$up_typ = htmlentities($up_typ, ENT_QUOTES, "UTF-8");
        $dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
	    try{
	        $pdo = new PDO($dsn, 'dnowicka', '1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	        
	        //sprawdzenie czy taki uzytkownik jest
	        $query_uzytkownik = $pdo->prepare('SELECT * FROM Osoby WHERE email = :email');
	        $query_uzytkownik->bindValue(':email', $up_email, PDO::PARAM_STR);
	        $query_uzytkownik->execute();
	        $uzytkownik = $query_uzytkownik->fetch();
	        
	        if(!$uzytkownik){
	           echo '<div class="communicate">Do adresu '.$up_email.' nie jest przypisany żaden użytkownik.</div>';
			    
	        }else{
	        
	        //Zmień typ
	        
	        $query_up = $pdo->prepare('UPDATE Osoby SET typ= :typ WHERE email= :email');
	        $query_up->bindValue(':email', $up_email, PDO::PARAM_STR);
	        $query_up->bindValue(':typ', $up_typ, PDO::PARAM_STR);
	        $query_up->execute();
	       
	         echo '<div class="communicate">Uprawnienia użytkownika przypisanego do adresu '.$up_email.' zostały zmienione na typ: '.$up_typ.'.</div>';
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

        </div>
        
        


        <div id="footer">
            Regatnik - zaplanuj regaty! &copy; Wszelkie prawa zatrzeżone
        </div>

    </div>
</body>

</html>