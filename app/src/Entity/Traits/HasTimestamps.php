<?php

declare(strict_types = 1);

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping\Column;

trait HasTimestamps
{
    #[Column(name: 'created_at')]
    private DateTime $createdAt;

    #[Column(name: 'updated_at')]
    private DateTime $updatedAt;

    public function updateTimestamps(): void
    {
        if (! isset($this->createdAt)) {
            $this->createdAt = new DateTime();
        }

        $this->updatedAt = new DateTime();
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
