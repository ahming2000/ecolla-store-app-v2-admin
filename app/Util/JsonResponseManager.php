<?php

namespace App\Util;


class JsonResponseManager
{
    private array $errors = [];
    private array $messages = [];
    private array $data = [];
    private bool $ok = true;

    public function addMessage(string $message, bool $ok = true)
    {
        $this->messages[] = $message;
        if ($this->ok) {
            $this->ok = $ok;
        }
    }

    public function getStackedMessage(): string
    {
        $message = "";
        foreach ($this->messages as $error) {
            $message = $message . $error . "<br>";
        }
        return $message;
    }

    public function setAll(array $errors, array $messages, array $data){
        $this->errors = $errors;
        $this->messages = $messages;
        $this->data = $data;
    }

    public function getJsonResponse(): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            [
                'errors' => $this->errors, // Array
                'messages' => $this->getStackedMessage(), // String
                'data' => $this->data, // Array
                'ok' => $this->ok // Boolean
            ]
        );
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->ok;
    }

    /**
     * @param bool $ok
     */
    public function setOk(bool $ok): void
    {
        $this->ok = $ok;
    }
}
