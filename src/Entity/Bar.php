<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\BarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BarRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: [
                'groups' => ['bar:read'],
            ]
        )
    ],
)]
class Bar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bar:read'])]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'bar', targetEntity: Foo::class)]
    #[Groups(['bar:read'])]
    private Collection $foos;

    public function __construct()
    {
        $this->foos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Foo>
     */
    public function getFoos(): Collection
    {
        return $this->foos;
    }

    public function addFoo(Foo $foo): self
    {
        if (!$this->foos->contains($foo)) {
            $this->foos->add($foo);
            $foo->setBar($this);
        }

        return $this;
    }

    public function removeFoo(Foo $foo): self
    {
        if ($this->foos->removeElement($foo)) {
            // set the owning side to null (unless already changed)
            if ($foo->getBar() === $this) {
                $foo->setBar(null);
            }
        }

        return $this;
    }
}
