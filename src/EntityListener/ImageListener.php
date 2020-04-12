<?php

namespace App\EntityListener;

use App\Entity\Image;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class ImageListener
{
    private $fileUploader;
    private $image;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function postLoad(Image $image, LifecycleEventArgs $args): void
    {
        $image->setWebView($image->getFilename());

        if(($_REQUEST['action'] === 'edit') && !$image->getTempFilename()) {
            $image->setTempFilename($image->getFilename());

            // Enable update events to be triggered
            $image->setFilename(null);
        }
    }

    public function prePersist(Image $image, LifecycleEventArgs $args)
    {
        $this->image = $args->getEntity();
        $filename = $this->fileUploader->upload($this->image->getFile());
        $this->image->setFilename($filename);
    }

    public function preUpdate(Image $image, PreUpdateEventArgs $args)
    {
        $this->image = $args->getEntity();

        $filename = $this->fileUploader->upload($this->image->getFile());

        $this->image->setFilename($filename);
        $this->fileUploader->remove($this->image->getTempFilename());
    }

    public function preRemove(Image $image, LifecycleEventArgs $args)
    {
        $this->image = $args->getEntity();
        $this->fileUploader->remove($this->image->getFileName());
    }
}
