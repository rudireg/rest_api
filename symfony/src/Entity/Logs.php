<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogsRepository")
 */
class Logs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $error_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $error_msg;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getErrorCode(): ?int
    {
        return $this->error_code;
    }

    public function setErrorCode(int $error_code): self
    {
        $this->error_code = $error_code;

        return $this;
    }

    public function getErrorMsg(): ?string
    {
        return $this->error_msg;
    }

    public function setErrorMsg(?string $error_msg): self
    {
        $this->error_msg = $error_msg;

        return $this;
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
}
