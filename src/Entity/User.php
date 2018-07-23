<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
//use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="This e-mail is alredy in use")
 * @UniqueEntity(fields="username", message="This username is alredy in use")
 */
class User implements AdvancedUserInterface, \Serializable
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=50)
     */
    private $username;
    
    /**
     * @ORM\Column(type="string")
     */
    private $password;
    
    /**
     * @Assert\NotBlank();
     * @Assert\Length(min=8, max=4096)
     */
    private $plainPassword;
    
    /**
     * @ORM\Column(type="string", length=254, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=4, max=50)
     */
    private $fullName;
    
    
    /**
     * @ORM\Column(type="simple_array")
     */
    private $roles;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user")
     */
    private $posts;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="following")
     */
    private $followers;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="followers")
     * @ORM\JoinTable(name="following", 
     *      joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="following_user_id", referencedColumnName="id")
     *      }
     * )
     */
    private $following;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MicroPost", mappedBy="likedBy")
     */
    private $postsLiked;
    
    /**
     * @ORM\Column(type="string", nullable=true, length=30)
     */
    private $confirmationToken;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;
    
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserPreferences", cascade={"persist"})
     */
    private $preferences;
    
    public function __construct(){
        $this->posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->postsLiked = new ArrayCollection();
        $this->roles = [self::ROLE_USER];
        $this->enabled = false;
    }
    
    /**
     * 
     * @return Collection
     */
    public function getPostsLiked(){
        return $this->postsLiked;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getRoles(){
        return $this->roles;
    }
    
    public function setRoles(array $roles){
        $this->roles = $roles;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    public function getSalt(){
        return null;
    }
    
    public function getUsername(){
        return $this->username;
    }
    
    public function eraseCredentials(){
        
    }
    
    public function serialize(){
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->enabled,
        ]);
    }
    
    public function unserialize($serialized){
        list($this->id, $this->username, $this->password, $this->enabled) = unserialize($serialized);
    }
    
    function getEmail() {
        return $this->email;
    }

    function getFullName() {
        return $this->fullName;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setFullName($fullName) {
        $this->fullName = $fullName;
    }
    
    public function setPassword($password){
        $this->password = $password;
    }
    
    public function setUsername($username){
        $this->username = $username;
    }
    
    function getPlainPassword() {
        return $this->plainPassword;
    }

    function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
    }

    public function getPosts(){
        return $this->posts;
    }
    
    /**
     * 
     * @return Collection
     */
    public function getFollowers(){
        return $this->followers;
    }
    
    /**
     * 
     * @return Collection
     */
    public function getFollowing(){
        return $this->following;
    }

    public function follow(User $userToFollow){
        if($this->getFollowing()->contains($userToFollow)){
            return;
        }
        $this->getFollowing()->add($userToFollow);
    }
    
    function getConfirmationToken() {
        return $this->confirmationToken;
    }

    function setConfirmationToken($confirmationToken) {
        $this->confirmationToken = $confirmationToken;
    }
    
    function getEnabled() {
        return $this->enabled;
    }

    function setEnabled($enabled) {
        $this->enabled = $enabled;
    }

    public function isAccountNonExpired(){
        return true;
    }
    
    public function isAccountNonLocked(){
        return true;
    }
    
    public function isCredentialsNonExpired(){
        return true;
    }
    
    public function isEnabled(){
        return $this->enabled;
    }
    
    function getPreferences() {
        return $this->preferences;
    }

    function setPreferences($preferences) {
        $this->preferences = $preferences;
    }





}
