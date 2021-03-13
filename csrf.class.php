<?php

/**
 * @author KaplanOmr
 * @github https://github.com/KaplanOmr
 * @create date 2021-03-13 23:43:48
 * @modify date 2021-03-13 23:43:48
 * @desc Basic PHP CSRF Class
 */

class CSRF
{
    private $csrfList;
    protected $inputName;


    /**
     * @param string $inputName Custom Input Name
     * @param bool $expiredToken Tokens Time Out
     * @param int $expriredTokenTime Expired Time
     */
    public function __construct(string $inputName = '_csrf', bool $expiredToken = true, int $expriredTokenTime = 3000)
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = [];
        }


        if ($expiredToken) {

            $expTime = time() - $expriredTokenTime;

            $tmpArr = $_SESSION['csrf'];
            if(count($tmpArr)){
                foreach ($tmpArr as $index => $csrf) {
                    if ($csrf['created'] < $expTime) {
                        unset($_SESSION['csrf'][$index]);
                    }
                }
            }            
        }


        $this->csrfList = $_SESSION['csrf'];
        $this->inputName = $inputName;
    }


    /**
     * Token Creator
     * @return string
     */
    public function createToken(): string
    {
        $tokenData = rand() . "-" . time();
        $token = hash('sha256', $tokenData);

        array_push($_SESSION['csrf'], [
            'token' => $token,
            'created' => time()
        ]);

        return $token;
    }


    /**
     * Form Input Creator
     * @return string
     */
    public function createInput(): string
    {
        $token = $this->createToken();
        $returnStr = '<input type="hidden" name="' . $this->inputName . '" value="' . $token . '">';
        return $returnStr;
    }

    /**
     * Token String Checker
     * @param string $token Token String
     * @return bool
     */
    public function checkToken(string $token): bool
    {
        if ($this->checkCsrfList()) {
            foreach ($this->csrfList as $index => $csrf) {
                if (hash_equals($token, $csrf['token'])) {
                    unset($_SESSION['csrf'][$index]);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Request Checker
     * @param array $request Request Array
     * @return bool
     */
    public function checkRequest(array $request): bool
    {
        if ($this->checkCsrfList()) {
            if (isset($request[$this->inputName]) && !empty($request[$this->inputName])) {
                $check = $this->checkToken($request[$this->inputName]);
                if ($check) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * CSRF List Checker
     * @return bool
     */
    private function checkCsrfList(): bool
    {
        if (count($this->csrfList)) {
            return true;
        }

        return false;
    }
}
