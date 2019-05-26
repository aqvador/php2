<?php 

class Token {
    
    public function __construct($host){
        if($host) {
            $this->host = $host;
            $this->token = $this->createToken();
        } else return false;
    }
    
    private function createToken() {
        return hash('md5', $this->host.date('d-m-y').$this->key);
    }
    
    public function checkToken($check) {
        if($check) {
            return ($check == $this->token)?true:false;
        } else return false;
    }
    
    private $key = '42ntktajybcnf';
    public $token = '';
    private $host;
}