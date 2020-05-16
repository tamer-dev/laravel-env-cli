<?php

namespace TamerDev\EnvironmentCommands;

use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EnvironmentBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:backup {--file=.env}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'backup .env file to a new file';
    
    /**
     * The new file path of backuped file.
     *
     * @var string
     */
    protected $newFilePath = '';

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
     * Execute the console of set env command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $envFilePath = $this->getEnvFilePath();
            $this->makeBackup($envFilePath); 
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        return $this->info("A new environment backup file has been created in this path '{$this->newFilePath}'") ;
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

    public function openFile(string $envFilePath , string $mode ="r")
    { 
        if (!file_exists($envFilePath) && $mode =="r") {
            throw new \Exception("The file $envFilePath does not exist ");
        }

        return fopen($envFilePath,$mode);
    }


    protected function makeBackup($envFilePath)
    {
        $fileContent =  file_get_contents($envFilePath);
        $this->newFilePath =$envFilePath.".backup_".date("YmdHis");
        return $this->writeFile($this->newFilePath,  $fileContent);
    }

}
