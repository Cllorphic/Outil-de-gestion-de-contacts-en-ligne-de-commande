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

class Contact
{
    private ?int $id = null;
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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
        return "Contact #" . ($this->id ?? "null") . " : " . ($this->name ?? "Contact sans nom");
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

        $contacts = [];

        foreach ($rows as $row) {
            $contact = new Contact();
            $contact->setId(isset($row['id']) ? (int)$row['id'] : null);
            $contact->setName($row['name'] ?? null);
            $contacts[] = $contact;
        }

        return $contacts;
    }

    public function findById(int $id): ?Contact
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contact WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Test immédiat demandé
        var_dump($row);

        if ($row === false) {
            return null;
        }

        $contact = new Contact();
        $contact->setId(isset($row['id']) ? (int)$row['id'] : null);
        $contact->setName($row['name'] ?? null);

        return $contact;
    }

    public function create(string $name): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO contact (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM contact WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

require_once 'Command.php';

$db = new DBConnect();
$pdo = $db->getPDO();

echo "Connection BDD OK\n";

while (true) {
    $line = readline("Entrez votre commande : ");

    if ($line === "list") {
        (new Command())->list();
        continue;
    }

    if (preg_match('/^detail\s+(\d+)$/', $line, $m)) {
        (new Command())->detail((int)$m[1]);
        continue;
    }

    if (preg_match('/^create\s+(.+)$/', $line, $m)) {
        (new Command())->create(trim($m[1]));
        continue;
    }

    if (preg_match('/^delete\s+(\d+)$/', $line, $m)) {
        (new Command())->delete((int)$m[1]);
        continue;
    }
}
