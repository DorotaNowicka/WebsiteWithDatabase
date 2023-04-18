<?php
   session_start();
   if ((!isset($_SESSION['udanarejestracja']))){
    header('Location: index.php');
    exit();  
   }else{
    unset($_SESSION['udanarejestracja']);
   }           
?>

<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8" />
    <title>Regatownik - dziękujemy za rejestrację</title>
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
  <div class="communicate">
    Dziękujemy za rejestrację w serwisie. Możesz już <a href="zaloguj.php"> zalogować się</a> na swoje konto.
  </div>
  </body>
</html>
  
  
  
  