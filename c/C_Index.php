<?php
//
// Конттроллер страниц.
//

class C_Index extends C_Base
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
		$this->title .= '::Главная';
        $this->set_variables();
		$this->content = $this->Template('v/v_index.php', array('tours_in_categories' => $this->tours_in_categories, 'big_icons' => $this->big_icons));	
	}
    
    private function get_goods_from_category($category_id){
        ob_start();
        $result = $this->msql->Select("SELECT goods.id_good, goods.name, goods.price, image_source FROM goods INNER JOIN categories ON goods.id_category = categories.id_category WHERE categories.id_category = '".$category_id."'");
        
        foreach($result as $value){
            $tour_name = $value['name'];
            $tour_price = $value['price'];
            $tour_id = $value['id_good'];
            $tour_image_source = "/src/" . $value['image_source'];
            require('v/v_sub_tourcard.php');
        };
        
        return ob_get_clean();
    }
    
    private function set_variables(){
        ob_start();
            $cat_result = $this->msql->Select("SELECT id_category, name FROM categories WHERE status is NULL");
            foreach ($cat_result as $cat_value){
                $cat_name = $cat_value['name'];
                $cat_tours = $this->get_goods_from_category($cat_value['id_category']);
                require('v/v_sub_indexcategory.php');
            }
        $this->tours_in_categories = ob_get_clean();
        ob_start();
            include('v/v_sub_bigicons.php');       
        $this->big_icons = ob_get_clean();
        
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
