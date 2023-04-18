<?php
session_start();

if (isset($_POST['haslo_nowe'])){
    $wszystko_OK = true;
    //czy poprawne obecne hasło
    $dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
	$pdo = new PDO($dsn, 'dnowicka', '1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	if($pdo){
	 
     $email = $_SESSION['email'];
     $haslo = $_POST['haslo_teraz'];
     $haslo_nowe = $_POST['haslo_nowe'];
     $haslo_nowe2 = $_POST['haslo_nowe2'];
    
     $haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");
     $haslo_nowe = htmlentities($haslo_nowe, ENT_QUOTES, "UTF-8");
     $haslo_nowe2 = htmlentities($haslo_nowe2, ENT_QUOTES, "UTF-8");
     

    $sql_czy_poprawne = "SELECT * FROM Osoby WHERE email='$email'";
	

	  $query_haslo = $pdo->prepare('SELECT * FROM Osoby WHERE email= :email');
	   
	        $query_haslo->bindValue(':email', $email, PDO::PARAM_STR);
	        $query_haslo->execute();
	        $osoba = $query_haslo->fetchAll();
	
	        
	       foreach ($osoba as $row){
	       
	          if(password_verify($haslo, $row['haslo'])){
	          //czy nowe hasło spełnia normy
	        
	       //Dlugosc
		    if ((strlen($haslo_nowe)<8) || (strlen($haslo_nowe)>20))
		    {
			    $wszystko_OK=false;
			    $_SESSION['e_haslo1']="Hasło musi posiadać od 8 do 20 znaków";
			    
		    }
		    if($haslo_nowe!=$haslo_nowe2){
			    $wszystko_OK=false;
			    $_SESSION['e_haslo2']="Podane hasła nie są zgodne";
		    }
		
		    $haslo_hash = password_hash($haslo_nowe, PASSWORD_DEFAULT);
            
            if ($wszystko_OK==true){
            $query_zmien = $pdo->prepare('UPDATE Osoby SET haslo=:haslo WHERE email= :email');
	        $query_zmien->bindValue(':haslo', $haslo_hash, PDO::PARAM_STR);
	        $query_zmien->bindValue(':email', $email, PDO::PARAM_STR);
	        $query_zmien->execute();
	        $_SESSION['zmieniono']="Hasło zostało zmienione";
            }
	          }else{
	          $wszystko_OK=false;
	           $_SESSION['e_haslo']='<span style="color:red">Podane hasło jest nieprawidłowe</span>';
	          }
	        }
	        
	        
            
            
	
	//od pdo
	}
//od isset submit
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

        <!-- ___________________  -->
        <div class="communicate">
        
        
        <?php
                    if ((!isset($_SESSION['is_logged']))OR($_SESSION      ['is_logged']==false)){
                   echo "Musisz być zalogowany, aby zmienić hasło.";
                   exit();
                   }
                    ?>
      
        <CENTER>
        
        <h3>Zmień hasło</h3>
        
        <form method="post">
        
        obecne hasło: <br/> &nbsp; <input type="password" name="haslo_teraz"/>
    <?php
        if(isset($_SESSION['e_haslo'])){
            echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
            unset($_SESSION['e_haslo']);
        }else{echo '<br/>';
        }
    ?>
    
      nowe hasło: <br/> &nbsp; <input type="password" name="haslo_nowe"/>
    <?php
        if(isset($_SESSION['e_haslo1'])){
            echo '<div class="error">'.$_SESSION['e_haslo1'].'</div>';
            unset($_SESSION['e_haslo1']);
        }else{echo '<br/>';
        }
    ?>
    
    powtórz nowe hasło: <br/> &nbsp; <input type="password" name="haslo_nowe2"/> 
    <?php
        if(isset($_SESSION['e_haslo2'])){
            echo '<div class="error">'.$_SESSION['e_haslo2'].'</div>';
            unset($_SESSION['e_haslo2']);
        }else{echo '<br/>';
        }
    ?>
    <br/>
    
    
    <button type="submit">Zmień hasło</button>

        
        </form>
        
            <?php
        if(isset($_SESSION['zmieniono'])){
            echo '<div style="color: white">'.$_SESSION['zmieniono'].'</div>';
            unset($_SESSION['zmieniono']);
        }else{echo '<br/>';
        }
    ?>
        
        </CENTER>
        
        <!--dotąd-->
        </div>

        </div>
        
        


        <div id="footer">
            Regatnik - zaplanuj regaty! &copy; Wszelkie prawa zatrzeżone
        </div>

    </div>
</body>

</html>