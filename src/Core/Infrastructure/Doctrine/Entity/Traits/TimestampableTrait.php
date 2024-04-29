<?php

namespace App\Core\Infrastructure\Doctrine\Entity\Traits;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

trait TimestampableTrait
{
    #[Groups(['timestampable'])]
    #[Gedmo\Timestampable(on: 'create')]
    #[MongoDB\Field(name: 'created_at', type: 'date')]
    protected ?DateTime $createdAt = null;

    #[Groups(['timestampable'])]
    #[Gedmo\Timestampable(on: 'update')]
    #[MongoDB\Field(name: 'updated_at', type: 'date')]
    protected ?DateTime $updatedAt = null;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
