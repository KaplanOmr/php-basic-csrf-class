<?php 
class CSRF {

    public function __construct($expiredToken = false, $exprideTokenTime = 3000)
    {   

        if(!isset($_SESSION['csrf'])){
            $_SESSION['csrf'] = [];
        }


        if($expiredToken){

            $expTime = time() - $exprideTokenTime;

            $tmpArr = $_SESSION['csrf'];

            foreach ($tmpArr as $index => $csrf) {
                if($csrf['created'] > $expTime){
                    unset($_SESSION['csrf'][$index]);
                }
            }
        }
        
    }

    public function createToken(): string
    {
        return '';
    }
}
