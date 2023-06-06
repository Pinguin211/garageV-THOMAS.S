<?php

namespace App\Service;

use App\Kernel;

class PathInterface
{
    private Kernel $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }


    // PARTIE DOSSIER

    public function getProjectDirPath(): string
    {
        return $this->kernel->getProjectDir() . '/';
    }

    public function getPublicDirPath(bool $create_path = true): string
    {
        return $this->getPath('getProjectDirPath', 'public/', $create_path);
    }

    public function getJsonDirPath(bool $create_path = true): string
    {
        return $this->getPath('getPublicDirPath', 'json/', $create_path);
    }

    public function getImagesDirPath(bool $create_path = true): string
    {
        return $this->getPath('getPublicDirPath', 'images/', $create_path);
    }

    public function getCarImagesDirPath(bool $create_path = true): string
    {
        return $this->getPath('getImagesDirPath', 'occasions/', $create_path);
    }

    public function getServicesImagesDirPath(bool $create_path = true): string
    {
        return $this->getPath('getImagesDirPath', 'services/', $create_path);
    }

    //PARTIE FICHIER

    public function getInfoFilePath(): string
    {
        return $this->getPath('getJsonDirPath', 'info.json', false);
    }

    //Fonction commune

    private function getPath(string $start_func, string $end, bool $create_path): string
    {
        $path = $this->$start_func() . $end;
        if ($create_path)
            self::createPath($path);
        return $path;
    }

    private static function createPath(string $path): void
    {
        if (!file_exists($path))
            mkdir($path, 0777, true);
    }
}