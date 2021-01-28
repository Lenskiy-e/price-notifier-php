<?php
declare(strict_types=1);
namespace App;

class exceptionHandler
{
    /**
     * @var
     */
    private $exception;
    
    /**
     * exceptionHandler constructor.
     * @param $exception
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
    }
    
    public function handle()
    {
        if(getenv('is_dev')) {
            echo $this->exception->getMessage();
            echo PHP_EOL;
            echo $this->exception->getCode();
            echo "<pre>";
            print_r($this->exception->getTrace());
        }else{
            echo $this->exception->getMessage();
        }
        exit();
    }
}