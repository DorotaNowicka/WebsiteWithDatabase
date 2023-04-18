<!DOCTYPE html>
<html lang="pl">

<?php
    session_start();
   
	
	if (isset($_POST['email']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_OK=true;
		//Wrzucam wszystkie do sieci
		
		$imie = $_POST['imie'];
		$imie = htmlentities($imie, ENT_QUOTES, "UTF-8");
		$nazwisko = $_POST['nazwisko'];
		$nazwisko = htmlentities($nazwisko, ENT_QUOTES, "UTF-8");
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		$_SESSION['imie']=$imie;
		$_SESSION['nazwisko']=$nazwisko;
		//Sprawdź poprawność imiename'a
		//Sprawdzenie długości imienia
		if ((strlen($imie)<3) || (strlen($imie)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_imie']="Imię musi posiadać od 3 do 20 znaków";
		}
		
		
		//Sprawdzenie nazwiska
		
		if(strlen($nazwisko)<1){
		    $wszystko_OK=false;
		    $_SESSION['e_nazwisko'] = "To pole nie może być puste";
		}
		if(strlen($nazwisko)>40){
		    $wszystko_OK=false;
		    $_SESSION['e_nazwisko'] = "Nie więcej niż 40 znaków";
		}
		
		//Sprawdzenie email

		//poprawne znaki
		if((filter_var($email, FILTER_SANITIZE_EMAIL)==false)||($emailB!=$email)){
		$wszystko_OK=false;
		$_SESSION['e_email']="Podaj poprawny adres email";
		}
		//Dlugosc
		if(strlen($emailB)<1){
		    $wszystko_OK=false;
		    $_SESSION['e_email'] = "To pole nie może być puste";
		}
		if(strlen($emailB)>80){
		    $wszystko_OK=false;
		    $_SESSION['e_email'] = "Nie więcej niż 80 znaków";
		}
		$_SESSION['email']=$emailB;
		
		//Poprawnosc hasla
		$haslo = $_POST['haslo'];
		$haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");
		$haslo2 = $_POST['haslo2'];
		$haslo2 = htmlentities($haslo2, ENT_QUOTES, "UTF-8");
		//Dlugosc
		if ((strlen($haslo)<8) || (strlen($haslo)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków";
		}
		if($haslo!=$haslo2){
			$wszystko_OK=false;
			$_SESSION['e_haslo2']="Podane hasła nie są zgodne";
		}
		
		$haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);

        //Checkbox
        if(!isset($_POST['regulamin'])){
            $wszystko_OK=false;
			$_SESSION['e_regulamin']="Akceptacja regulaminu jest wymagana";
        }
		
		//sprawdzenie z bazą
		
		$dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
	    try{
	        $pdo = new PDO($dsn, 'dnowicka', '1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	        
	        //jest już taki mail?
	        $query_czy_nowy_mail = $pdo->prepare('SELECT * FROM Osoby WHERE email = :email');
	        $query_czy_nowy_mail->bindValue(':email', $emailB, PDO::PARAM_STR);
	        $query_czy_nowy_mail->execute();
	        $czy_nie_nowy_mail = $query_czy_nowy_mail->fetch();
	        
	        if($czy_nie_nowy_mail){
	            $wszystko_OK=false;
			    $_SESSION['e_email']='Konto o takim adresie email już istnieje. <a href="zaloguj.php" style="color:red">Zaloguj się</a>.';
	        }
	        
	        //wszystko OK
		    if($wszystko_OK==true){
		        $query_insert = $pdo->prepare('INSERT INTO Osoby VALUES (:imie, :nazwisko, :email, :haslo_hash, :typ)');
		        $query_insert->bindValue('imie', $imie, PDO::PARAM_STR);
		        $query_insert->bindValue(':nazwisko', $nazwisko, PDO::PARAM_STR);
		        $query_insert->bindValue(':email', $emailB, PDO::PARAM_STR);
		        $query_insert->bindValue(':haslo_hash', $haslo_hash, PDO::PARAM_STR);
	        $query_insert->bindValue(':typ', 'zawodnik', PDO::PARAM_STR);
	        $query_insert->execute();
	        $_SESSION['udanarejestracja']=true;
	        header('Location: nowy_zawodnik.php');
	        
		    }
		    
		    
		   
	        
	    }catch(PDOException $e){
	        $wszystko_OK=false;
	        echo "<br/>padłem.";
	        //Dla deva, usuń!:
	        echo '<br/>Info: '.$e;
	        exit();
	    }
	    
	    
		
		
		
		

		
    }
?>


  <head>
    <meta charset="utf-8" />
    <title>Regatownik - zarejestruj</title>
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
   <br/><br/><br/>
   <div id="log">
   <CENTER>
   
   <h1>Rejestracja</h1>
   </CENTER>
    <form method="post">
    imię: &nbsp; <input type="text" name="imie"  <?= isset($_SESSION['imie']) ? 'value="' . $_SESSION['imie'] . '"' : '' ?>/> 
    
   
    <?php
        if(isset($_SESSION['e_imie'])){
            echo '<div class="error">'.$_SESSION['e_imie'].'</div>';
            unset($_SESSION['e_imie']);
        }else{echo '<br/>';
        }
    ?>
    <br/>
    
    nazwisko: &nbsp; <input type="text" name="nazwisko" <?= isset($_SESSION['nazwisko']) ? 'value="' . $_SESSION['nazwisko'] . '"' : '' ?>/> 
    <?php
        if(isset($_SESSION['e_nazwisko'])){
            echo '<div class="error">'.$_SESSION['e_nazwisko'].'</div>';
            unset($_SESSION['e_nazwisko']);
        }else{echo '<br/>';
        }
    ?>
    <br/>
    login(email): &nbsp; <input type="text" name="email" <?= isset($_SESSION['email']) ? 'value="' . $_SESSION['emailB'] . '"' : '' ?>/> 
    <?php
        if(isset($_SESSION['e_email'])){
            echo '<div class="error">'.$_SESSION['e_email'].'</div>';
            unset($_SESSION['e_email']);
        }else{echo '<br/>';
        }
    ?>
    <br/>
    
    hasło: &nbsp; <input type="password" name="haslo"/>
    <?php
        if(isset($_SESSION['e_haslo'])){
            echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
            unset($_SESSION['e_haslo']);
        }else{echo '<br/>';
        }
    ?>
    <br/>
    powtórz hasło: &nbsp; <input type="password" name="haslo2"/> 
    <?php
        if(isset($_SESSION['e_haslo2'])){
            echo '<div class="error">'.$_SESSION['e_haslo2'].'</div>';
            unset($_SESSION['e_haslo2']);
        }else{echo '<br/>';
        }
    ?>
    <br/>
    
    <label><input type="checkbox" name="regulamin"/> Akceptuję regulamin</label> 
      <?php
        if(isset($_SESSION['e_regulamin'])){
            echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
            unset($_SESSION['e_regulamin']);
        }else{echo '<br/>';
        }
    ?>
    <br/>
    <CENTER>
    <button type="submit">Zarejestruj się</button>
    </CENTER>
    </form>
 
 </div>
  </body>

</html>
  