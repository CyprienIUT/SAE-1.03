<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="1A21">
    <style>
        body>header {
            height: 100vh;
            width: 100%;

            border: 1px solid black;
            border-radius: 25px;
            height: 98vh;
            width: 100%;
            display: flex;
            flex-direction :column ;
            justify-content: center;
            align-items: center;
        }

        section {
            height: 98vh;
        }
        
        p>code {
            background-color: rgb(60, 60, 60);
            color: rgb(255, 190, 137);
            display: inline-block;
            padding: 2px;
            border-radius: 5px;
        }

        pre>code {
            background-color: rgb(60, 60, 60);
            color: rgb(255, 190, 137);
            display: inline-block;
            padding: 10px;
            border-radius: 5px;
        }

        pre>code>span {
            background-color: rgb(60, 60, 60);
            color: rgb(137, 192, 255);
            display: inline-block;
            padding: 10px;
            border-radius: 5px;
        }
        
        main>h2,
        article>h2,
        section>h2,
        main>header>h2 {
            border-bottom: 1px solid black;
        }

        table, td,th {
            border: 1px solid #000;
            border-collapse: collapse;
            padding : 5px;
        }

        [class *="bold"] {
            font-weight: bold;
        }

        [class *="italic"] {
            font-style: italic;
        }
    </style>
    <title>Documentation Utilisateur</title>
</head>

<body>
    <header>
<?php
    $configFile = file("config");
    $date = date("d/m/Y");
    foreach ($configFile as $configLine) {
        $line = explode("=", $configLine);
        if($line[0] == "PRODUIT"){
            $produit = $line[1];
        }
        else if($line[0] == "CLIENT"){
            $client = $line[1];
        }
        else if($line[0] == "VERSION"){
            $version = $line[1];
        }
    }   
?>
        <h1>
<?php
    echo "\t\t\t$produit";
?>
        </h1>
        <h2>
<?php
    echo "\t\t\t$client";
?>
        </h2>
        <h3>
<?php
    echo "\t\t\tVersion $version - $date";
?>
        </h3>
    </header>
    <main>
<?php

$lines = file("DOC_UTILISATEUR.md");

$tableau = array();

$mainIndice = 0;

$isNewSection = false;
$isTable = false;
$isCode = false;
$isList = false;
$carSpecial = false;

foreach($lines as $line){

    /*Booléen detection en cours*/

    /*Générer les titres*/
    if ($line[0] == "#") {
        $i = 0;
        while ($line[$i] == "#") {
            $line[$i] = " ";
            $i++;
        }
        if ($i == 2) {
            if (!$isNewSection)
            {
                $isNewSection = true;
            } 
            else
            {
?> 
        </section>
<?php
            }
?> 
        <section>
<?php
        }
        echo "<h$i> $line </h$i>";
    }

    /*Liste*/ 
    else if($line[0] == "-") {
        if($isList == false){
            $isList = true;
?>
            <ul>
<?php
        }
?>
                <li>
<?php
        for ($i = 2; $i < strlen($line) - 1 ; $i++) 
            echo $line[$i];
?>
                </li>
<?php
    }
    else if(($isList == true)){
        $isList = false;
?>
            </ul>
<?php
    }

    /*Tableau*/
    elseif ($line[0] == "|") {
        $isTable = true;
        if (strpos($line,"-") == false) {
            $tableau[] = array_slice(explode("|", $line), 1, 4);
        }

    }
    else if ($isTable) {
        construireTableau($tableau);
        $isTable = false;
        $tableau = array();
    }

    /*Bloc de Code*/
    else if((strlen($line) >= 3) && ($line[0].$line[1].$line[2] == "```") && ($isCode == false)){
?>
            <code><pre>
<?php
        $isCode = true;
    }
    else if($isCode == true){
        if((strlen($line) >= 3) && ($line[0].$line[1].$line[2] == "```")){
?>
            </code></pre>
<?php
            $isCode = false;
        }
        else {
            echo $line;
        }
    }
    else if($line[0] == '['){
        echo "\n";
        modifChaine($line);
        echo $line;
    }

    /*Paragraphe*/
    else if($line[0] != "\n"){
?>
            <p> <?php modifChaine($line); echo "    $line"; ?> </p>
<?php
    }

    /*Passe à la ligne suivante*/ 
}

function construireTableau($tab){
    $thExist = false;
?>
            <table>
<?php
    foreach ($tab as $vals) {
?>
                <tr>
<?php
        foreach ($vals as $val) {
            modifChaine($val);
            if($thExist == false){
?>
                    <th> <?php echo $val; ?> </th>
<?php
            }
            else{
?>
                    <td> <?php echo $val; ?> </td>
<?php
            }
        }
?>
                </tr>
<?php
        $thExist = true;
    }
?>
            </table>
<?php
}

function modifChaine(&$chaine){
    $isStrongChaine = false;
    $isItalicsChaine = false;
    $isLink = false;
    $isHref = false;
    $temp = "";
    $link = "";
    $href = "";
    for ($i = 0 ; $i < strlen($chaine) ; $i++) { 
        /*lien*/
        if(($chaine[$i] == '[') && ($isLink == false)){
            $isLink = true;
            $link = "";
        }
        else if($isLink == true){
            if($chaine[$i] == ']'){
                $isLink = false;
            }
            else{
                $link = $link . $chaine[$i];
            }
        }
        else if(($chaine[$i] == '(') && ($isHref == false)){
            $isHref = true;
            $href = "";
        }
        else if($isHref == true){
            if($chaine[$i] == ')'){
                $isHref = false;
                modifChaine($link);
                $temp = $temp . "<a href=\"" . $href . "\">" . $link . "</a>";
            }
            else{
                $href = $href . $chaine[$i];
            }
        }
        /*gras*/
        else if(($i+1 < strlen($chaine)) && ($chaine[$i] == '*') && ($chaine[$i+1] == '*') && ($isStrongChaine == false)){
            $temp = $temp . "<span class=\"bold\">";
            $isStrongChaine = true;
            $carSpecial = true;
            $i++;
        }
        else if(($i+1 < strlen($chaine)) && ($chaine[$i] == '*') && ($chaine[$i+1] == '*') && ($isStrongChaine == true)){
            $temp = $temp . "</span>";
            $isStrongChaine = false;
            $i++;
        }
        /*italique*/
        else if(($chaine[$i] == '_') && ($isItalicsChaine == false)){
            $temp = $temp . "<span class=\"italic\">";
            $isItalicsChaine = true;
        }
        else if(($chaine[$i] == '_') && ($isItalicsChaine == true)){
            $temp = $temp . "</span>";
            $isItalicsChaine = false;
        }
        else{
            $temp = $temp . $chaine[$i];
        }
    }
    $chaine = $temp;
}
?>
        </section>
    </main>
</body>

</html>
