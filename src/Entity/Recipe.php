<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $instruction = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbrePers = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $tpsCuisson = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageRecette = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeIngredient::class)]
    private Collection $recipeIngredients;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: AddToFavorite::class)]
    private Collection $addToFavorites;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
        $this->addToFavorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): static
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function getNbrePers(): ?int
    {
        return $this->nbrePers;
    }

    public function setNbrePers(?int $nbrePers): static
    {
        $this->nbrePers = $nbrePers;

        return $this;
    }

    public function getTpsCuisson(): ?\DateTimeInterface
    {
        return $this->tpsCuisson;
    }

    public function setTpsCuisson(?\DateTimeInterface $tpsCuisson): static
    {
        $this->tpsCuisson = $tpsCuisson;

        return $this;
    }

    public function getImageRecette(): ?string
    {
        return $this->imageRecette;
    }

    public function setImageRecette(?string $imageRecette): static
    {
        $this->imageRecette = $imageRecette;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AddToFavorite>
     */
    public function getAddToFavorites(): Collection
    {
        return $this->addToFavorites;
    }

    public function addAddToFavorite(AddToFavorite $addToFavorite): static
    {
        if (!$this->addToFavorites->contains($addToFavorite)) {
            $this->addToFavorites->add($addToFavorite);
            $addToFavorite->setRecipe($this);
        }

        return $this;
    }

    public function removeAddToFavorite(AddToFavorite $addToFavorite): static
    {
        if ($this->addToFavorites->removeElement($addToFavorite)) {
            // set the owning side to null (unless already changed)
            if ($addToFavorite->getRecipe() === $this) {
                $addToFavorite->setRecipe(null);
            }
        }

        return $this;
    }
}
