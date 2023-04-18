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
            <?php
            if ((isset($_SESSION['is_logged']))&&($_SESSION      ['is_logged']==true)){
                   
                    echo "<a href='zmien_haslo.php'><div class='menu_rect_o'>Zmień hasło</div></a>";
                   }else{
                   echo "<a href='zarejestruj.php'><div class='menu_rect_o'>Zarejestruj się</div></a>";
                   }
            
            ?>
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
            <h2>Witaj na regatowniku - stronie internetowej zawierającej wszystkie potrzebne informacje o regatach na wodach całego globu.</h2>
            
             <p>Tu by były zdjęcia albo historia regat, ale pozwolę sobie umieścić tu Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi fermentum, elit at tempus dignissim, elit orci feugiat velit, nec rhoncus massa nibh in risus. Donec nisl ante, vulputate id urna et, sodales tempus enim. Pellentesque faucibus mi pretium dolor elementum maximus. Ut sagittis nunc at tellus pulvinar sollicitudin. Nunc id metus id urna sollicitudin porttitor nec sed nisi. Pellentesque at commodo odio, non egestas mauris. Morbi pulvinar arcu ut quam ornare, sed consectetur velit sollicitudin. Maecenas consectetur tortor risus, quis gravida turpis dictum congue. Duis nec aliquam odio, at porta odio. Phasellus sit amet finibus justo. In lobortis condimentum egestas. Nam tincidunt augue iaculis nunc mattis suscipit. Morbi libero sem, elementum eu magna vel, molestie laoreet urna. Nam euismod enim vel sapien egestas, quis rutrum eros porttitor.</p>

 <p>Cras fringilla a est nec scelerisque. Nam vestibulum, elit vitae facilisis semper, mi orci vulputate massa, in posuere nisi mauris nec dolor. Mauris rhoncus viverra magna, et lacinia lorem sodales id. Nam massa velit, scelerisque eget velit nec, sodales luctus purus. Etiam laoreet augue metus, a feugiat odio rhoncus sed. Curabitur sed risus posuere, elementum ligula et, tincidunt justo. Aenean sodales quis orci a auctor. Morbi nec elit et lacus sagittis dapibus.</p>

 <p>Quisque tincidunt a nunc sed dapibus. Vestibulum venenatis tincidunt magna ut posuere. In neque ex, aliquet quis odio eget, semper pharetra velit. Vestibulum a nisl sodales, auctor massa ut, elementum massa. Donec convallis ligula eu turpis pulvinar hendrerit. Etiam aliquam, ipsum nec mattis vulputate, orci eros sodales mi, convallis condimentum lacus tortor sit amet nulla. Morbi at congue sapien, nec fringilla erat. Aliquam mollis, arcu nec blandit tempor, nulla erat dapibus lectus, ac pulvinar libero sapien vitae velit. Aenean luctus mollis lorem, nec imperdiet erat vehicula ut. Pellentesque congue nunc dolor, eget dapibus tellus ullamcorper in. Etiam in libero velit. Pellentesque quis imperdiet lacus, vitae varius leo. Sed tellus dolor, suscipit nec lobortis at, tempus eu erat. Vestibulum condimentum auctor nibh, a malesuada sapien fringilla vitae. Suspendisse in maximus justo.</p>


         
        </div>
        
        


        <div id="footer">
            Regatnik - zaplanuj regaty! &copy; Wszelkie prawa zatrzeżone
        </div>

    </div>
</body>

</html>