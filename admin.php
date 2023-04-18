<?php
session_start();
 if ((!isset($_SESSION['is_logged']))||($_SESSION      ['is_logged']!=true)||($_SESSION['typ']!="admin")){
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
<!--___________________________________________________- -->
<!-- Usuń regaty wraz z trasą -->
<form method="post" action="drop_regaty.php">
    <h2>Usuń regaty</h2>
    nazwa regat: <input type="text" name="usun_nazwa" /> <br/>
    rok regat: <input type="text" name="usun_rok" /><br/>
    <br/><button style = "font-family: 'Montserrat', sans-serif" type="submit">Usuń regaty</button></div>
</form>

<!-- Zmień uprawnienia użytkownika -->
<form method="post" action="uprawnienia.php">
    <h2>Zmień uprawnienia użytkownika</h2>
    email: <input type="email" name="up_email" /> <br/>
    nadaj uprawnienie:  
    <select id="up_typ" name="up_typ">
        <option value="zawodnik">zawodnik</option>  
        <option value="organizator">organizator</option> 
        <option value="admin">admin</option> 
        </select><br/>
    
    <br/><button type="submit">Zmień uprawnienia</button>
</form>

<!-- Dodaj port -->
<form method="post" action="dodaj_port.php">
    <h2>Dodaj port</h2>
    nazwa portu: <input type="text" name="add_port" /> <br/>
    ocean: <select id="add_ocean" name="add_ocean">
        <option value="Pacyfik">Pacyfik</option>  
        <option value="Indyjski">Indyjski</option> 
         <option value="Atlantyk">Atlantyk</option>  
        
        </select><br/>
    szerokość geograficzna:<input type="number" name="add_szer" max="180"/> 
     <select id="add_szerP" name="add_szerP">
        <option value="E">E</option>  
        <option value="W">W</option> 
        </select><br/>
    długość geograficzna: <input type="number" name="add_dl" max="90" />
    <select id="add_dlP" name="add_dlP">
        <option value="N">N</option>  
        <option value="S">S</option> 
        </select><br/>
    <br/><button type="submit">Dodaj port</button>
</form>

<br></br>



        </div>
        
        


        <div id="footer">
            Regatnik - zaplanuj regaty! &copy; Wszelkie prawa zatrzeżone
        </div>

    </div>
</body>

</html>: