 <?php
 session_start();
 
 
 if((!isset($_POST['login']))||(!isset($_POST['haslo'])))
 {
    header('Location: index.php');
    exit();
 }
 
 $dsn = "pgsql:host=labdb;port=5432;dbname = dnowicka;";
	$pdo = new PDO($dsn, 'dnowicka', '1234');
	if($pdo){
	echo "Connected to the $db database successfully!<br/>";
	 
     $login = $_POST['login'];
     $haslo = $_POST['haslo'];
     $login = htmlentities($login, ENT_QUOTES, "UTF-8");

    $sql_czy_poprawne = "SELECT COUNT(*) FROM Osoby WHERE email='$login'";
	$sql_log = "SELECT * FROM Osoby WHERE email='$login'";
	
	
	if($poprawne = $pdo->query($sql_czy_poprawne)->fetchColumn()){
	    if($rezultat = $pdo->query($sql_log))
	    {
	    	while ($row = $rezultat->fetch(\PDO::FETCH_ASSOC)) {
	    	  
	    	    if(password_verify($haslo, $row['haslo'])){
	        	   $_SESSION['is_logged']=true;
	            	//echo "Witaj ";
	            	//echo $row['imie'];
	            	//echo "! Zostałas poprawnie zalogowana.";
                   $_SESSION['imie']=$row['imie'];
                   $_SESSION['nazwisko']=$row['nazwisko'];
                   $_SESSION['email']=$row['email'];
                   $_SESSION['typ']=$row['typ']; 
                   unset($_SESSION['blad']);
	               header('Location: index.php'); 
                   }else{
                 $_SESSION['blad']='<span style="color:red">Nieprawidłowy login lub hasło</span>';
                 header('Location: zaloguj.php');
                 }        
            }
	        
	    }
	    
	}
	else{
	    $_SESSION['blad']='<span style="color:red">Nieprawidłowy email lub hasło</span>';
	    header('Location: zaloguj.php');
	}
	
	

	
	$pdo->close();
	}else{
	echo "conection error";
	}

 
 ?>