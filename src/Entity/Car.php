<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    public const FUEL_DIESEL_TYPE = 1;
    public const FUEL_ESSENCE_TYPE = 2;
    public const FUEL_ELECTRICITY_TYPE = 3;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::JSON)]
    private array $imagesNames = [];

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column]
    private ?int $kilometers = null;

    #[ORM\Column]
    private ?int $fuelType = null;

    #[ORM\ManyToMany(targetEntity: Option::class)]
    private Collection $options;

    #[ORM\Column]
    private ?int $price = null;

    public function __construct()
    {
        $this->options = new ArrayCollection();
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

    public function getImagesNames(): array
    {
        return $this->imagesNames;
    }

    public function removeImageName(int $place): void
    {
        unset($this->imagesNames[$place]);
    }

    public function setImageName(int $place, string $name): void
    {
        $this->imagesNames[$place] = $name;
    }

    public function getImageName(int $place): string | false
    {
        return $this->imagesNames[$place] ?? false;
    }

    public function setImagesNames(array $imagesNames): self
    {
        $this->imagesNames = $imagesNames;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getKilometers(): ?int
    {
        return $this->kilometers;
    }

    public function setKilometers(int $kilometers): self
    {
        $this->kilometers = $kilometers;

        return $this;
    }

    public function getFuelType(): ?int
    {
        return $this->fuelType;
    }

    public function setFuelType(int $fuelType): self
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        $this->options->removeElement($option);

        return $this;
    }

    public function getFuelName(): string
    {
        return self::getFuelNameByType($this->fuelType);
    }

    public static function getFuelNameByType(int $type): string
    {
        return match ($type) {
            self::FUEL_DIESEL_TYPE => 'Diesel',
            self::FUEL_ESSENCE_TYPE => 'Essence',
            self::FUEL_ELECTRICITY_TYPE => 'Electrique',
            default => 'Non renseigné'
        };
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    private static function getAssetImagePath(string $imageName): string
    {
        return "images/occasions/$imageName";
    }

    public function getFirstImageAssetPath(): string
    {
        if (isset($this->imagesNames[0]))
            return self::getAssetImagePath($this->imagesNames[0]);
        else
            return 'images/unknow.jpg';
    }

    public function getAllImagesAssetsPath(): array
    {
        if (count($this->imagesNames) > 0) {
            $arr = [];
            foreach ($this->imagesNames as $name)
                $arr[] = self::getAssetImagePath($name);
        }
        else
            $arr = ['images/unknow.jpg'];
        return $arr;
    }
}
