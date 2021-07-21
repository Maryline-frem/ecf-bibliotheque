<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $editionYear;

    /**
     * @ORM\Column(type="integer")
     */
    private $pageNumber;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $codeIsbn;

    /**
     * @ORM\OneToMany(targetEntity=Borrowing::class, mappedBy="book")
     */
    private $borrowings;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=Kind::class, mappedBy="books")
     */
    private $kinds;

    public function __construct()
    {
        $this->borrowings = new ArrayCollection();
        $this->kinds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEditionYear(): ?int
    {
        return $this->editionYear;
    }

    public function setEditionYear(?int $editionYear): self
    {
        $this->editionYear = $editionYear;

        return $this;
    }

    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(int $pageNumber): self
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    public function getCodeIsbn(): ?string
    {
        return $this->codeIsbn;
    }

    public function setCodeIsbn(?string $codeIsbn): self
    {
        $this->codeIsbn = $codeIsbn;

        return $this;
    }

    /**
     * @return Collection|Borrowing[]
     */
    public function getBorrowings(): Collection
    {
        return $this->borrowings;
    }

    public function addBorrowing(Borrowing $borrowing): self
    {
        if (!$this->borrowings->contains($borrowing)) {
            $this->borrowings[] = $borrowing;
            $borrowing->setBook($this);
        }

        return $this;
    }

    public function removeBorrowing(Borrowing $borrowing): self
    {
        if ($this->borrowings->removeElement($borrowing)) {
            // set the owning side to null (unless already changed)
            if ($borrowing->getBook() === $this) {
                $borrowing->setBook(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Kind[]
     */
    public function getKinds(): Collection
    {
        return $this->kinds;
    }

    public function addKind(Kind $kind): self
    {
        if (!$this->kinds->contains($kind)) {
            $this->kinds[] = $kind;
            $kind->addBook($this);
        }

        return $this;
    }

    public function removeKind(Kind $kind): self
    {
        if ($this->kinds->removeElement($kind)) {
            $kind->removeBook($this);
        }

        return $this;
    }

    public function isAvaible(): bool
    {
        // S'il n'y a pas d'emprunt, le livre est dispo
        // S'il y a des emprunts mais qu'ils ont tous été retournés,
        // alors le livre est dispo.

        // loan == borrowing
        foreach ($this->getBorrowings() as $borrowing) {
            if ($borrowing->getReturnDate() == null) {
                return false;
            }
        }
        return true;
    }
}
