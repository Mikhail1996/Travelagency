<?php
//
// Конттроллер страниц.
//

class C_Catalog extends C_Base
{
    protected $second_column_title;
    
	// Конструктор.
	public function __construct(){
		parent::__construct();
	}
	
	public function before(){
		//$this->needLogin = true; // раскоментируйте, чтобы закрыть доступ ко всем страницам данного контроллера
		parent::before();
	}
	
	public function action_index(){
		$this->title .= '::Католог';
        $this->set_variables(1);
		$this->content = $this->Template('v/v_catalog.php', array('catalog_tours' => $this->catalog_tours, 'big_icons' => $this->big_icons, 'second_column_title' => $this->second_column_title));	
	}
    
    private function prepare_categories(){
       ob_start();
       echo('<option value=null>Без категории</option>');
       $categories_result = $this->msql->Select("SELECT id_category, name FROM categories");
       foreach($categories_result as $categories_value){
            echo('<option value='.$categories_value["id_category"].' id = "optioncat_'.$categories_value["id_category"].'" class = "category_option">'.$categories_value["name"].'</option>');
       }
       return ob_get_clean();
    }
    
    private function get_all_goods($action_num/* 1 - каталог 2 - редактирование каталога */){
        ob_start();
        $result = $this->msql->Select("SELECT goods.id_good, goods.description, goods.name, image_source, goods.price, goods.id_category as id_category FROM goods LEFT JOIN categories ON goods.id_category = categories.id_category");
        
        if ($action_num == 2/* редактирование каталога */){
            $all_categories_as_select_options = $this->prepare_categories();
            print_r('<div class="input_labels"><p>Название</p><p>Цена</p><p>Описание</p><p>Изображение</p><p>Категория</p></div>');
        }
        
        foreach($result as $value){
            $tour_name = $value['name'];
            $tour_price = $value['price'];
            $tour_description = $value['description'];
            $tour_id = $value['id_good'];
            $tour_category = $value['id_category'];
            if (is_null($tour_category)){
                $tour_category = 0;
            }
            $tour_image_source = "/src/" . $value['image_source'];
            if ($action_num == 1){
                require('v/v_sub_tourcard.php');
            } else {
                require('v/v_sub_editcatalog.php');
            }
        };
        if ($action_num == 2/* редактирование каталога */){
            $tour_name = "";
            $tour_price = "";
            $tour_description = "";
            $tour_image_source = "";
            $tour_category = 0;
            require('v/v_sub_editcatalog_addgood.php');
        }
        
        return ob_get_clean();
    }
    
    private function get_all_categories(){
        ob_start();
        $result = $this->msql->Select("SELECT id_category, name, status FROM categories");
        
        print_r('<div class="input_labels cat_name"><p>Название</p><p>Скрыть*</p></div>');
        foreach($result as $value){
            $cat_id = $value['id_category'];
            $cat_name = $value['name'];
            if (is_null($value['status'])){
               $cat_hidden = false; 
            } else {
               $cat_hidden = true; 
            }
            require('v/v_sub_editcategories.php');
        }
        require('v/v_sub_editcategories_addcategory.php');
        
        print_r('<div class="input_labels_comment"><p>* - при установленном флажке товары данной категории не будут отображаться на главной странице</p></div>');
        
        return ob_get_clean();
    }
    
    private function set_variables($action_num/* 1 - каталог 2 - редактирование каталога */){
        
        if ($action_num < 3){
           $this->second_column_title = "Каталог туров";
           $this->catalog_tours = $this->get_all_goods($action_num); 
        } else {
           $this->second_column_title = "Каталог категорий";
           $this->catalog_tours = $this->get_all_categories();  
        }        
        ob_start();
            include('v/v_sub_bigicons.php');        
        $this->big_icons = ob_get_clean();
        
    }
	
	public function action_edit(){
        if ($this->muser->user_role == 3){
            $this->title .= '::Редактирование каталога';
            $this->set_variables(2);
            $this->content = $this->Template('v/v_catalog.php', array('catalog_tours' => $this->catalog_tours, 'big_icons' => $this->big_icons, 'second_column_title' => $this->second_column_title));
        } else {
            alert('У Вас недостаточно прав доступа! Войдите под именем администратора!');
            $this->redirect('../auth/login');
        }
	}
    
    public function action_editcat(){
        if ($this->muser->user_role == 3){
            $this->title .= '::Редактирование категорий';
            $this->set_variables(3);
            $this->content = $this->Template('v/v_catalog.php', array('catalog_tours' => $this->catalog_tours, 'big_icons' => $this->big_icons, 'second_column_title' => $this->second_column_title));
        } else {
            alert('У Вас недостаточно прав доступа! Войдите под именем администратора!');
            $this->redirect('../auth/login');
        }
	}
}
