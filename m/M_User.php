<?php
//
// Помощник работы с БД
//
class M_User
{
	private static $instance;	// экземпляр класса
    public $id_user;
    public $user_role;
    public $user_name;
    private static $msql;
    
    public static function Instance()
	{
		if (self::$instance == null){
			self::$instance = new M_User();
        }
			
		return self::$instance;
	}
    
    // Конструктор.
	private function __construct()
	{	
        self::$msql = M_MSQL::Instance();
	}
    
	// Функция авторизации
    public function log_in($login, $password){
        $result = self::$msql->Select("SELECT id_user, user_login, user_password, id_role, user_name FROM `user`");
        foreach ($result as $values){
            if ($values['user_login'] == $login){
                if ($values['user_password'] == $password){
                    $this->id_user = $values['id_user'];
                    $this->user_role = $values['id_role'];
                    $this->user_name = $values['user_name'];
                    setcookie("userLogin", $login, time()+86400, '/');
                    setcookie("userPassword", $password, time()+86400, '/');
                    return true;
                }
            }
        }
    }
    
    public function Get(){
        return self::$id_user; 
    }
}
