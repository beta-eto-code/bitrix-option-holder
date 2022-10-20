<?php

namespace Bitrix\Main;

class Result
{
    private $errors = [];
    private $data;

    public function __construct()
    {
        $this->data = null;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return empty($this->errors);
    }

    /**
     * @param Error $error
     * @return Result
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @param array $errors
     * @return Result
     */
    public function addErrors(array $errors)
    {
        foreach ($errors as $error) {
            $this->errors[] = $error;
        }

        return $this;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array|string[]
     */
    public function getErrorMessages()
    {
        return array_map(function (Error $error) {
            return $error->getMessage();
        }, $this->errors);
    }
}
