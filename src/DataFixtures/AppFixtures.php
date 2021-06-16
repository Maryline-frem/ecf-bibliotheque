<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Borrowing;
use App\Entity\Kind;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    
    private $encoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        // Création d'une instance de faker localisée en
        // français (fr) de France (FR).
        $this->faker = FakerFactory::create('fr_FR');
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager)
    {
        // Définition du nombre d'objets qu'il faut créer.
        $borrowerCount = 100;
        $usersPerBorrower = 1;

        // Le nombre de users à créer dépend du nombre d'emprunteur.
        $usersCount = $usersPerBorrower * $borrowerCount;

        // Appel des fonctions qui vont créer les objets dans la BDD.
        // La fonction loadAdmins() ne renvoit pas de données mais les autres
        // fontions renvoit des données qui sont nécessaires à d'autres fonctions.
        $this->loadAdmins($manager);
        // La fonction loadStudents() a besoin de la liste des school years.
        $users = $this->loadUsers($manager, $usersCount);
        // $borrowers = $this->loadBorrowers($manager, $borrowerCount);
        

        //$users = $this->loadUsers($manager,100);

        // Exécution des requêtes.
        // C-à-d envoi de la requête SQL à la BDD.
        $manager->flush();
    }

    public function loadAdmins(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
    }

    public function loadUsers(ObjectManager $manager, int $count)
    {
        $users = [];

        for ($i = 0; $i < $count; $i++) {
            // Création d'un nouveau user.
            $user = new User();
            $user->setEmail($this->faker->email());
            // Hachage du mot de passe.
            $password = $this->encoder->encodePassword($user, '123');
            $user->setPassword($password);
            // Le format de la chaîne de caractères ROLE_FOO_BAR_BAZ
            // est libre mais il vaut mieux suivre la convention
            // proposée par Symfony.
            $user->setRoles(['ROLE_USER']);

            // Demande d'enregistrement d'un objet dans la BDD.
            $manager->persist($user);
            $users[] = $user;
        }
        return $users;
    }

    public function loadBorrowers(ObjectManager $manager, int $count)
    {
        $borrowers = [];

        for ($i = 0; $i < $count; $i++) {
            // Création d'un nouvel emprunteur.
            $borrower = new Borrower();
            $borrower->setFirstname($this->faker->firstname());
            $borrower->setLastname($this->faker->lastname());
            $borrower->setPhone($this->faker->phoneNumber());
            $borrower->setActive($this->faker->boolean());
           
            $borrower->setCreationDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:00:00'));

            $borrower->setUser($user);

            // Demande d'enregistrement d'un objet dans la BDD.
            $manager->persist($borrower);

            $borrowers[] = $borrower;
        }
        return $borrowers;
    }
}
