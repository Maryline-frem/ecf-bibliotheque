<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Borrowing;
use App\Entity\Kind;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
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
        $booksCount = 1000;
        $authorCount = 500;
        $kindCount = 10;

        // Création du tableau Kind
        $listKind = ['Poésie', 'Nouvelle', 'Roman historique', 'Roman d\'amour', 'Roman d\'aventure', 'Science-fiction', 'Fantaisy', 'Biographie', 'Conte', 'Témoignage', 'Théâtre', 'Essai', 'Journal intime'];
        // $booksPerAuthor = $this->faker->randomNumber($nbDigits = NULL, $strict = false);
        // $booksPerKind = $this->faker->randomNumber($nbDigits = NULL, $strict = false);

        // Appel des fonctions qui vont créer les objets dans la BDD.
        // La fonction loadAdmins() ne renvoit pas de données mais les autres
        // fontions renvoit des données qui sont nécessaires à d'autres fonctions.
        $this->loadAdmins($manager);
        // $users = $this->loadUsers($manager, $usersCount);
        $borrowers = $this->loadBorrowers($manager, $borrowerCount);
        $authors = $this->loadAuthors($manager, $authorCount);
        $kinds = $this->loadKinds($manager, $listKind);
        $books = $this->loadBooks($manager, $authors, $kinds, $booksCount);  

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

    public function loadBorrowers(ObjectManager $manager, int $count)
    {
        $borrowers = [];

        // Création emprunteur foo
        $user = new User();
        $user->setEmail('foo.foo@example.com');
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_EMPRUNTEUR']);

        $manager->persist($user);

        $borrower = new Borrower();
        $borrower->setFirstname('foo');
        $borrower->setLastname('foo');
        $borrower->setPhone('123456789');
        $borrower->setActive(true);
        $borrower->setCreationDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'));
        $borrower->setUser($user);

        $manager->persist($borrower);

        $borrowers[] = $borrower;

        // Création emprunteur bar
        $user = new User();
        $user->setEmail('bar.bar@example.com');
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_EMPRUNTEUR']);

        $manager->persist($user);

        $borrower = new Borrower();
        $borrower->setFirstname('bar');
        $borrower->setLastname('bar');
        $borrower->setPhone('123456789');
        $borrower->setActive(false);
        $borrower->setCreationDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 11:00:00'));
        // récupération de la date de début
        $creationDate = $borrower->getCreationDate();
        // création de la date de fin à partir de la date de début
        $modificationDate = \DateTime::createFromFormat('Y-m-d H:i:s', $creationDate->format('Y-m-d H:i:s'));
        // ajout d'un interval de 3 mois à la date de début
        $modificationDate->add(new \DateInterval('P3M'));
        $borrower->setModificationDate($modificationDate);
        $borrower->setUser($user);

        $manager->persist($borrower);

        $borrowers[] = $borrower;

        // Création emprunteur baz
        $user = new User();
        $user->setEmail('baz.baz@example.com');
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_EMPRUNTEUR']);

        $manager->persist($user);

        $borrower = new Borrower();
        $borrower->setFirstname('baz');
        $borrower->setLastname('baz');
        $borrower->setPhone('123456789');
        $borrower->setActive(true);
        $borrower->setCreationDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'));
        $borrower->setUser($user);

        $manager->persist($borrower);

        $borrowers[] = $borrower;

        for ($i = 3; $i < $count; $i++) {

            $user = new User();
            $user->setEmail($this->faker->email());
            // Hachage du mot de passe.
            $password = $this->encoder->encodePassword($user, '123');
            $user->setPassword($password);
            // Le format de la chaîne de caractères ROLE_FOO_BAR_BAZ
            // est libre mais il vaut mieux suivre la convention
            // proposée par Symfony.
            $user->setRoles(['ROLE_EMPRUNTEUR']);

            // Demande d'enregistrement d'un objet dans la BDD.
            $manager->persist($user);

            // Création d'un nouvel emprunteur.
            $borrower = new Borrower();
            $borrower->setFirstname($this->faker->firstname());
            $borrower->setLastname($this->faker->lastname());
            $borrower->setPhone($this->faker->phoneNumber());
            $borrower->setActive($this->faker->boolean());
            $borrower->setCreationDate($this->faker->dateTimeThisDecade());
            $borrower->setUser($user);

            // Demande d'enregistrement d'un objet dans la BDD.
            $manager->persist($borrower);

            $borrowers[] = $borrower;
        }
        return $borrowers;
    }

    public function loadBooks(ObjectManager $manager, array $authors, $kinds, int $count)
    {
        $books = [];

        $book = new Book();
        $book->setTitle('Lorem ipsum dolor sit amet');
        $book->setEditionYear('2010');
        $book->setPageNumber('100');
        $book->setCodeIsbn('9785786930024');
        $book->setAuthor($authors[0]);
        $book->addKind($kinds[0]);

        $manager->persist($book);
        $books[] = $book;

        $book = new Book();
        $book->setTitle('Consectetur adipiscing elit');
        $book->setEditionYear('2011');
        $book->setPageNumber('150');
        $book->setCodeIsbn('9783817260935');
        $book->setAuthor($authors[1]);
        $book->addKind($kinds[1]);

        $manager->persist($book);
        $books[] = $book;

        $book = new Book();
        $book->setTitle('Mihi quidem Antiochum');
        $book->setEditionYear('2012');
        $book->setPageNumber('200');
        $book->setCodeIsbn('9782020493727');
        $book->setAuthor($authors[2]);
        $book->addKind($kinds[2]);

        $manager->persist($book);
        $books[] = $book;

        $book = new Book();
        $book->setTitle('Quem audis satis belle');
        $book->setEditionYear('2013');
        $book->setPageNumber('250');
        $book->setCodeIsbn('9794059561353');
        $book->setAuthor($authors[3]);
        $book->addKind($kinds[3]);

        $manager->persist($book);
        $books[] = $book;

        for ($i = 3; $i < $count; $i++) {

            // Création d'un nouveau livre.
            $book = new Book();
            $book->setTitle($this->faker->sentence($nbWords = 3, $variableNbWords = true));
            $book->setEditionYear($this->faker->year($max = 'now'));
            $book->setPageNumber($this->faker->numberBetween($min = 50, $max = 1000));
            $book->setCodeIsbn($this->faker->isbn13());
            $book->setAuthor($this->faker->randomElement($authors));
            $book->addKind($this->faker->randomElement($kinds));

            // Demande d'enregistrement d'un objet dans la BDD.
            $manager->persist($book);
            $books[] = $book;
        }
        return $books;
    }

    public function loadAuthors(ObjectManager $manager, int $count)
    {
        $authors = [];

        // Création d'un premier auteur
        $author = new Author();
        $author->setFirstname('Auteur inconnu');
        $author->setLastname('');

        $manager->persist($author);
        $authors[] = $author;

        // Création d'un deuxième auteur
        $author = new Author();
        $author->setFirstname('Cartier');
        $author->setLastname('Hugues');

        $manager->persist($author);
        $authors[] = $author;

        // Création d'un troisième auteur
        $author = new Author();
        $author->setFirstname('Lambert');
        $author->setLastname('Armand');

        $manager->persist($author);
        $authors[] = $author;

        // Création d'un quatrième auteur
        $author = new Author();
        $author->setFirstname('Moitessier');
        $author->setLastname('Thomas');

        $manager->persist($author);
        $authors[] = $author;

        for ($i = 3; $i < $count; $i++) {

            // Création d'un nouvel auteur.
            $author = new Author();
            $author->setFirstname($this->faker->firstname());
            $author->setLastname($this->faker->lastname());

            // Demande d'enregistrement d'un objet dans la BDD.
            $manager->persist($author);
            $authors[] = $author;
        }
        return $authors;
    }

    public function loadKinds(ObjectManager $manager, array $listKind)
    {
        $kinds = [];

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[0]);

        // Demande d'enregistrement d'un objet dans la BDD.
        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[1]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[2]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[3]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[4]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[5]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[6]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[7]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[8]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[9]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[10]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[11]);

        $manager->persist($kind);
        $kinds[] = $kind;

        // Création d'un nouveau genre.
        $kind = new Kind();
        $kind->setLastname($listKind[12]);

        $manager->persist($kind);
        $kinds[] = $kind;

        return $kinds;
    }
}
