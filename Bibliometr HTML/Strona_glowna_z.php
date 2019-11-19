<?php 
  @session_start();

  if(!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == false) {
    header('Location:  login.php');
    exit();
  }
?>

<!doctype html>

<html>
    <head>
        <title>SKPN - System katalogowania publikacji naukowych</title>
        <link rel="stylesheet" href="./css/template.css">
        <link rel="stylesheet" href="./css/strona_glowna_z.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script> 
        $(document).ready(function(){
            $("#szukaj").click(function(){
                $("#searchbars").slideToggle("fast");
            });
        });
        </script>
    </head>

    <body>
        <div id="box">

            <header>
                <h3>SYSTEM BIBLIOMETRYCZNY</h3>
                <?php
                    if($_SESSION['imie'] !== null && $_SESSION['nazwisko'] !== null) {
                        echo '<div class = "logged-in-as"><p>Jesteś zalogowany jako: '.$_SESSION['imie'] . ' ' . $_SESSION['nazwisko'] . '</p></div>'; 
                    }
                ?>
            </header>

            <div id="divider"></div>

            <div id="content">
                <div id="prawa">
                    <a class="button" href ="panel_uz.php">PANEL UŻYTKOWNIKA</a>
                    <a class="button" onclick="panelToggleE()">EDYTUJ PROFIL</a>
                    <a class="button" href="logout.php">WYLOGUJ SIĘ</a>
                    
                    <div id="panel_edycja">
                        <p onclick="panelToggleE()"><i class="fa fa-times" aria-hidden="true"></i></p>
                        <input required type="text" placeholder="Imię" id="imie"><br>
                        <input required type="text" placeholder="Nazwisko" id="nazwisko"></br>
                        <input required type="text" placeholder="E-mail" id="email"></br>
                        <input required type="text" placeholder="Hasło" id="haslo"></br>
                        <input required type="text" placeholder="Uczelnia" id="uczelnia"></br>
                        <input type="submit" value="ZAPISZ">
                    </div>
                </div>	
              
              
            <a href="Strona_glowna_z.php"><img src="IMG/logoskpn.png"></a>

            <div id="wyszukiwarka">
                    <div class="wysz-part">
                        <input type="button" value = "Szukaj" class="button1" id="szukaj">
                    </div> 

                    <div class="wysz-part">
                        <a class="button1">eksportuj</a>
                        <a class="button1" onclick="panelToggle()">filtrowanie</a>
                    </div>

                    <div id="panel">
                        <p onclick="panelToggle()"><i class="fa fa-times" aria-hidden="true"></i></p>
                            <span><input type="checkbox">Autor</span>
                            <span><input type="checkbox">Nazwa publikacji</span>
                            <span><input type="checkbox">Data</span>
                            <span><input type="checkbox">DOI</span>
                            <span><input type="checkbox">Tytuł</span>
                            <span><input type="checkbox">Punkty</span>
                    </div>
                </div>
                <div id="searchbars">
                    <form>
                        Autor:<br><input type="text" id = "autor"><br>
                        Nazwa publikacji:<br><input type="text" id = "nazwa"><br>
                        Data:<br>
                        od:<input type="date" id="data">
                        do:<input type="date" id="data"><br>
                        DOI:<br><input type="text" id="DOI"><br>
                        Tytuł:<br><input type="text" id="tytul"><br>
                        <input type="submit" value="Szukaj" class="button1">
                    </form>
                </div>

                
            
                
			<table id = "tabela">
                <tr>
                    <td class ="hidden-in-table"></td>
                    <td><input type="checkbox" checked></td>
                    <td><input type="checkbox" checked></td>
                    <td><input type="checkbox" checked></td>
                    <td><input type="checkbox" checked></td>
                    <td><input type="checkbox" checked></td>
                    <td><input type="checkbox" checked></td>
                </tr>
                <tr>
                    <td class ="hidden-in-table"></td>
                    <td>Autor</td>
                    <td>Nazwa pub.</td>
                    <td>Data</td>
                    <td>DOI</td>
                    <td>Tytuł</td>
                    <td>Punkty</td>
                </tr>
                <tr>
                    <td><input type="checkbox" checked></td>
                    <td>Paweł Krawczak</td>
                    <td>Moja pierwsza publikacja dotycząca funkcjonalności bibliometrów w życiu codziennym</td>
                    <td>11.11.2011</td>
                    <td>ZUT</td>
                    <td>Doktor rehabilitowany</td>
                    <td>100/100</td>
                </tr>
			</table>
        </div>
        <footer>
            <p>Pingwiny&copy; 2019 ZUT PSIAI</p>
        </footer>

        <script src="js/scripts.js"></script>
    </body>
</html>