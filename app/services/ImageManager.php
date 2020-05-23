<?php

namespace app\services;

use Intervention\Image\ImageManagerStatic as Image;

class ImageManager
{
    private $folder;
    private $userPhotosFolder = 'img/';

    public function __construct()
    {
        $this->folder = config('uploadsFolder');
    }

    public function uploadImage($image, $currentImage = null)
    {
        if (!is_file($image['tmp_name']) && !is_uploaded_file($image['tmp_name'])) { return $currentImage; }

        $this->deleteImage($currentImage);

        $tmp = explode('.', $image['name']);
        $fileExtention = end($tmp);
        
        $fileName = strtolower(str_random(10)) . '.' . $fileExtention;
        $image = Image::make($image['tmp_name']);
        $image->save($this->folder . $fileName);
        return $fileName;
    }

    public function deleteImage($image)
    {
        if ($this->checkImageExists($image)) {
            unlink($this->folder . $image);
        }
    }

    public function deleteUserImage($image)
    {
        if ($this->checkUserImageExists($image)) {
            unlink($this->userPhotosFolder . $image);
        }
    }
    
    public function checkImageExists($path)
    { 
        if ($path != null && is_file($this->folder . $path) && file_exists($this->folder . $path)) {
            return true;
        }
    }
    
    public function getImage($image)
    {
        if ($this->checkImageExists($image)) {
            return '/' . $this->folder . $image;
        }
        
        return '/img/no-user.png';
    }

    public function getDimensions($file)
    {
        if ($this->checkImageExists($file)) {
            list($width, $height) = getimagesize($this->folder . $file);
            return $width . 'X' . $height;
        }   
    }

    public function uploadUserImage($image, $currentImage = null)
    {
        if (!is_file($image['tmp_name']) && !is_uploaded_file($image['tmp_name'])) { return $currentImage; }

        $this->deleteImage($currentImage);

        $tmp = explode('.', $image['name']);
        $fileExtention = end($tmp);
        
        $fileName = strtolower(str_random(10)) . '.' . $fileExtention;
        $image = Image::make($image['tmp_name']);
        $image->save($this->userPhotosFolder . $fileName);
        return $fileName;
    }

    public function checkUserImageExists($path)
    {
        if ($path != null && is_file($this->userPhotosFolder . $path) && file_exists($this->userPhotosFolder . $path)) {
            return true;
        }
    }

    public function getUserImage($image)
    {
        if ($this->checkUserImageExists($image)) {
            return '/' . $this->userPhotosFolder . $image;
        }

        return '/img/no-user.png';
    }
}