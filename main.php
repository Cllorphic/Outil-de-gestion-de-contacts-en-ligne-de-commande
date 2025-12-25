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
    private int $id;
    private string $name;
    private string $email;
    private string $phoneNumber;

    public function __construct(
        int $id,
        string $name,
        string $email,
        string $phoneNumber
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPhoneNumber(): string { return $this->phoneNumber; }

    public function __toString(): string
    {
        return "Contact #{$this->id} : {$this->name} | {$this->email} | {$this->phoneNumber}";
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
            $contacts[] = new Contact(
                (int)$row['id'],
                $row['name'],
                $row['email'],
                $row['phone_number']
            );
        }

        return $contacts;
    }

    public function findById(int $id): ?Contact
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contact WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new Contact(
            (int)$row['id'],
            $row['name'],
            $row['email'],
            $row['phone_number']
        );
    }

    public function create(string $name, string $email, string $phoneNumber): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO contact (name, email, phone_number)
             VALUES (:name, :email, :phone)"
        );

        $stmt->execute([
            'name'  => $name,
            'email' => $email,
            'phone' => $phoneNumber
        ]);
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

echo "Connexion BDD OK\n";

while (true) {
    $line = readline("Entrez votre commande : ");

    if($line === "help"){
        echo "Commandes disponibles :" .PHP_EOL;
        echo "- list" . PHP_EOL;
        echo "- detail" . PHP_EOL;
        echo "- create <'nom'> <email> <'telephone'>" . PHP_EOL;
        echo "- delete" . PHP_EOL;
        echo "- exit" . PHP_EOL;
        continue;
    }

    if ($line === "list") {
        (new Command())->list();
        continue;
    }

    if (preg_match('/^detail\s+(\d+)$/', $line, $m)) {
        (new Command())->detail((int)$m[1]);
        continue;
    }

    if (preg_match('/^create\s+"([^"]+)"\s+([^\s]+)\s+"([^"]+)"$/', $line, $m)) {
    (new Command())->create($m[1], $m[2], $m[3]);
    continue;
}


    if (preg_match('/^delete\s+(\d+)$/', $line, $m)) {
        (new Command())->delete((int)$m[1]);
        continue;
    }
    
    if ($line === "exit") {
        echo "Au revoir !" . PHP_EOL;
        break;
    }
     echo "Commande inconnue. Tapez 'help' pour voir les commandes disponibles." . PHP_EOL;
}
