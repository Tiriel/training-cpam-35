<?php

namespace App\Dto;

class Contact
{
    public function __construct(
        private ?string $name = null,
        private ?string $email = null,
        private ?string $subject = null,
        private ?string $content = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Contact
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): Contact
    {
        $this->subject = $subject;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): Contact
    {
        $this->content = $content;
        return $this;
    }
}
