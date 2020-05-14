<?php

namespace TamerDev\EnvironmentCommands;

use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EnvironmentSetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:set {key} {value?} {--file=} {--b|backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set and save an environment variable in the .env file';
    
    /**
     * this flage till if we updated key or add new key.
     *
     * @var boolean
     */

    private $key_found =false;

    /**
     * to save old value ok the key before updated (this if $key_found = true ).
     *
     * @var string
     */

    private $oldValue ="";

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
            [$key, $value] = $this->getKeyValue();
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage());
        }
        $envFilePath = $this->getEnvFilePath();
        $file = fopen($envFilePath,"r");
        $processedFileContent = $this->getProcessedFileContent($file,$key,$value);
        $this->writeFile($envFilePath, $processedFileContent);

        return $this->getMessage($key,$value);
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
        $file = fopen($path, 'w');
        fwrite($file, $contents);

        return fclose($file);
    }


    /**
     * Determine what the supplied key and value is from the current command.
     *
     * @return array
     */
    protected function getKeyValue(): array
    {
        $key = $this->argument('key');
        $value = $this->argument('value');

        if (! $value) {
            $parts = explode('=', $key, 2);

            if (count($parts) !== 2) {
                throw new InvalidArgumentException('No value was set');
            }

            $key = $parts[0];
            $value = $parts[1];
        }

        if (! $this->isValidKey($key)) {
            throw new InvalidArgumentException('Invalid argument key');
        }

        if (! is_bool(strpos($value, ' '))) {
            $value = '"' . $value . '"';
        }

        return [strtoupper($key), $value];
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

        if (!preg_match('/^[a-zA-Z_]+$/', $key)) {
            throw new InvalidArgumentException('Invalid environment key. Only use letters and underscores');
        }

        if($key =="APP_KEY" ){
            throw new InvalidArgumentException('Environment {APP_KEY} should not be set by this command. it is better to use the native one {php artisan key:generate}'); 
        }

        return true;
    }

    protected function getEnvFilePath(): string
    {
        if($this->option('file')){
            $envFilePath = base_path($this->option('file'));
        }else{
            $envFilePath = app()->environmentFilePath();
        }
        return $envFilePath;
    }

    protected function getProcessedFileContent($file,$key,$value): string
    {
        $file_content="";
        while(! feof($file))
        {
            $fileLine= fgets($file) ;
            $parts = explode('=', $fileLine, 2);
            if (count($parts) == 2) {
                $env_key=$parts[0] ;
                $env_value =str_replace("\r\n","",trim($parts[1]));
                if($key==$env_key){
                    $this->key_found=true;
                    $this->oldValue=$env_value;
                    $fileLine="$env_key=$value\r\n";  
                } 
            }
            $file_content =$file_content.$fileLine;
        }
        fclose($file);

        if (!$this->key_found){
            $file_content =$file_content."$key=$value\r\n";
        }
        return $file_content;

    }

    /**
     * get message .
     * @param string $file
     * @param string $key
     */
    protected function getMessage($key,$value)
    {
        if($this->key_found){
            $message = $this->info("Environment variable with key '{$key}' has been changed from '{$this->oldValue}' to '{$value}'");
        }else{
            $message = $this->info("A new environment variable with key '{$key}' has been set to '{$value}'") ;
        }
        return $message;
    }
}
