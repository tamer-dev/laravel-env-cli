<?php

namespace TamerDev\EnvironmentCommands;

use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EnvironmentReadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:read {key} {--file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read an environment variable in the .env file';
    
    /**
     * this flage till if we updated key or add new key.
     *
     * @var boolean
     */

    private $key_found =false;


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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $key = $this->getKeyArgument();
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage());
        }
        
        $envFilePath = $this->getEnvFilePath();
        $file = fopen($envFilePath,"r");
        $value = $this->readValue($file,$key);

        return $this->getMessage($key,$value);
    }

    /**
     * Read Value gor given Key.
     *
     * @param file $file
     * @param string $key
     * @return string
     */
    protected function readValue($file,$key): string
    {
        $key = strtoupper($key);
        $env_value="";
        while(! feof($file))
        {
            $fileLine= fgets($file) ;
            $parts = explode('=', $fileLine, 2);
            if (count($parts) == 2) {
                $env_key=$parts[0] ;
                $env_value =str_replace("\r\n","",trim($parts[1]));
                if($key==$env_key){
                    $this->key_found=true;
                    fclose($file);
                    return $env_value ;
                } 
            }
        }
        fclose($file);

        return $env_value;
    }


    /**
     * Determine what the supplied key from the current command.
     *
     * @return string
     */
    protected function getKeyArgument(): string
    {
        $key = $this->argument('key');
        
        if (! $this->isValidKey($key)) {
            throw new InvalidArgumentException('Invalid argument key');
        }

        return strtoupper($key);
    }

    /**
     * Check if a given string is valid as an environment variable key.
     *
     * @param string $key
     * @return boolean
     */
    protected function isValidKey(string $key): bool
    {
        if (Str::contains($key, '=')) {
            throw new InvalidArgumentException("Environment key should not contain '='");
        }

        return true;
    }

    /**
     * get the full path of env file .
     *
     * @return string 
     */
    protected function getEnvFilePath(): string
    {
        if($this->option('file')){
            $envFilePath = base_path($this->option('file'));
        }else{
            $envFilePath = app()->environmentFilePath();
        }
        return $envFilePath;
    }

    /**
     * get message .
     * @param string $key
     * @param string $value
     */
    protected function getMessage($key,$value)
    {
        if($this->key_found){
            $message = $this->info("Environment variable with key '{$key}' have value from .env file '{$value}'");
        }else{
            $message = $this->info("Environment variable with key '{$key}' not found") ;
        }
        return $message;
    }
}
