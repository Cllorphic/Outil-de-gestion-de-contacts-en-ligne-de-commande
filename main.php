<?php

require "bdd.php";

// 1️⃣ On crée la connexion UNE FOIS
$db = new DBConnect();
$pdo = $db->getPDO();

// (optionnel mais demandé par tes consignes)
var_dump($pdo);

// 2️⃣ Boucle CLI
while (true) {
    $line = readline("Entrez votre commande : ");

    if ($line === "list") {

        // ⬇️ TON CODE VA ICI ⬇️
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
