<?php

namespace App\Service;

use Google\Cloud\Storage\StorageClient;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileUploader
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function upload(UploadedFile $file): string
    {
        $filename = $this->generateUniqueName($file);
        $bucketName = $this->params->get('google_bucket_name');
        $storage = new StorageClient([
            'keyFilePath' => $this->params->get('google_storage_key')
        ]);
        $bucket = $storage->bucket($bucketName);
        $bucket->upload(fopen($file->getRealPath(), 'r'), [
            'name' => $filename,
        ]);
        return "https://storage.cloud.google.com/" . $bucketName . '/' . $filename;
    }

    public function remove(string $fileName): void
    {
        $bucketName = $this->params->get('google_bucket_name');
        $storage = new StorageClient([
            'keyFilePath' => $this->params->get('google_storage_key')
        ]);
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->object(basename($fileName));
        // check if file exists
        if (!$object->exists()) {
            throw new NotFoundHttpException('Image not found');
        }
        $object->delete();
    }

    public function generateUniqueName(UploadedFile $file): string
    {
        return Uuid::uuid4().'.'.$file->guessExtension();
    }
}
