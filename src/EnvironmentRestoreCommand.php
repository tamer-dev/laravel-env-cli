<?php

namespace TamerDev\EnvironmentCommands;

use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EnvironmentRestoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:restore {backupedFileName} {--file=.env}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'restore your .env file from a backup file';
    

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the command of Restore env file.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $backupedFilePath = $this->getBackupedEnvFilePath();
            $envFilePath = $this->getEnvFilePath();
            
            $this->makeRestore($backupedFilePath,$envFilePath); 
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        return $this->info("the env file '{$envFilePath}' has been restored from this file path '{$backupedFilePath}'") ;
    }


    /**
     * get the full path of env file .
     *
     * @return string 
     */
    protected function getEnvFilePath(): string
    {
        return base_path($this->option('file'));
    }

    /**
     * get the full path of env file .
     *
     * @return string 
     */
    protected function getBackupedEnvFilePath(): string
    {
        return base_path($this->argument('backupedFileName'));
    }

    /**
     * Restore env file from a backup file.
     *
     * @param string $backupedFilePath
     * @param string $envFilePath
     * @return boolean
     */
    protected function makeRestore($backupedFilePath,$envFilePath): bool
    {
        if (!file_exists($backupedFilePath) ) {
            throw new \Exception("The file $backupedFilePath does not exist ");
        }
        if (!file_exists($envFilePath) ) {
            throw new \Exception("The file $envFilePath does not exist ");
        }
        $fileContent =  file_get_contents($backupedFilePath);
        return $this->writeFile($envFilePath,  $fileContent);
    }

    /**
     * Overwrite the contents of a file.
     *
     * @param string $path
     * @param string $contents
     * @return boolean
     */
    protected function writeFile(string $path, string $contents): bool
    {
        $file = $this->openFile($path, 'w');
        fwrite($file, $contents);

        return fclose($file);
    }

    /**
     * Open a file.
     *
     * @param string $envFilePath
     * @param string $mode
     * @return filestream
     */
    public function openFile(string $envFilePath , string $mode ="r")
    { 
        if (!file_exists($envFilePath) && $mode =="r") {
            throw new \Exception("The file $envFilePath does not exist ");
        }

        return fopen($envFilePath,$mode);
    }

}
