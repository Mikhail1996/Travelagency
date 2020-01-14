<?php

class C_Auth extends C_Base
{
    
	// Конструктор.
	public function __construct(){
		parent::__construct();
	}
	
	public function before(){
		//$this->needLogin = true; // раскоментируйте, чтобы закрыть доступ ко всем страницам данного контроллера
		parent::before();
	}
	
	public function action_login(){
		$this->title .= '::Авторизация';
		$this->content = $this->Template('v/v_auth.php', array('temp' => 'temp'));	
	}

	
	/*public function action_edit(){
		if(!M_Users::Instance()->Can('EDIT_PAGES'))
			$this->redirect('../auth/login');
		
		$this->title .= '::Редактирование';
		$id = isset($this->params[2]) ? (int)$this->params[2] : 1;
		$mPages = M_Pages::Instance();
		
		if($this->isPost())
		{
			$mPages->text_set($_POST['text'], $id);
			$this->redirect("../../page/index/$id");
		}
		
		$text = $mPages->text_get($id);
		$this->content = $this->Template('v/v_edit.php', array('text' => $text));		
	}*/
}
