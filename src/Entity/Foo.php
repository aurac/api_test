<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\FooRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FooRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection()
    ],
)]
class Foo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['foo:read', 'bar:item:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['foo:read', 'bar:item:read'])]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['1'])]
    private ?string $property1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['2'])]
    private ?string $property2 = null;

    #[ORM\ManyToOne(inversedBy: 'foos')]
    private ?Bar $bar = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getProperty1(): ?string
    {
        return $this->property1;
    }

    public function setProperty1(?string $property1): self
    {
        $this->property1 = $property1;

        return $this;
    }

    public function getProperty2(): ?string
    {
        return $this->property2;
    }

    public function setProperty2(?string $property2): self
    {
        $this->property2 = $property2;

        return $this;
    }

    public function getBar(): ?Bar
    {
        return $this->bar;
    }

    public function setBar(?Bar $bar): self
    {
        $this->bar = $bar;

        return $this;
    }
}
