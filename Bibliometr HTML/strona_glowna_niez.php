
<?php
@session_start();

  if(isset($_SESSION['loggedin'])) {
        header('Location:  Strona_glowna_z.php');
        exit();
  }
?>

<!doctype html>

<html>
    <head>
        <title>SKPN</title>
        <link rel="stylesheet" href="./css/template.css">
        <link rel="stylesheet" href="./css/strona_glowna_niez.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>

    <body>
        <div id="box">

            <header>
                <h3>SYSTEM BIBLIOMETRYCZNY</h3>
            </header>

            <div id="divider"></div>

            <div id="content">
                <div id="prawa">
                    <a class="button" href ="login.php">Logowanie</a>
                    <a class="button" href="reje.php">Rejestracja</a>
                </div>	
              
                <a href="strona_glowna_niez.php"><img class="logo" src="IMG/logoskpn.png" alt="logo" /></a>

                <div id="wyszukiwarka">
                    <div class="wysz-part">
                        <input type="button" value = "Szukaj" class="button1" id="szukaj">
                    </div> 

                    <div class="wysz-part">
                        <a class="button1">eksportuj</a>
                        <a class="button1" onclick="panelToggle()">filtrowanie</a>
                    </div>

                    <div id="panel" action ="search.php">
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

                    <form type ="post">
                        Autor:<br /><input type="text" id = "autor" name ="autor-search"><br />
                        Nazwa publikacji:<br><input type="text" id = "nazwa" name ="nazwa-search"><br>
                        Data:<br>
                        od:<input type="date" id="data" name ="dataOd-search">
                        do:<input type="date" id="data" name ="dataDo-search"><br>
                        DOI:<br><input type="text" id="DOI" name ="doi-search"><br>
                        Tytuł:<br><input type="text" id="tytul" name ="tytul-search"><br>
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

                <?php 

                    require_once "connect.php";

                    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
                    if ($polaczenie->connect_errno != 0) {
                        throw new Exception(mysqli_connect_errno());
                    } else {

                        $nazwaSearch;
                        $userID;
                        $userImie;
                        $userNazwisko;
                        $doiSearch;
                        $tytulNaukowy;
                        $userIdSearch;
                        $dataOdSearch;
                        $dataDoSearch;

                        if (isset($_GET['autor-search']) && $_GET['autor-search'] != "") {
                            $autorSearch = $_GET['autor-search'];
                            $autorSearchImie = substr($autorSearch,0,strpos($autorSearch, ' '));
                            $autorSearchImieLength = strlen($autorSearchImie) + 1;
                            $autorSearchNazwisko = substr($autorSearch, $autorSearchImieLength);

                            $userImie = $autorSearchImie;
                            $userNazwisko = $autorSearchNazwisko;

                            $query = $polaczenie->query("SELECT id_user FROM uzytkownik WHERE imie = '$autorSearchImie' AND nazwisko = '$autorSearchNazwisko'");
                            $fetch = $query->fetch_assoc();
                            $userID = $fetch['id_user'];
                            $userIdSearch = "id_user = '$userID'";

                            $query->free_result();
                        }

                        if (isset($_GET['nazwa-search']) && $_GET['nazwa-search'] != "") {
                            $nazwaPublikacji = $_GET['nazwa-search'];
                            if (!isset($userID)) {
                                $query = $polaczenie->query("SELECT id_user FROM publikacja WHERE tytul = '$nazwaPublikacji'");
                                $fetch = $query->fetch_assoc();
                                $userID = $fetch['id_user'];
                                $userIdSearch = "id_user = '$userID'";

                                $query->free_result();
                            }
                            $nazwaSearch = "tytul = '$nazwaPublikacji'";
                        }

                        if (isset($_GET['dataOd-search']) && $_GET['dataOd-search'] != "") {
                            $dataod = $_GET['dataOd-search'];
                            $dataOdSearch = "data_publikacji >= '$dataod'"; 
                        }

                        if (isset($_GET['dataDo-search']) && $_GET['dataDo-search'] != "") {
                            $datado = $_GET['dataDo-search'];
                            $dataDoSearch = "data_publikacji < '$datado'"; 
                        }

                        if (isset($_GET['doi-search']) && $_GET['doi-search'] != "") {
                            $doi = $_GET['doi-search'];
                            if (!isset($userID)) {
                                $query = $polaczenie->query("SELECT id_user FROM publikacja WHERE doi = '$doi'");
                                $fetch = $query->fetch_assoc();
                                $userID = $fetch['id_user'];
                                $userIdSearch = "id_user = '$userID'";
                                
                                $query->free_result();
                            }

                            $doiSearch = "doi = '$doi'";
                        }

                        if (isset($_GET['tytul-search']) && $_GET['tytul-search'] != "") {
                            $tytul = $_GET['tytul-search'];
                            if (!isset($userID)) {
                                $query = $polaczenie->query("SELECT id_user FROM publikacja WHERE tytul_naukowy = '$tytul'");
                                $fetch = $query->fetch_assoc();
                                $userID = $fetch['id_user'];
                                $userIdSearch = "id_user = '$userID'";
                                
                                $query->free_result();
                            }
                            $tytulNaukowy = "tytul_naukowy = '$tytul'";
                        }

                        if (isset($userID) && (!isset($userImie) || !isset($userNazwisko))) {
                            $query = $polaczenie->query("SELECT imie, nazwisko FROM uzytkownik WHERE id_user = '$userID'");
                            $fetch = $query->fetch_assoc();
                            $userImie = $fetch['imie'];
                            $userNazwisko = $fetch['nazwisko'];
                        }

                        if (isset($userID) || isset($nazwaSearch) || isset($doiSearch) || isset($tytulNaukowy) || isset($dataOdSearch) || isset($dataDoSearch)) {

                            $varArray = [];
                            
                            if (isset($userID)) {
                                array_push($varArray, $userIdSearch);
                            }
                            if (isset($nazwaSearch)) {
                                array_push($varArray, $nazwaSearch);
                            }
                            if (isset($doiSearch)) {
                                array_push($varArray, $doiSearch);
                            }
                            if (isset($tytulNaukowy)) {
                                array_push($varArray, $tytulNaukowy);
                            }
                            if (isset($dataDoSearch)) {
                                array_push($varArray, $dataDoSearch);
                            }
                            if (isset($dataOdSearch)) {
                                array_push($varArray, $dataOdSearch);
                            }

                            $collectQuery = "SELECT * FROM publikacja WHERE";
                            $checkForAnd = 0;
                            foreach ($varArray as $element) {
                                if (isset($element)) {
                                    if ($checkForAnd == 0) {
                                        $p = " ".$element;
                                        $checkForAnd += 1;
                                    } else {
                                        $p = " AND ".$element;
                                    }
                                }
                                $collectQuery = $collectQuery.$p;
                            }
                        }
                        
                        if (isset($userID) || isset($dataOdSearch) || isset($dataDoSearch)) {
                            $query = $polaczenie->query($collectQuery);

                            foreach($query as $newquery) {

                                $test = $newquery['id_user'];
                                echo $test;

                                if(isset($dataOdSearch) || isset($dataDoSearch)) {
                                    $query3 = $polaczenie->query("SELECT imie, nazwisko FROM uzytkownik WHERE id_user = '$test'");
                                    $fetch3 = $query3->fetch_assoc();

                                    $userImie = $fetch3['imie'];
                                    $userNazwisko = $fetch3['nazwisko'];

                                    $query3->free_result();
                                }

                                if((!isset($userImie) || !isset($userNazwisko)) || ( $userImie == "" || $userNazwisko == "")) {
                                    
                                    if (isset($userIdSearch)) {
                                        $query2 = $polaczenie->query("SELECT imie, nazwisko FROM uzytkownik WHERE $userIdSearch");
                                        $fetch = $query2->fetch_assoc();
                                        $userImie = $fetch['imie'];
                                        $userNazwisko = $fetch['nazwisko'];
    
                                        $query2->free_result();
                                    }
                                } 
                                echo '
                                <tr>
                                    <td><input type="checkbox" checked></td>
                                    <td>'.$userImie.' '.$userNazwisko.'</td>
                                    <td>'.$newquery['tytul'].'</td>
                                    <td>'.$newquery['data_publikacji'].'</td>
                                    <td>'.$newquery['doi'].'</td>
                                    <td>'.$newquery['tytul_naukowy'].'</td>
                                    <td>'.$newquery['punkty'].'</td>
                                </tr>';
                            }
                        }
                }
                
                ?>
            </table>
        </div>
        <footer>
            <p>Pingwiny&copy; 2019 ZUT PSIAI</p>
        </footer>

        <script src="js/scripts.js"></script>
    </body>
</html>