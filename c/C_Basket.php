<?php
//
// Конттроллер страниц.
//

class C_Basket extends C_Base
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
        if ($this->muser->id_user){
            $this->title .= '::Корзина';
            $this->set_variables();
            $this->content = $this->Template('v/v_basket.php', array('catalog_tours' => $this->catalog_tours, 'total_cost' => $this->total_cost));
        } else {
            $this->redirect('../auth/login');
        }
	}
    
    private function get_all_basket_goods(){
        ob_start();
        $result = $this->msql->Select("SELECT basket.id_basket, goods.name, goods.price FROM basket LEFT JOIN goods ON basket.id_good = goods.id_good WHERE id_user='".$this->muser->id_user."'  AND is_in_order is NULL");
        
        $is_basket_page = true;
        
        foreach($result as $value){
            $tour_name = $value['name'];
            $tour_price = $value['price'];
            $tour_id = $value['id_basket'];
            $this->total_cost += $value['price'];
            require('v/v_sub_tourcard.php');
        };
        
        return ob_get_clean();
    }
    
    private function set_variables(){
        
        $this->total_cost = 0;
        $this->catalog_tours = $this->get_all_basket_goods();
        
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
