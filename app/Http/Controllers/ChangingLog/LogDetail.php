<?php


namespace App\Http\Controllers\ChangingLog;


class LogDetail
{
    public string $category;
    public array $contents;

    /**
     * LogDetail constructor.
     * @param string $category
     * @param array $contents
     */
    public function __construct(string $category, array $contents)
    {
        $this->category = $category;
        $this->contents = $contents;
    }


}
