<!DOCTYPE html>
<html lang="pl">

<?php
    session_start();
    if ((isset($_SESSION['is_logged']))&&($_SESSION['is_logged']==true))
    {
    header('location: index_regatownik.php');
    exit();
    }
?>


  <head>
    <meta charset="utf-8" />
    <title>Regatownik - zaloguj</title>
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
   
   <h1>Logowanie</h1>
    <form action="logowanie.php" method="post">
    login: &nbsp; <input type="text" name="login"/> <br/><br/>
    haslo: &nbsp; <input type="password" name="haslo"/> <br/><br/>
    <button type="submit">Zaloguj się</button>
    </form>
   <p style="margin-top: 10px;"> Nie masz konta? <a href="zarejestruj.php" style="color: #464646">Zarejestruj się!</a></p>
    <?php
      if(isset($_SESSION['blad'])) echo $_SESSION['blad']; 
    ?>
    </CENTER>
 </div>
  </body>

</html>
  