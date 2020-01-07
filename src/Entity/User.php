<?php

namespace App\Entity;

use App\Service\NotesOrganiser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{


    const admin="ROLE_ADMIN";
    const user="ROLE_USER";
    const professor="ROLE_PROFESSOR";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="user", orphanRemoval=true)
     */
    private $notes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Subject", mappedBy="user")
     */
    private $subjects;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;



    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->professorSubjects = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->assignNote = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    
    

   

    

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setUser($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getUser() === $this) {
                $note->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Subject[]
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects[] = $subject;
            $subject->addUser($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->contains($subject)) {
            $this->subjects->removeElement($subject);
            $subject->removeUser($this);
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getAssignNote(): Collection
    {
        return $this->assignNote;
    }


    public function __toString()
    {
        return $this->firstname." ".$this->lastname;
    }


    public function isGranted($role)
    {
       
        return in_array($role, $this->getRoles());
    }


    public function getMoyenne(){
        $notes=$this->getNotes();
        $subjects=$this->getSubjects();
        $noteService=new NotesOrganiser;
        return $noteService->OrganiseNotes($notes,$subjects);
    }

    public function getNotesFromSubject($subject){
        $moyenne=$this->getMoyenne();
        $tableMoyenne=$moyenne[$subject->getTitle()];
        unset($tableMoyenne["moyenne"]);
        unset($tableMoyenne["hasBeenNoted"]);
        unset($tableMoyenne["subject_id"]);
        return $tableMoyenne;
    }

    public function getMoyenneFromSubject($subject){
        $moyenne=$this->getMoyenne();
       $tableMoyenne=$moyenne[$subject->getTitle()];
       if($tableMoyenne["hasBeenNoted"]===false){
           return false;
       }else{
          return round($tableMoyenne["moyenne"],2);
       }

    }


    public function getMoyenneGeneral(){
        $notes=$this->getNotes();
        $subjects=$this->getSubjects();
        $noteService=new NotesOrganiser;
        return $noteService->getMoyenneGeneral($notes,$subjects);
    }


}
