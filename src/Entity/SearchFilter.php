<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class SearchFilter
{
    private ?Campus $campus = null;

    private ?string $search = null;
    #[Assert\DateTime()]
    private ?\DateTimeInterface $startDate = null;
    #[Assert\DateTime()]
    private ?\DateTimeInterface $endDate = null;
    private ?bool $organizer = false;
    private ?bool $registered = false;
    private ?bool $notRegistered = false;
    private ?bool $finished = false;

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getRegistered(): ?bool
    {
        return $this->registered;
    }

    public function setRegistered(?bool $registered): void
    {
        $this->registered = $registered;
    }

    public function getOrganizer(): ?bool
    {
        return $this->organizer;
    }

    public function setOrganizer(?bool $organizer): void
    {
        $this->organizer = $organizer;
    }

    public function getNotRegistered(): ?bool
    {
        return $this->notRegistered;
    }

    public function setNotRegistered(?bool $notRegistered): void
    {
        $this->notRegistered = $notRegistered;
    }

    public function getFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(?bool $passed): void
    {
        $this->finished = $passed;
    }






}