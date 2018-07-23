<?php

namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

use App\Entity\MicroPost;
use App\Entity\LikeNotification;

class LikeNotificationSubscriber implements EventSubscriber{
    
    public function getSubscribedEvents(){
        return [
            Events::onFlush
        ];
    }
    
    public function onFlush(OnFlushEventArgs $args){
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        
        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate){
            if(!$collectionUpdate->getOwner() instanceof MicroPost){
              continue;  
            }
            
            if('likedBy' !== $collectionUpdate->getMapping()['fieldName']){
               continue;
            }
            
            $insertDiff = $collectionUpdate->getInsertDiff();
            
            if(!count($insertDiff)){
                return;
            }
            
            $microPost = $collectionUpdate->getOwner();
            $notification = new LikeNotification();
            $notification->setUser($microPost->getUser());
            $notification->setMicroPost($microPost);
            $notification->setLikedBy(reset($insertDiff));
            
            $em->persist($notification);
            $uow->computeChangeSet($em->getClassMetaData(LikeNotification::class),
                    $notification
                    );
        }
    }
}
