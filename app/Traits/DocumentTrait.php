<?php


namespace App\Traits;


trait DocumentTrait
{
    static public function storePositionData($academicID,$cardApplicationDocument): array
    {
        $filePath=$academicID;
        $fileName=$cardApplicationDocument->id;
        $storage= 'CardApplicant';
        return  [$filePath,$fileName,$storage];
    }
}
