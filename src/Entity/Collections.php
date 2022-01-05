<?php

namespace App\Entity;
use App\Entity\Item;
use App\Repository\CollectionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CollectionsRepository::class)
 */
class Collections
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\User", inversedBy="collections")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_created;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="collection",cascade={"persist","remove"})
     */
    private $items;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Collections", inversedBy="collections")
     */
    private $collection;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }


    public function __toString(){

        return $this->title;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

//    public function addItem(Item $item): self
//    {
//        if (!$this->items->contains($item)) {
//            $this->items[] = $item;
//
//            $item->setCollections($this);
//        }
//
//        return $this;
//    }


    public function addItem(Item $item): void
    {
//        // for a many-to-many association:
//        $item->addCollection($this);

        // for a many-to-one association:
        $item->setCollection($this);


        $this->items->add($item);
    }



    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCollections() === $this) {
                $item->setCollections(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(?\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCollection(): ?Collections
    {
        return $this->collection;
    }

    public function setCollection(?Collections $collection): self
    {
        $this->collection = $collection;

        return $this;
    }






}
