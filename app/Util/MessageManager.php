<?php


namespace App\Util;


use function PHPUnit\Framework\isEmpty;

class MessageManager
{

    private array $errorMessages = [];
    private array $infoMessages = [];

    public function pushError(string $errorMessage)
    {
        $this->errorMessages[] = $errorMessage;
    }

    public function pushInfo(string $infoMessage)
    {
        $this->infoMessages[] = $infoMessage;
    }

    public function getError(string $key): string
    {
        return $this->errorMessages[$key];
    }

    public function getInfo(string $key): string
    {
        return $this->infoMessages[$key];
    }

    public function hasError(): bool
    {
        return !empty($this->errorMessages);
    }

    public function hasInfo(): bool
    {
        return !empty($this->infoMessages);
    }

    public function getAllErrors(string $mode = 'array')
    {
        if ($mode == 'string') {
            $message = "";
            foreach ($this->errorMessages as $error) {
                $message = $message . $error . "<br>";
            }
            return $message;
        } else {
            return $this->errorMessages;
        }
    }

    public function getAllInfos(string $mode = 'array')
    {
        if ($mode == 'string') {
            $message = "";
            foreach ($this->infoMessages as $info) {
                $message = $message . $info . "<br>";
            }
            return $message;
        } else {
            return $this->infoMessages;
        }
    }

    public function flashAll(){
        $this->flashError();
        $this->flashInfo();
    }

    public function flashError()
    {
        if ($this->hasError()) {
            session()->flash('error', $this->getAllErrors('string'));
        }
    }

    public function flashInfo()
    {
        if ($this->hasInfo()) {
            session()->flash('message', $this->getAllInfos('string'));
        }
    }

}
