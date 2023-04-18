<?php
session_start();

$dataczas = new DateTime();

//Obsługa formularza
if(isset($_POST['o_nazwa'])){

 $wszystko_OK=true;
$o_nazwa = htmlentities($_POST['o_nazwa'], ENT_QUOTES, "UTF-8");
  //nazwa i rok nie puste
  
    if(strlen($o_nazwa)<1){
      $wszystko_OK=false;
      $_SESSION['e_o_nazwa']="Nazwa nie może być pusta";
      
    
    }

$_SESSION['o_nazwa']=$o_nazwa;
$o_rok = htmlentities($_POST['o_rok'], ENT_QUOTES, "UTF-8");
$_SESSION['o_rok']=$o_rok;
     if(strlen($o_rok)<1){
      $wszystko_OK=false;
      $_SESSION['e_o_rok']="Podanie roku jest wymagane";
    
    }
$o_pocz=$_POST['o_pocz'];
$_SESSION['o_pocz']=$o_pocz;
$o_kon=$_POST['o_kon'];
$_SESSION['o_kon']=$o_kon;
   
  
    if($_POST['o_kon']<=$_POST['o_pocz']){
        $wszystko_OK=false;
        $_SESSION['e_o_kon']="Data końca rejestracji musi być późniejsza niż data początku";
    }
    $porty_list = array($_POST['o_1'], $_POST['o_2'], $_POST['o_3'], $_POST['o_4'], $_POST['o_5'], $_POST['o_5']);
    
    
    
    // czy podane dwa porty
    if($_POST['o_1']==""){
        $wszystko_OK=false;
        $_SESSION['e_o_1']="Podanie pierwszego portu jest obowiązkowe";
    }
    if($_POST['o_2']==""){
        $wszystko_OK=false;
        $_SESSION['e_o_2']="Wymagane są przynajmniej dwa porty";
    }
    // brak dziur w trasie
    $puste=array();
    for($i = 0; $i<6;$i++){
        
        if ($porty_list[$i]==""){
            array_push($puste, $i+1);
        }
    }
    
    for($i = 1; $i<count($puste);$i++){
        if($puste[$i]-$puste[$i-1]!=1){
        $wszystko_OK=false;
        $_SESSION['e_o']="W trasie regat nie może być dziur";
        }
        
    }
    
     //Checkbox
        if(!isset($_POST['regulamin'])){
            $wszystko_OK=false;
			$_SESSION['e_regulamin']="Akceptacja regulaminu jest wymagana";
        }
    
   
        if($wszystko_OK==true){
        //połącz z bazą
         $dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
	    try{
	        $pdo = new PDO($dsn, 'dnowicka', '1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	        
	    //sprawdź czy nie było już 
	         $query_czy_nowe_regaty = $pdo->prepare('SELECT * FROM Regaty WHERE nazwa = :nazwa and rok =:rok');
	        $query_czy_nowe_regaty->bindValue(':nazwa', $o_nazwa, PDO::PARAM_STR);
	        $query_czy_nowe_regaty->bindValue(':rok', $o_rok, PDO::PARAM_STR);
	        $query_czy_nowe_regaty->execute();
	        $czy_nie_nowe = $query_czy_nowe_regaty->fetch();
	        
	        if($czy_nie_nowe){
	            $wszystko_OK=false;
			    $_SESSION['e_o_rok']='Regaty o tej nazwie w tym roku są już zgłoszone';
	        }
	        
	         if($wszystko_OK==true){
	        
        //dodaj regaty
	        $query_nowe_regaty = $pdo->prepare('INSERT INTO REGATY VALUES (:nazwa, :rok, :pocz,:kon)');
	        $query_nowe_regaty->bindValue(':nazwa', $o_nazwa, PDO::PARAM_STR);
	        $query_nowe_regaty->bindValue(':rok', $o_rok, PDO::PARAM_STR);
	        
	        $query_nowe_regaty->bindValue(':pocz', $o_pocz, PDO::PARAM_STR);
	        
	       
	        $query_nowe_regaty->bindValue(':kon', $o_kon, PDO::PARAM_STR);
	        
	        
	        $query_nowe_regaty->execute();
	        
	    //dodaj trasę
	       for($i = 0; $i<6;$i++){
	            
	            if($porty_list[$i]!=""){
	            $query_nowa_trasa = $pdo->prepare('INSERT INTO TRASA VALUES (:regaty, :rok, :port, :kolejnosc)');
	            $query_nowa_trasa->bindValue(':regaty', $o_nazwa, PDO::PARAM_STR);
	             $query_nowa_trasa->bindValue(':rok', $o_rok, PDO::PARAM_STR);
	             $query_nowa_trasa->bindValue(':port', $porty_list[$i], PDO::PARAM_STR);
	             $query_nowa_trasa->bindValue(':kolejnosc', $i+1, PDO::PARAM_STR);
	            $query_nowa_trasa->execute(); 
	            }
	            
	        }
	        
	       
	        $zorganizowano = true;
	        
	      }  
    }catch(PDOException $e){
	            $wszystko_OK=false;
	            echo "<br/>padłem. Brak połączenia z bazą.";
	            //Dla deva, usuń!:
	            echo '<br/>Info: '.$e;
	            exit();
	        }
	        

}
}//od if isset

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
            if($zorganizowano==true){
             echo '<div class="communicate">Twoje zawody zostały dodane. Możesz obejrzeć je w zakładce Zapisz się na regaty!</div>';
             exit();
            }
            
            ?>
        
        
            <?php
                    if ((!isset($_SESSION['is_logged']))||($_SESSION['typ']=="zawodnik")){
                   echo '<div class="communicate" style="text-align: center">Chcesz zorganizować zawody?<br/> Skontaktuj się z nami mailowo: a@gmail.com.<br/><br/><div style="font-size:60%">Jeśli masz już przyznane uprawnienia organizatora, zaloguj się.</div></div>';
                   exit();
                   }
            ?>
            
            <h2>Czas zorganizować zawody!</h2>
            Wypełnij formularz zgłoszeniowy zawodów.
            <hr>
           
            <form method="post">
            Nazwa: <input type="text" name="o_nazwa"  <?= isset($_SESSION['o_nazwa']) ? 'value="' . $_SESSION['o_nazwa'] . '"' : '' ?>/>
              <?php
        if(isset($_SESSION['e_o_nazwa'])){
            echo '<br/><div class="error">'.$_SESSION['e_o_nazwa'].'</div>';
            unset($_SESSION['e_o_nazwa']);
        }
    ?>
            <br/>
       
            Data zawodów: <input type="number" name="o_rok" placeholder="rok" <?= 'min="'.$dataczas->format('Y').'"'?> <?= isset($_SESSION['o_rok']) ? 'value="' . $_SESSION['o_rok'] . '"' : '' ?>/>
               <?php
        if(isset($_SESSION['e_o_rok'])){
            echo '<div class="error">'.$_SESSION['e_o_rok'].'</div>';
            unset($_SESSION['e_o_rok']);
        }
    ?>
     
            <br/>
            Początek rejestracji na zawody: 
            <?php 
            echo '<input type="date" name="o_pocz" min="'.$dataczas->format('Y-m-d').'"/>';
            ?>
            <br/>
             Koniec rejestracji na zawody: 
            <?php 
            echo '<input type="date" name="o_kon" min="'.$dataczas->format('Y-m-d').'"/>';
            ?>
            <?php
        if(isset($_SESSION['e_o_kon'])){
            echo '<div class="error">'.$_SESSION['e_o_kon'].'</div>';
            unset($_SESSION['e_o_kon']);
        }
    ?>
            <br/>
            
            <?php //zczytanie dostępnych portów
            $dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
	    try{
	        $pdo = new PDO($dsn, 'dnowicka', '1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $query_ls_porty = $pdo->query('SELECT nazwa from Port ORDER BY nazwa');
	        $ls_porty= $query_ls_porty->fetchAll();
	        
	      
	        }catch(PDOException $e){
	        echo "problem z bazą";
	        }
	       
            ?>
            <div id="o_trasa">
            <h3>Trasa</h3>
           <div style="font-size:60%"> Jeśli portu nie znajdziesz na liście, zgłoś to administracji: a@gmail.com.</div>
           
            <div class="pn"> 1 :</div> 
        <select id="port" name="o_1">
        <option value=""></option>
        <?php
               
	        foreach($ls_porty as $port){
	        echo '<option value="'.$port['nazwa'].'">'.$port['nazwa'].'</option>';
	        }
	        ?>
        </select><div style="clear:both"></div>
        <?php
        if(isset($_SESSION['e_o_1'])){
            echo '<div class="error">'.$_SESSION['e_o_1'].'</div>';
            unset($_SESSION['e_o_1']);
        }
        ?>
        
         <div class="pn"> 2 : </div>
        <select id="port" name="o_2">
        <option value=""></option>
        <?php
               
	        foreach($ls_porty as $port){
	        echo '<option value="'.$port['nazwa'].'">'.$port['nazwa'].'</option>';
	        }
	        ?>
        </select><div style="clear:both"></div>
        <?php
        if(isset($_SESSION['e_o_2'])){
            echo '<div class="error">'.$_SESSION['e_o_2'].'</div>';
            unset($_SESSION['e_o_2']);
        }
        ?>
        
         <div class="pn"> 3 : </div>
        <select id="port" name="o_3">
        <option value=""></option>
        <?php
               
	        foreach($ls_porty as $port){
	        echo '<option value="'.$port['nazwa'].'">'.$port['nazwa'].'</option>';
	        }
	        ?>
        </select><div style="clear:both"></div>
        
        <div class="pn">  4 : </div>
        <select id="port" name="o_4">
        <option value=""></option>
        <?php
               
	        foreach($ls_porty as $port){
	        echo '<option value="'.$port['nazwa'].'">'.$port['nazwa'].'</option>';
	        }
	        ?>
        </select><div style="clear:both"></div>
        
         <div class="pn"> 5 : </div>
        <select id="port" name="o_5">
        <option value=""></option>
        <?php
               
	        foreach($ls_porty as $port){
	        echo '<option value="'.$port['nazwa'].'">'.$port['nazwa'].'</option>';
	        }
	        ?>
        </select><div style="clear:both"></div>
        
         <div class="pn"> 6 : </div>
        <select id="port" name="o_6">
        <option value=""></option>
        <?php
               
	        foreach($ls_porty as $port){
	        echo '<option value="'.$port['nazwa'].'">'.$port['nazwa'].'</option>';
	        }
	        ?>
        </select><div style="clear:both"></div>
        <?php
        if(isset($_SESSION['e_o'])){
            echo '<div class="error">'.$_SESSION['e_o'].'</div>';
            unset($_SESSION['e_o']);
        }
        ?>
        </div>
      
         
         <br/><label><input type="checkbox" name="regulamin"/> Akceptuję regulamin organizacji zawodów</label> 
      <?php
        if(isset($_SESSION['e_regulamin'])){
            echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
            unset($_SESSION['e_regulamin']);
        }else{echo '<br/>';
        }
    ?>
    <br/>
    <div style="text-align:center">
    <button type="submit">Zgłoś zawody!</button>
    </div>
         </form>
         <br/>
        </div>
        
        


        <div id="footer">
            Regatnik - zaplanuj regaty! &copy; Wszelkie prawa zatrzeżone
        </div>

    </div>
</body>

</html>