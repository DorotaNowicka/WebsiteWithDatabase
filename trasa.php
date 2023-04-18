<?php
//echo $_GET['nazwa']."  ".$_GET['rok'];
    $nazwa = $_GET['nazwa'];
    $rok = $_GET['rok'];
?>

<?php
session_start();
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
        
        
        
        <!--________________________________________________________-->

        <div id="trasa">
        <h1> Trasa regat <?=$nazwa." z ".$rok." roku" ?></h1>
        <CENTER>
        <table>
        <!--____________________Nagłówek_____________________-->
       
						<tr><th> </th><th>Port</th><th>Ocean</th><th>Szerokość geograficzna</th><th>Długość geograficzna</th></tr>
            </thead>
            <tbody>
        
        <?php
           $dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
	    try{
	        $pdo = new PDO($dsn, 'dnowicka', '1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	        
	        
	        $query_trasa = $pdo->prepare('SELECT kolejnosc, port,ocean, szerokosc_geo, dlugosc_geo from Trasa JOIN Port ON Trasa.port=Port.nazwa WHERE regaty= :nazwa AND rok= :rok ORDER BY kolejnosc');
	        $query_trasa->bindValue(':nazwa', $nazwa, PDO::PARAM_STR);
	        $query_trasa->bindValue(':rok', $rok, PDO::PARAM_STR);
	        $query_trasa->execute();
	        $trasa = $query_trasa->fetchAll();
	        
	        
	       foreach ($trasa as $przystanek){
	        echo "<tr><td>{$przystanek['kolejnosc']}</td><td>{$przystanek['port']}</td><td>{$przystanek['ocean']}</td><td>{$przystanek['szerokosc_geo']}</td><td>{$przystanek['dlugosc_geo']}</td></tr>";
	        }
	        
	        
	        
	      /*  foreach ($ls_regaty as $regaty){
	        echo "<tr><td>{$regaty['nazwa']}</td><td>{$regaty['rok']}</td><td>{$regaty['liczba_uczestn']}</td><td>{$regaty['pocz_zapisow']}</td><td>{$regaty['kon_zapisow']}</td><td><a href='trasa.php?nazwa=".$regaty['nazwa']."&rok=".$regaty['rok']."'>Trasa</a></td><td><a href = 'zapisy.php?nazwa=".$regaty['nazwa']."&rok=".$regaty['rok']."'>Zapisz się</a></td></tr>";
	        }*/
	        
	        }catch(PDOException $e){
	            $wszystko_OK=false;
	            echo "<br/>padłem. Brak połączenia z bazą.";
	            //Dla deva, usuń!:
	            echo '<br/>Info: '.$e;
	            exit();
	        }
	       
        
?>

</table>
</CENTER>

</div>
        </div>
        
        
<br></br>

        <div id="footer">
            Regatnik - zaplanuj regaty! &copy; Wszelkie prawa zatrzeżone
        </div>

    </div>
</body>

</html>