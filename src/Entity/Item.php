<?php

namespace App\Entity;
use App\Entity\Category;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Category", inversedBy="items")
     */
    private $category;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;
    /**
     * @ORM\ManyToMany   (targetEntity="App\Entity\Tag", inversedBy="items")
     */
    private $tag;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $likes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $date_created;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $date_modife;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\User", inversedBy="items")
     */
    private $author;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;




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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }


    public function getCategory(): ?object
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }


    public function isStatus(){
        return $this->status;
    }

    public function enable(){
        $this->status = 1;
        return $this->save(false);
    }

    public function disable(){
        $this->status = 0;
        return $this->save(false);
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLikes(): ?string
    {
        return $this->likes;
    }

    public function setLikes(string $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreated(): ?string
    {
         return $this->date_created;
    }

    public function setDateCreated(string $date_created): self
    {

        $this->date_created = $date_created;
        return $this;
    }

    public function getDateModife(): ?\DateTimeInterface
    {
        return $this->date_modife;
    }

    public function setDateModife(string $date_modife): self
    {
        $this->date_modife = $date_modife;
        return $this;
    }

    public function getAuthor(): ?object
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function setTag(Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }











    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return  $this;
    }

    public function __construct()
    {
       $this->items = new ArrayCollection();
       $this->tag = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->date_created;
        return $tag->title;

    }


//    public function __construct(string $date_created)
//    {
//        $this->date_created= new \DateTime();
//        //$this->date_modife= new \DateTime();
//
//    }


//    public function preUpdate()
//    {
//        $this->date_modife= new \DateTime();
//    }

//    public function setCreatedAtValue(): void
//    {
//
//        //$item->setDateCreated(2016-06-12);
//        $this->date_created= new \DateTime();
//    }

public function addTag(Tag $tag): self
{
    if (!$this->tag->contains($tag)) {
        $this->tag[] = $tag;
    }

    return $this;
}

public function removeTag(Tag $tag): self
{
    $this->tag->removeElement($tag);

    return $this;
}





}
