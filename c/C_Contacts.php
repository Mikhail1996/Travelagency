<?php
//
// Конттроллер страниц.
//

class C_Contacts extends C_Base
{
    
	// Конструктор.
	public function __construct(){
		parent::__construct();
	}
	
	public function before(){
		//$this->needLogin = true; // раскоментируйте, чтобы закрыть доступ ко всем страницам данного контроллера
		parent::before();
	}
	
	public function action_index(){
		$this->title .= '::Контакты';
		$this->content = $this->Template('v/v_contacts.php', array());	
	}
    
}
