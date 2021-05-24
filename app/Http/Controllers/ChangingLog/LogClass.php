<?php


namespace App\Http\Controllers\ChangingLog;


class LogClass
{
    public string $class;
    public array $logs;

    /**
     * LogClass constructor.
     * @param string $class
     * @param array $logs
     */
    public function __construct(string $class, array $logs)
    {
        $this->class = $class;
        $this->logs = $logs;
    }

    public function getPlainClassName(){
        return str_replace('.', '', $this->class);
    }

}
