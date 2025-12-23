<?php

class Command
{
    public function list(): void
    {
        $db = new DBConnect();
        $pdo = $db->getPDO();

        $manager = new ContactManager($pdo);
        $contacts = $manager->findAll();

        foreach ($contacts as $contact) {
            echo $contact->toString() . PHP_EOL;
        }
    }

    public function detail(int $id): void
    {
        $db = new DBConnect();
        $pdo = $db->getPDO();

        $manager = new ContactManager($pdo);
        $contact = $manager->findById($id);

        // Test immédiat demandé
        var_dump($contact);

        if ($contact === null) {
            echo "Contact introuvable" . PHP_EOL;
            return;
        }

        echo $contact->toString() . PHP_EOL;
    }

    public function create(string $name): void
    {
        $db = new DBConnect();
        $pdo = $db->getPDO();

        $manager = new ContactManager($pdo);
        $manager->create($name);
    }

    public function delete(int $id): void
    {
        $db = new DBConnect();
        $pdo = $db->getPDO();

        $manager = new ContactManager($pdo);
        $manager->deleteById($id);
    }
}
