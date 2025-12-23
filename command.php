<?php

class Command
{
    private ContactManager $manager;

    public function __construct()
    {
        $db = new DBConnect();
        $this->manager = new ContactManager($db->getPDO());
    }

    public function list(): void
    {
        $contacts = $this->manager->findAll();

        if (empty($contacts)) {
            echo "Aucun contact trouvé." . PHP_EOL;
            return;
        }

        foreach ($contacts as $contact) {
            echo $contact . PHP_EOL; // appelle __toString()
        }
    }

    public function detail(int $id): void
    {
        $contact = $this->manager->findById($id);

        if ($contact === null) {
            echo "Contact introuvable." . PHP_EOL;
            return;
        }

        echo $contact . PHP_EOL;
    }

    public function create(string $name, string $email, string $phoneNumber): void
    {
        $this->manager->create($name, $email, $phoneNumber);
        echo "Contact créé avec succès." . PHP_EOL;
    }

    public function delete(int $id): void
    {
        $this->manager->deleteById($id);
        echo "Contact supprimé." . PHP_EOL;
    }
}
