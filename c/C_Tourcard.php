<?php
//
// Конттроллер страниц.
//

class C_Tourcard extends C_Base
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
		$this->title .= '::Страница тура';
        $this->set_variables();
		$this->content = $this->Template('v/v_tour.php', array('good_name' => $this->good_name, 'good_price' => $this->good_price, 'good_description' => $this->good_description, 'good_image_source' => $this->good_image_source));	
	}
    
    private function set_variables(){
        ob_start();
            $good_info = $this->msql->Select("SELECT name, price, description, image_source FROM goods WHERE id_good = '".$this->params[2]."'");
            foreach ($good_info as $good_inf){
                $this->good_name = $good_inf['name'];
                $this->good_price = $good_inf['price'];
                $this->good_description = $good_inf['description'];
                $this->good_image_source = '/src/' . $good_inf['image_source'];
            }        
    }
}
