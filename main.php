<?php

class DBConnect
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=127.0.0.1;dbname=carnet_adresses;charset=utf8mb4',
            'root',
            ''
        );

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}

class ContactManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM contact");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Test immédiat demandé
        var_dump($rows);

        return $rows;
    }
}

class Contact
{
    private ?int $id = null;
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function toString(): string
    {
        return "Contact(id=" . ($this->id ?? "null") . ", name=" . ($this->name ?? "null") . ")";
    }
}

$db = new DBConnect();
$pdo = $db->getPDO();

echo "Connection BDD OK\n";

$contactManager = new ContactManager($pdo);

while (true) {
    $line = readline("Entrez votre commande : ");
    if ($line === "list") {
        $contacts = $contactManager->findAll();
    }
}
