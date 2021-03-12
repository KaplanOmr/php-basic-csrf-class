<?php
class CSRF
{
    private $csrfList;
    protected $inputName;

    public function __construct(string $inputName = '_csrf', bool $expiredToken = false, int $exprideTokenTime = 3000)
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = [];
        }


        if ($expiredToken) {

            $expTime = time() - $exprideTokenTime;

            $tmpArr = $_SESSION['csrf'];

            foreach ($tmpArr as $index => $csrf) {
                if ($csrf['created'] > $expTime) {
                    unset($_SESSION['csrf'][$index]);
                }
            }
        }


        $this->csrfList = $_SESSION['csrf'];
        $this->inputName = $inputName;
    }

    public function createToken(): string
    {
        $tokenData = rand() . "-" . time();
        $token = hash('sha256', $tokenData);

        array_push($_SESSION['csrf'], [
            'token' => $token,
            'time' => time()
        ]);

        return $token;
    }

    public function createInput(): string
    {
        $token = $this->createToken();
        $returnStr = '<input type="hidden" name="' . $this->inputName . '" value="' . $token . '">';
        return $returnStr;
    }

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

    private function checkCsrfList(): bool
    {
        if (count($this->csrfList)) {
            return true;
        }

        return false;
    }
}
