<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikeNotificationRepository")
 */
class LikeNotification extends Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroPost")
     */
    private $microPost;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $likedBy;
    
    function getMicroPost() {
        return $this->microPost;
    }

    function getLikedBy() {
        return $this->likedBy;
    }

    function setMicroPost($microPost) {
        $this->microPost = $microPost;
    }

    function setLikedBy($likedBy) {
        $this->likedBy = $likedBy;
    }


}
