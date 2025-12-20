<?php

require "bdd.php";


$db = new DBConnect();
$pdo = $db->getPDO();


var_dump($pdo);


while (true) {
    $line = readline("Entrez votre commande : ");

    if ($line === "list") {

       
        $stmt = $pdo->query("SELECT * FROM contact");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $row["id"] . " | "
               . $row["name"] . " | "
               . $row["email"] . " | "
               . $row["phone_number"] . "\n";
        }

    } elseif ($line === "exit") {
        break;
    } else {
        echo "Commande inconnue\n";
    }
}
