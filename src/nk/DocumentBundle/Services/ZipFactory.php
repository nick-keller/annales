<?php

namespace nk\DocumentBundle\Services;


use Doctrine\Common\Collections\Collection;

class ZipFactory
{
    public function create(Collection $files)
    {
        while(file_exists($destination = __DIR__.'/../../../../www/zip/'.sha1(uniqid(mt_rand(), true)).'.zip')){}
        $valid_files = array();

        foreach($files as $file)
            if(file_exists($file->getPath()))
                $valid_files[] = $file;

        if(count($valid_files)) {
            $zip = new \ZipArchive();

            if($zip->open($destination,\ZipArchive::CREATE) !== true)
                return false;

            foreach($valid_files as $file)
                $zip->addFile($file->getPath(),$file->getName());

            $zip->close();

            return $destination;
        }
        else
        {
            return false;
        }
    }

    public function remove($file)
    {
        unlink($file);
    }
}