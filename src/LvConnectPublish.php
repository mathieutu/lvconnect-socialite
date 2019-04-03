<?php

namespace MathieuTu\LVConnectSocialite;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class LvConnectPublish extends Command
{
    protected $signature = 'lvconnect:publish';
    protected $description = 'Scaffold basic lvconnect login and registration views and routes';

    public function handle()
    {
        $dir = $this->laravel->path('Http/Controllers/Login/');

        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        foreach ($this->getControllerStubs() as $sourcePath => $destinationPath) {
            copy($sourcePath, $destinationPath);
        }

        file_put_contents(
            $this->laravel->basePath('routes/web.php'),
            file_get_contents(__DIR__ . '/stubs/routes.stub'),
            FILE_APPEND
        );

        copy(__DIR__ . '/stubs/login.blade.stub', $this->laravel->resourcePath('views/login.blade.php'));

        $this->info('LVConnect authentication scaffolding generated successfully.');
    }

    private function getControllerStubs(): array
    {
        $dir = __DIR__ . '/stubs/Controllers';
        return Collection::make(scandir($dir))
            ->diff(['..', '.'])
            ->mapWithKeys(function (string $fileName) use ($dir) {
                $sourcePath = "$dir/$fileName";
                $targetPath = $this->laravel->path('Http/Controllers/Login/' . $this->replaceStubFileName($fileName));

                return [$sourcePath => $targetPath];
            })->toArray();
    }

    private function replaceStubFileName(string $fileName): string 
    {
        return str_replace('stub', 'php', $fileName);
    }
}
