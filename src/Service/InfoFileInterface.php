<?php

namespace App\Service;

use App\Entity\Timetable\Timetable;
use App\Lib\JsonFile;

class InfoFileInterface
{
    private PathInterface $path;

    public const KEY_TIMETABLE = Timetable::KEY_TIMETABLE;
    public const KEY_MAX_CUTLERYS = 'max_cutlerys';
    public const DEFAULT_MAX_CUTLERYS = 130;

    public function __construct(PathInterface $path)
    {
        $this->path = $path;
    }

    public function getTimetable(): Timetable|false
    {
        if (!($file = JsonFile::ConstructWithPath($this->path->getInfoFilePath())))
            return false;
        elseif (!($arr = $file->getInFile(self::KEY_TIMETABLE)))
            return false;
        elseif (!is_array($arr))
            return false;
        return Timetable::ConstructWithArray($arr);
    }

    public function setTimetable(Timetable $timetable)
    {
        $json = JsonFile::ConstructWithPath($this->path->getInfoFilePath());
        if (!$json)
            return;
        $json->setInFile($timetable->getAsArray(), self::KEY_TIMETABLE);
        $json->saveFile();
    }

}