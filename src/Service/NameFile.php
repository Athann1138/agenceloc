<?php

namespace App\Service;

class NameFile{

    public function renameFile($imageFile)
    {
        $value=date('YmdHis')."-".uniqid()."-".rand(100000,999999).".".$imageFile->getClientOriginalExtension();
        return $value;
    }
}