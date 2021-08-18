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
     * @ORM\OneToMany(targetEntity="App\Entity\Images", mappedBy="item",cascade={"persist"})
     */
    private $images;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="item",cascade={"persist"})
     */
    private $comments;


    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Category", inversedBy="items")
     */
    private $category;




    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToMany   (targetEntity="App\Entity\Tag", inversedBy="items")
     */
    private $tag;




    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $likes;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $description;



    /**
     * @ORM\Column(type="datetime", nullable=true)
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

        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();


    }

    public function __toString()
    {
        return $this->date_created;
        return $tag->title;





    }


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





    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setItem($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getItem() === $this) {
                $image->setItem(null);
            }
        }

        return $this;
    }












    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setItem($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getItem() === $this) {
                $comment->setItem(null);
            }
        }

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
















}
