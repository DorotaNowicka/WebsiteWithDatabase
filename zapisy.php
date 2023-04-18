<?php
//echo $_GET['nazwa']."  ".$_GET['rok'];
session_start();
    $nazwa = $_GET['nazwa'];
    $rok = $_GET['rok'];
    $_SESSION['zapisz_regaty']=$nazwa;
    $_SESSION['zapisz_rok']=$rok;
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
        <!--_________________________________________-->
        <!-- Dodaj daty zapisów.OK
         if obecna data nie między początkami zapisów, wyświetl komunikat, że za wcześnie/ za późno na rejestrację -->
         <?php
         $dataczas = new DateTime();
         date_timezone_set($dataczas, timezone_open('Europe/Warsaw'));
        // $dataczass->setTimezone(new DateTimeZone('CET'));
         
         //mogłabym przesłać też daty zapisów, ale 1. to jawne przesyłanie, 2. nowe zapytanie uniemożliwi błędy
         //połączenie
         $dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
	    try{
	        $pdo = new PDO($dsn, 'dnowicka', '1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	        
	        
	        $te_regaty = $pdo->prepare('SELECT * from Regaty WHERE nazwa= :nazwa AND rok= :rok');
	        $te_regaty->bindValue(':nazwa', $nazwa, PDO::PARAM_STR);
	        $te_regaty->bindValue(':rok', $rok, PDO::PARAM_STR);
	        $te_regaty->execute();
	        $ile = $te_regaty->rowCount();
	   //$ile = 1;
	        if($ile!=1){
	            echo '<div class="communicate">Takie regaty nie istnieją!</div>';
	        }else{
	            //zczytaj ich daty zapisów:
                $te_regaty_f = $te_regaty->fetchAll();
                foreach ($te_regaty_f as $regaty){
                
                    $data_p = new DateTime($regaty['pocz_zapisow']);
                    
                    $dpocz = DateTime::createFromFormat('Y-m-d', $regaty['pocz_zapisow']);
                    $dkon = DateTime::createFromFormat('Y-m-d', $regaty['kon_zapisow']);
                    
                    
                    //koniec prób
                    
                    if ($dpocz>$dataczas){
                        echo '<div class="communicate"/>Za wcześnie, aby się zapisać. Zapisy rozpoczną się '.$dpocz->format('Y-m-d').'.</div>';
                    }elseif($dkon<$dataczas){
                        echo '<div class="communicate"/>Rejestracja na te zawody już się zakończyła.</div>';    
                    }else{
                    // jeśli nie - sprawdź czy zalogowany. NIE - zmienna z adresu zapisów, przekieruj do logowania
                        if ((!isset($_SESSION['is_logged']))OR($_SESSION['is_logged']==false)){
                        echo '<div class="communicate"/>Musisz być zalogowany, żeby się zapisać.</div>';
                        }else{
                        
                        //zapisz się na zawody nazwa, data. Czy potwierdzasz? Potwierdzenie - dodanie do bazy danych Uczestnik, komunikat że się udało
                        $_SESSION['mozna_zapisac']=true;
                        echo '<div class="communicate" style="text-align:center"/>'.$_SESSION['imie'].', czy na pewno chcesz zapisać się na regaty '.$regaty['nazwa'].' z '.$rok.' roku?<br/>
                        <form action="zapisy2.php" method="post"><input type="hidden" name="mozna_zapisac" value="tak"/><input type="submit" value="Tak, zapisz mnie!" />
                        </div>';
                        }
                    }
                    
                    
     
                   
                    
                    
                }
	        
	        }
	        
	        
	        
	       
	     }catch(PDOException $e){
	            $wszystko_OK=false;
	            echo "<br/>padłem. Brak połączenia z bazą.";
	            //Dla deva, usuń!:
	            echo '<br/>Info: '.$e;
	            exit();
	        }
	     
         
      echo $dataczas->format('Y-m-d H:i:s');
         ?>
         
        
        
        


        <!--_________________________________________-->
        </div>
        
        


        <div id="footer">
            Regatnik - zaplanuj regaty! &copy; Wszelkie prawa zatrzeżone
        </div>

    </div>
</body>

</html>