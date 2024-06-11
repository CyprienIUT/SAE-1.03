<?php
    // CONSTANTES
    define("COMM_BEGIN", "/**");
    define("COMM_INDENT","* ");
    define("COMM_END", "*/");
    define("EOL", ";");
    define("EMPTY_VALUE", "---valeur_vide---");
    define("LIST_IDENTIFIER", "- ");

    define("STRUC_BEGIN", "typedef struct");
    define("STRUC_BEGIN_END", " {");
    define("STRUC_END", "}");

    define("DEFINE_BEGIN", "#define");

    define("CONST_BEGIN", "const ");

    define("FONCT_BEGIN","\brief");
    define("FONCT_DETAIL","\\detail ");
    define("FONCT_RETURN","\\return ");
    define("FONCT_PARAM","\\param ");
    define("FONCT_END","{");
        
    // Tableaux contenant les résultats
    $generalComment = "";
    $tabDefines = [];
    $tabStruc = [];
    $tabGlobles = [];
    $tabFonctions = [];

    // *********************************** Général ******************************

    function getInlineComm($line) {
        $debutComm = strpos($line, COMM_BEGIN)+strlen(COMM_BEGIN);
        $finComm = strpos($line, COMM_END)-$debutComm;

        return trim(substr($line, $debutComm, $finComm));
    }

    function generalCommentBegins($line) {
        return str_contains($line, COMM_BEGIN);
    }

    function generalCommentEnds($line) {
        return str_contains($line, COMM_END);
    }

    function getGeneralComment($line) {
        return trim(substr($line, strlen(COMM_INDENT)));
    }

    // *********************************** Structures ******************************

    function strucBegin($line) {
        return str_contains($line, STRUC_BEGIN);
    }

    function strucEnd($line) {
        return str_contains($line, STRUC_END);
    }

    function getStrucName($line) {
        $debutStrucName = strpos($line, STRUC_END)+strlen(STRUC_END);
        $finStrucName = strpos($line, EOL)-$debutStrucName;

        return trim(substr($line, $debutStrucName, $finStrucName));
    }

    function getStrucParam($line) {
        return ["param" => trim(substr($line, 0, strpos($line, EOL))), "comm" => getInlineComm($line)];
    }

    // *********************************** Defines ******************************

    function defineBegin($line) {
        return str_contains($line, DEFINE_BEGIN);
    }   

    function getDefineAssociation($line) {
        $debutAsso = strpos($line, DEFINE_BEGIN)+strlen(DEFINE_BEGIN);
        $finAsso = strpos($line, COMM_BEGIN)-$debutAsso;

        $asso = explode(" ", trim(substr($line, $debutAsso, $finAsso)));

        return ["name" => $asso[0], "value" => $asso[1]];
    }

    function getDefine($line) {
        return ["asso" => getDefineAssociation($line), "comm" => getInlineComm($line)];
    }

    // *********************************** Globales ******************************

    function globaleBegin($line) {
        global $inFonction, $inStruc;
        return ($inFonction == false && $inStruc == false && str_contains($line, COMM_BEGIN) && str_contains($line, COMM_END)) || str_contains($line, CONST_BEGIN);
    }   

    function getGlobaleType($line) {
        return trim(substr($line, 0, strpos($line, " ")));
    }

    function getGlobaleName($line) {
        $result = EMPTY_VALUE;
        if (str_contains($line, "=")) {
            $result = substr($line, strpos($line, " "), strpos($line, "=")-strpos($line, " "));
        } else {
            $result = substr($line, strpos($line, " "), strpos($line, EOL)-strpos($line, " "));
        }
        return trim($result);
    }

    function getGlobaleValue($line) {
        $result = EMPTY_VALUE;
        if (str_contains($line, "=")) {
            $result = substr($line, strpos($line, "=")+1, strpos($line, EOL)-strpos($line, "="));
        }
        return trim($result);
    }
    
    function getGlobale($line) {
        if (str_contains($line, CONST_BEGIN)) {
            $line = substr($line, strlen(CONST_BEGIN));
        }
        return ["type" => getGlobaleType($line), "name" => getGlobaleName($line), "value" => getGlobaleValue($line), "comm" => getInlineComm($line)];
    }

    // *********************************** Fonctions ******************************

    function fonctionBegin($line){
        return str_contains($line, FONCT_BEGIN);
    }
    
    function getFonctionBrief($line){
        return trim(substr($line, strlen(COMM_INDENT . FONCT_BEGIN)+1));
    }

    function fonctionEnd($line){
        return str_contains($line, FONCT_END);
    }
    
    function getFonctionName($line) {
        return substr($line, strpos($line, " ")+1, strpos($line, "(") - strpos($line, " ") - 1);
    }

    function fonctionDetail($line){
        return str_contains($line, FONCT_DETAIL);
    }
    
    function getFonctionDetail($line) {
        $result = "";

        if (str_contains($line, FONCT_DETAIL)) {
            $result = trim(substr($line, strlen(COMM_INDENT . FONCT_DETAIL)+1));
        } else if (true) {
            $result = trim(substr($line, strlen(COMM_INDENT)+1));
        }

        if ($result == "") {
            $result = "<br><br>";
        }
        return $result;
    }
    
    function fonctionReturn($line) {
        return str_contains($line, FONCT_RETURN);
    }

    function getFonctionReturn($line) {
        $result = ["type" => "", "comm" => ""];
        $trimed_line = trim(substr($line, strlen(COMM_INDENT . FONCT_DETAIL)+1));

        $result["type"] = substr($trimed_line, 0, strpos($trimed_line, " "));
        $result["comm"] = substr($trimed_line, strlen($result["type"])+1);

        return $result;
    }
    
    function fonctionParam($line) {
        return str_contains($line, FONCT_PARAM);
    }

    function getFonctionParam($line) {
        $result = [
            "type" => "test",
            "name" => trim("test : $line"),
            "comm" => "test"
        ];
        $trimed_line = trim(substr($line, strlen(COMM_INDENT . FONCT_PARAM)+1));

        $result["type"] = substr($trimed_line, 0, strpos($trimed_line, " "));
        $trimed_line = substr($trimed_line, strpos($trimed_line, " ")+1);
        $result["name"] = substr($trimed_line, 0, strpos($trimed_line, " "));
        $result["comm"] = substr($trimed_line, strlen($result["name"])+1);

        return $result;
    }


    for ($i = 1; $i <= 3; $i++) {

        $lines = file("src" . $i . ".c");

        $generalCommentDone = false; 
        $inGeneralComment = false;
        $inStruc = false;
        $inFonction = false;
        $inDetail = false;
        
        foreach ($lines as $index => $line) {
            if (!$generalCommentDone && generalCommentBegins($line)) {
                $inGeneralComment = true;
            } else if ($inGeneralComment) {
                if (generalCommentEnds($line)) {
                    $inGeneralComment = false;
                    $generalCommentDone = true;
                } else {
                    $generalComment .= " " . getGeneralComment($line);
                }
            } else if (strucBegin($line)) {
                $inStruc = true;
                $structure = ["name" => EMPTY_VALUE,
                "comm" => EMPTY_VALUE,
                "params" => []
            ];
            } else if($inStruc) {
                if (strucEnd($line) == false) {
                    $strucParam = getStrucParam($line);
                    $structure["params"][$strucParam["param"]] = $strucParam;
                } else {
                    $structure["name"] = getStrucName($line);
                    $structure["comm"] = getInlineComm($line);
                    $tabStruc[$structure["name"]] = $structure;
                    $inStruc = false;
                }
            } else if (defineBegin($line)) {
                $define = getDefine($line);
                $tabDefines[$define["asso"]["name"]] = $define;
                
            } else if (globaleBegin($line)) {
                $globale = getGlobale($line);
                $tabGlobales[$globale["name"]] = $globale;
            } else if (fonctionBegin($line)) { 
                $inFonction = true;
                $fonction = ["name" => EMPTY_VALUE,
                "brief" => getFonctionBrief($line),
                "detail" => [],
                "return" => EMPTY_VALUE,
                "params" => []];
            } else if ($inFonction) {
                if (fonctionDetail($line)) {
                    $inDetail = true;
                    $fonction["detail"][] = getFonctionDetail($line); 
                } else if (fonctionReturn($line)) { 
                    $inDetail = false;
                    $fonction["return"] = getFonctionReturn($line);
                } else if (fonctionParam($line)) { 
                    $inDetail = false;
                    $param = getFonctionParam($line);
                    $fonction["params"][$param["name"]] = $param;
                } else if (fonctionEnd($line)) {
                    $inDetail = false;
                    $inFonction = false; 
                    $fonction["name"] = getFonctionName($line);
                    $fonction["detail"] = $fonction["detail"];
                    $tabFonctions[$fonction["name"]] = $fonction;
                } else if ($inDetail) {
                    $tempDetail = getFonctionDetail($line);
                    if (substr($tempDetail, 0, strlen(LIST_IDENTIFIER)) == LIST_IDENTIFIER) {
                        $fonction["detail"][] = $tempDetail;
                    } else {
                        $fonction["detail"][count($fonction["detail"])-1] .= " " . $tempDetail;
                    }
                } 
            }
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <meta name="author" content="1A21">
    <style>
        body>header {
            height: 98vh;
            width: 100vw;

            border: 1px solid black;
            border-radius: 25px;
            
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        code {
            background-color: rgb(192, 192, 192);
        }

        code>span {
            background-color: white;
            font-style: italic;
        }

        main>h2, article>h2, section>h2, main>header>h2 {
            border-bottom: 1px solid black;
        }
    </style>
    <title>Documentation Technique</title>
</head>
<body>
    <header>
<?php
    $config = file("config");
    $date = date("d/m/Y");
    foreach ($config as $configLine) {
        $iConfig = 0;
        $estPasse = false;
        $configTemp = "";
        $contenu = "";
        while($iConfig < strlen($configLine)){
            if($configLine[$iConfig] == '='){
                $estPasse = true;
            }
            else if($estPasse == false){
                $configTemp = $configTemp . $configLine[$iConfig];
            }
            else if($estPasse == true){
                $contenu = $contenu . $configLine[$iConfig];
            }
            $iConfig++;
        }

        if($configTemp == "PRODUIT"){
?>
        <h1>
<?php
            echo "\t\t\t$contenu";
?>
        </h1>
<?php
        }
        else if($configTemp == "CLIENT"){
?>
        <h2>
<?php
            echo "\t\t\t$contenu";
?>
        </h2>
<?php
        }
        else if($configTemp == "VERSION"){
?>
        <h3>
<?php
            echo "\t\t\t Version $contenu - $date";
?>
        </h3>
<?php
        }   
    }
?>
    </header>
    <main>
        <header>
            <h2>
                Description du code
            </h2>
            <p><?php echo $generalComment ?></p>
        </header>

        <article>
            <h2>
                DEFINES
            </h2>
            <ul>
                <?php 
                    foreach($tabDefines as $aDefine) {
                        echo "<li>\n";
                        echo "<code>\n";
                        echo $aDefine["asso"]["name"] . " " . $aDefine["asso"]["value"];
                        echo "<span> (" . $aDefine["comm"] . ")</span>\n";
                        echo "</code>\n";
                        echo "</li>\n";
                    }
                ?>
            </ul>
        </article>

        <section>
            <h2>
                STRUCTURES
            </h2>
            <?php
                foreach($tabStruc as $aStruc) {
                    echo "<article>\n";
                    echo "<h3>" . $aStruc["name"] . "</h3>\n";

                    echo "<p>" . $aStruc["comm"] . "</p>\n";

                    echo "<ul>\n";

                    foreach($aStruc["params"] as $aParam) {
                        echo "<li>\n";
                        echo "<code>\n";
                        echo $aParam["param"] . "<span> (" . $aParam["comm"] . ")</span>\n";
                        echo "</code>\n";
                        echo "</li>\n";
                    }

                    echo "</ul>\n";
                    echo "</article>\n";
                }
            ?>
        </section>

        <article>
            <h2>
                GLOBALES
            </h2>

            <ul>
                <?php
                    foreach($tabGlobales as $aGlobale) {
                        echo "<li>\n";
                        echo "<code>\n";
                        echo $aGlobale["type"] . " " . $aGlobale["name"];
                        if ($aGlobale["value"] != EMPTY_VALUE) {
                            echo " = " . $aGlobale["value"];
                        }
                        echo "<span> (" . $aGlobale["comm"] . ")</span>";
                        echo "</code>\n";
                        echo "</li>\n";
                    }
                ?>
            </ul>
        </article>

        <section>
            <h2>FONCTIONS</h2>

            <?php
                foreach($tabFonctions as $aFonction) {
                    echo "<article>\n";
                    echo "<h3>" . $aFonction["name"] . "</h3>\n";
                    $inList = false;
                    foreach($aFonction["detail"] as $element) {
                        if ($inList) {
                            if (substr($element, 0, strlen(LIST_IDENTIFIER)) == LIST_IDENTIFIER) {
                                $element = str_replace("- ", "", $element);
                                echo "<li>\n";
                                echo "<p>" . $element . "</p>\n";
                                echo "</li>\n";
                            } else {
                                echo "</ul>\n";
                                echo "<p>" . $element . "</p>\n"; 
                            }
                        } else if (substr($element, 0, strlen(LIST_IDENTIFIER)) == LIST_IDENTIFIER) {
                            $element = str_replace("- ", "", $element);
                            echo "<ul>\n";
                            $inList = true;
                            echo "<li>\n";
                            echo "<p>" . $element . "</p>\n";
                            echo "</li>\n";
                        } else {
                            echo "<p>" . $element . "</p>\n"; 
                        }
                    }

                    if ($inList) {
                        echo "</ul>\n";
                        $inList = false;
                    }

                    echo "<h4>Paramètres</h4>";

                    echo "<ul>\n";

                    foreach($aFonction["params"] as $aParam) {
                        echo "<li>\n";
                        echo "<code>\n";
                        echo $aParam["type"] . " ". $aParam["name"] . "<span> (" . $aParam["comm"] . ")</span>\n";
                        echo "</code>\n";
                        echo "</li>\n";
                    }

                    echo "</ul>\n";

                    echo "<h4>Valeur Renvoyé</h4>";
                    
                    echo "<ul>\n";
                    
                    echo "<li>\n";
                    echo "<code>\n";
                    if ($aFonction["return"] != EMPTY_VALUE) {
                        echo $aFonction["return"]["type"] . "<span> (" . $aFonction["return"]["comm"] . ")</span>\n";
                    } else {
                        echo "none";
                    }
                    echo "</code>\n";
                    echo "</li>\n";

                    echo "</ul>\n";
                    echo "</article>\n";
                }
            ?>
        </section>
    </main>
</body>
</html>
