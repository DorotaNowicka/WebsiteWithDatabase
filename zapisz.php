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
        <?php
        $dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
	    try{
	        $pdo = new PDO($dsn, 'dnowicka', '1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	        
	        
	        $query_ls_regaty = $pdo->query('SELECT nazwa, Regaty.rok rok, pocz_zapisow, kon_zapisow, port, kolejnosc FROM Regaty JOIN Trasa ON Regaty.nazwa=Trasa.regaty AND Regaty.rok=Trasa.rok order by rok DESC,regaty, kolejnosc');
	        $ls_regaty= $query_ls_regaty->fetchAll();
        ?>
        
        <div class="communicate_m" style="margin-top=10px"/> 
        <CENTER>
        <form method="post">
        Znajdź zawody z roku: <input type="number" name="szukaj_roku" placeholder="Tylko liczba"/> 	
<input type="submit" style="width:40px" value="&#128269;"/>

          
        
        </form><br/>
        lub
        <form method="post">
        
        <label for="z_portem">Znajdź zawody z portem: </label>
        <select id="z_portem" name="szukaj_portu">
        <?php
        $query_ls_porty = $pdo->query('SELECT nazwa from Port ORDER BY nazwa');
	        $ls_porty= $query_ls_porty->fetchAll();
	        
	        foreach($ls_porty as $port){
	        echo '<option value="'.$port['nazwa'].'">'.$port['nazwa'].'</option>';
	        }
	        
	        
	        
	        ?>
       
        
        </select>
        
        
<input type="submit" style="width:40px" value="&#128269;"/>
        </form>
        </CENTER>
        
        </div>
        
        <!-- tabelka od najnowszych -->
        <CENTER>
            <table>
            <thead>
            <tr><th colspan="7">Przejrzyj regaty z ostatnich lat</th></tr>
						<tr><th>Nazwa regat</th><th>Data</th><th>Początek zapisów</th><th>Koniec zapisów</th><th>Trasa</th><th>Zapisz się</th></tr>
            </thead>
            <tbody>
            <?php
            
            if(isset($_POST['szukaj_roku'])){
            $poprzednie="";
	        $poprzedni_rok="";
	        foreach ($ls_regaty as $regaty){
	            if($poprzednie==$regaty['nazwa']&&$poprzedni_rok==$regaty['rok']){
	            continue;
	            }else{
	                if($regaty['rok']==$_POST['szukaj_roku']){
	            echo "<tr><td>{$regaty['nazwa']}</td><td>{$regaty['rok']}</td><td>{$regaty['pocz_zapisow']}</td><td>{$regaty['kon_zapisow']}</td><td><a href='trasa.php?nazwa=".$regaty['nazwa']."&rok=".$regaty['rok']."'>Trasa</a></td><td><a href = 'zapisy.php?nazwa=".$regaty['nazwa']."&rok=".$regaty['rok']."'>Zapisz się</a></td></tr>";
	            $poprzednie=$regaty['nazwa'];
	            $poprzedni_rok=$regaty['rok'];
	            }}}
            
            }elseif(isset($_POST['szukaj_portu'])){
                $poprzednie="";
	        $poprzedni_rok="";
	        foreach ($ls_regaty as $regaty){
	            if($poprzednie==$regaty['nazwa']&&$poprzedni_rok==$regaty['rok']){
	            continue;
	            }else{
	                if($regaty['port']==$_POST['szukaj_portu']){
	            echo "<tr><td>{$regaty['nazwa']}</td><td>{$regaty['rok']}</td><td>{$regaty['pocz_zapisow']}</td><td>{$regaty['kon_zapisow']}</td><td><a href='trasa.php?nazwa=".$regaty['nazwa']."&rok=".$regaty['rok']."'>Trasa</a></td><td><a href = 'zapisy.php?nazwa=".$regaty['nazwa']."&rok=".$regaty['rok']."'>Zapisz się</a></td></tr>";
	            $poprzednie=$regaty['nazwa'];
	            $poprzedni_rok=$regaty['rok'];
	            }}}
            }else{
        //opcja domyślna - wszystkie zawody
	        $poprzednie="";
	        $poprzedni_rok="";
	        foreach ($ls_regaty as $regaty){
	            if($poprzednie==$regaty['nazwa']&&$poprzedni_rok==$regaty['rok']){
	            continue;
	            }else{
	            
	            echo "<tr><td>{$regaty['nazwa']}</td><td>{$regaty['rok']}</td><td>{$regaty['pocz_zapisow']}</td><td>{$regaty['kon_zapisow']}</td><td><a href='trasa.php?nazwa=".$regaty['nazwa']."&rok=".$regaty['rok']."'>Trasa</a></td><td><a href = 'zapisy.php?nazwa=".$regaty['nazwa']."&rok=".$regaty['rok']."'>Zapisz się</a></td></tr>";
	            $poprzednie=$regaty['nazwa'];
	            $poprzedni_rok=$regaty['rok'];
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
        
?>
</tbody>
</table>
<br/><br/>
</CENTER>

        </div>
        
        


        <div id="footer">
            Regatnik - zaplanuj regaty! &copy; Wszelkie prawa zatrzeżone
        </div>

    </div>
</body>

</html>