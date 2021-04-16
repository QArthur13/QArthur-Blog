<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $underTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $contents;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Commentary::class, mappedBy="article")
     */
    private $commentaries;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visibility;

    /**
     * @ORM\OneToMany(targetEntity=UserLike::class, mappedBy="article")
     */
    private $userLikes;

    /**
     * @ORM\OneToMany(targetEntity=UserShare::class, mappedBy="article")
     */
    private $userShares;

    public function __construct()
    {
        $this->commentaries = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
        $this->userShares = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
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

    public function getUnderTitle(): ?string
    {
        return $this->underTitle;
    }

    public function setUnderTitle(string $underTitle): self
    {
        $this->underTitle = $underTitle;

        return $this;
    }

    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function setContents(string $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Commentary[]
     */
    public function getCommentaries(): Collection
    {
        return $this->commentaries;
    }

    public function addCommentary(Commentary $commentary): self
    {
        if (!$this->commentaries->contains($commentary)) {
            $this->commentaries[] = $commentary;
            $commentary->setArticle($this);
        }

        return $this;
    }

    public function removeCommentary(Commentary $commentary): self
    {
        if ($this->commentaries->removeElement($commentary)) {
            // set the owning side to null (unless already changed)
            if ($commentary->getArticle() === $this) {
                $commentary->setArticle(null);
            }
        }

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * @return Collection|UserLike[]
     */
    public function getUserLikes(): Collection
    {
        return $this->userLikes;
    }

    public function addUserLike(UserLike $userLike): self
    {
        if (!$this->userLikes->contains($userLike)) {
            $this->userLikes[] = $userLike;
            $userLike->setArticle($this);
        }

        return $this;
    }

    public function removeUserLike(UserLike $userLike): self
    {
        if ($this->userLikes->removeElement($userLike)) {
            // set the owning side to null (unless already changed)
            if ($userLike->getArticle() === $this) {
                $userLike->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserShare[]
     */
    public function getUserShares(): Collection
    {
        return $this->userShares;
    }

    public function addUserShare(UserShare $userShare): self
    {
        if (!$this->userShares->contains($userShare)) {
            $this->userShares[] = $userShare;
            $userShare->setArticle($this);
        }

        return $this;
    }

    public function removeUserShare(UserShare $userShare): self
    {
        if ($this->userShares->removeElement($userShare)) {
            // set the owning side to null (unless already changed)
            if ($userShare->getArticle() === $this) {
                $userShare->setArticle(null);
            }
        }

        return $this;
    }
}
