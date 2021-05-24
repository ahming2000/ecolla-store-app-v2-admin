<?php


namespace App\Http\Controllers\ChangingLog;


class Log
{
    public string $version;
    public string $date;
    public array $details;

    /**
     * Log constructor.
     * @param string $version
     * @param string $date
     * @param array $details
     */
    public function __construct(string $version, string $date, array $details)
    {
        $this->version = $version;
        $this->date = $date;
        $this->details = $details;
    }


}
