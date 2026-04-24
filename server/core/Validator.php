<?php

namespace app\core;

/** Валидация данных */
class Validator {
    /**
     * Валидация данных с формы
     *
     * @param array $data
     * @return bool|array
     */
    public function validateData(array $data): bool|array {
        $validatedData = [];

        foreach($data as $key => $value) {
            $value = trim($value, " ");

            switch($key) {
                case "number":
                    if(!$this->phoneRUS($value)) {
                        return false;
                    }

                    $validatedData[$key] = $value;

                    break;
                case "password":
                    if(!$this->password($value) || (isset($data["rePassword"]) && $data["rePassword"] !== $value)) {
                        return false;
                    }

                    $validatedData[$key] = $value;

                    break;
                case "rePassword":
                    if(!$this->password($value) || (isset($data["password"]) && $data['password'] !== $value)) {
                        return false;
                    }

                    break;
                case "secondName":
                case "firstName":
                    if(iconv_strlen($value) < 2 || !preg_match("/^[А-я]+$/u", $value) || str_contains($value, "'")) {
                        return false;
                    }

                    $validatedData[$key] = $value;

                    break;
                case "age":
                    if(!is_numeric($value) || $value < 14 || $value > 1000) {
                        return false;
                    }

                    $validatedData[$key] = $value;

                    break;
                case "gender":
                    if($gender = $this->gender($value)) {
                        $validatedData[$key] = $gender;

                    } else {
                        return false;
                    }

                    break;
                case "mail":
                    if(!$this->email($value)) {
                        return false;
                    }

                    $validatedData[$key] = $value;

                    break;
                case "agreement":
                    if($value !== "on") {
                        return false;
                    }

                    break;
                case "title":
                    if(iconv_strlen($value) > 30 || iconv_strlen($value) < 5) {
                        return false;
                    }

                    $validatedData[$key] = $value;

                    break;
                case "message":
                    if(iconv_strlen($value) > 300 || iconv_strlen($value) < 5) {
                        return false;
                    }

                    $validatedData[$key] = $value;

                    break;
                default:
                    break;
            }
        }

        ksort($validatedData);

        return $validatedData;
    }

    /**
     * Проверка на Российский номер
     *
     * @param string $phone
     * @return bool|array
     */
    public function phoneRUS(string $phone): bool|int {
        return preg_match("/^\+7\s\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/", $phone);
    }

    /**
     * Проверка на международный номер
     *
     * @param string $phone
     * @return bool|array
     */
    public function phone(string $phone): bool|int {
        return preg_match("/^\+\d{1,3}\s\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/", $phone);
    }

    /**
     * Валидация пароля
     *
     * @param string $password
     * @return bool
     */
    public function password(string $password): bool {
        $isValidPassword = (preg_match("/\D+/", $password) && strtolower($password) != $password && strtoupper($password) != $password);

        if(iconv_strlen($password) < 6 || !$isValidPassword) {
            return false;
        }

        return true;
    }

    /**
     * Определение гендера
     *
     * @param string $gender
     * @return bool|string
     */
    public function gender(string $gender): bool|string {
        return match ($gender) {
            "Женский", "female" => "female",
            "Мужской", "male" => "male",
            default => false,
        };
    }

    /**
     * Валидация почты
     *
     * @param string $email
     * @return bool|string
     */
    public function email(string $email): bool|string {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && !str_contains($email, "'");
    }
}