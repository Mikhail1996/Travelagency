<?php
//
// Базовый контроллер сайта.
//
abstract class C_Base extends C_Controller
{
	protected $title;		// заголовок страницы
	protected $content;		// содержание страницы
	protected $needLogin;	// необходима ли авторизация
    protected $welcome;		// приветствие в header
    private $menu;          // меню
    protected $msql;        // объект БД
    protected $muser;       // данные пользователя
    protected $total_cost;  // общая сумма товаров в корзине

	// Конструктор.
	function __construct()
	{	
		$this->needLogin = false;
        $this->msql = M_MSQL::Instance();
        $this->muser = M_User::Instance();
	}
	
    public function setMenu(){
        ob_start();
        echo('
            <li><a href="/index/index">Главная</a></li>
            <li><a href="/catalog/index">Каталог туров</a></li>
            <li><a href="/basket/index">Корзина</a></li>
            <li><a href="/auth/login">Авторизация</a></li>
            <li><a href="/orders/index">Мои заказы</a></li>
        ');
        // Если админ
        if($this->muser->user_role == 3){
            echo('
                <li><a href="/catalog/edit">Редактирование товаров</a></li>
                <li><a href="/catalog/editcat">Редактирование категорий</a></li>
                <li><a href="/orders/edit">Редактирование заказов</a></li>
            ');
        }
		return ob_get_clean();
    }
    
	protected function before()
	{
        // Редирект на авторизацию
		//if($this->needLogin && $this->user === null)
		//	$this->redirect('/auth/login');
        
        // Авторизация по кукам
        if (is_null($this->muser->id_user)){
            if ($_COOKIE["userLogin"] && $_COOKIE["userPassword"]){
                $this->muser->log_in($_COOKIE["userLogin"], $_COOKIE["userPassword"]); 
            }
        }
	
        $this->post_method_handler();
		$this->title = 'Турагентство MyWorld';
		$this->content = '';
        $this->welcome = '';
        $this->menu = $this->setMenu();
	}
	
	//
	// Генерация базового шаблона
	//	
	public function render()
	{
		$vars = array('title' => $this->title, 'content' => $this->content, 'welcome' => $this->welcome, 'menu' => $this->menu);
		$page = $this->Template('v/v_main.php', $vars);				
		echo $page;
	}
    
    // Обработчик данных, пришедших в POST-запросе от script.js
    private function post_method_handler(){
        if($this->isPost())
		{
            // Пользовательские функции
            if($_POST['reg_name']){
                $new_user = array();
                $new_user['user_name'] = $_POST['reg_name'];
                $new_user['user_login'] = $_POST['reg_login'];
                $new_user['user_password'] = md5($_POST['reg_password']);
                $new_user['id_role'] = '1';
                $this->msql->Insert('user', $new_user);
                setcookie("userLogin", $_POST['reg_login'], time()+86400, '/');
                setcookie("userPassword", md5($_POST['reg_password']), time()+86400, '/');
                $this->redirect('../index/index');
            }
            if($_POST['auth_login']){
                $auth_done = $this->muser->log_in($_POST['auth_login'], md5($_POST['auth_password']));
                if ($auth_done){
                    $this->redirect('../index/index');
                }
            }
			if($_POST['add_to_basket_id'] != 0){
                $new_basket_good = array();
                $new_basket_good['id_user'] = $this->muser->id_user;
                $new_basket_good['id_good'] = $_POST['add_to_basket_id'];
                $this->msql->Insert('basket', $new_basket_good);
            }
            if($_POST['delete_from_basket_id'] != 0){
                $where = 'id_basket='.$_POST['delete_from_basket_id'];
                $this->msql->Delete('basket', $where);
            }
            if($_POST['form_order'] != 0){
                $new_order = array();
                $new_order['id_user'] = $this->muser->id_user;
                $new_order['id_order_status'] = '1';
                $new_order['amount'] = $_POST['amount'];
                $datetime = date(y.m.d.H.i.s);
                $new_order['datetime_create'] = $datetime;
                $this->msql->Insert('order', $new_order);
                $result = $this->msql->Select("SELECT id_order FROM `order` WHERE datetime_create = ".$datetime." AND id_user = ".$this->muser->id_user);
                $this->msql->Update('basket', 'is_in_order = 1, id_order = '.$result[0]['id_order'], 'id_user = '.$this->muser->id_user.' AND is_in_order is NULL');
            }
            if($_POST['account_exit']){
                $this->muser->id_user = null;
                $this->muser->user_role = null;
                $this->muser->user_name = null;
                setcookie("userLogin", '', time()-86400, '/');
                setcookie("userPassword", '', time()-86400, '/');
            }
            
            // Админские функции
            if($_POST['delete_order_id'] != 0){
                $where = 'id_order='.$_POST['delete_order_id'];
                $this->msql->Delete('basket', $where);
                $this->msql->Delete('`order`', $where);
            }
            if($_POST['delete_category_id'] != 0){
                $where = 'id_category='.$_POST['delete_category_id'];
                $this->msql->Delete('`categories`', $where);
            }
            if($_POST['delete_good_id'] != 0){
                $where = 'id_good='.$_POST['delete_good_id'].' AND NOT is_in_order is NULL';
                $result = $this->msql->Select("SELECT id_user FROM `basket` WHERE ".$where);
                $where = 'id_good='.$_POST['delete_good_id'];
                if(empty($result)){
                    $this->msql->Delete('`goods`', $where);
                } else {
                    foreach($result as $user_value){
                        print_r($user_value["id_user"].';');
                    }
                    print_r('&&&'); // Показывает, что передача параметров окончена
                }
            }
            if($_POST['edit_catalog_form']){
                $new_edit_catalog = '';
                $new_edit_catalog .= '`name` = "'.$_POST['good_name'].'"';
                $new_edit_catalog .= ',`price` = "'.$_POST['good_price'].'"';
                if ($_POST['good_description']){
                    $new_edit_catalog .= ',`description` = "'.$_POST['good_description'].'"';
                } else {
                    $new_edit_catalog .= ',`description` = NULL';
                }
                if ($_POST['image_source']){
                    $new_edit_catalog .= ',`image_source` = "'.$_POST['image_source'].'"';
                } else {
                    $new_edit_catalog .= ',`image_source` = NULL';
                }                
                $new_edit_catalog .= ',`id_category` = '.$_POST['good_category'];
                $this->msql->Update('goods', $new_edit_catalog, 'id_good = '.$_POST['good_id']);
            }
            if ($_POST['add_catalog_form']){
                $new_edit_catalog = array();
                $new_edit_catalog['name'] = $_POST['good_name'];
                $new_edit_catalog['price'] = $_POST['good_price'];
                $new_edit_catalog['description'] = $_POST['good_description'];
                $new_edit_catalog['image_source'] = $_POST['image_source'];
                if ($_POST['good_category']){
                    $new_edit_catalog['id_category'] = $_POST['good_category'];
                } else {
                    $new_edit_catalog['id_category'] = null;
                }
                $this->msql->Insert('goods', $new_edit_catalog);
            }
            if($_POST['edit_category_form']){
                $new_edit_category = '';
                $new_edit_category .= '`name` = "'.$_POST['cat_name'].'"';
                if ($_POST['cat_hidden']){
                    $new_edit_category .= ',`status` = 1';
                } else {
                    $new_edit_category .= ',`status` = NULL';
                }
                $this->msql->Update('categories', $new_edit_category, 'id_category = '.$_POST['category_id']);
            }
            if ($_POST['add_category_form']){
                $new_edit_category = array();
                $new_edit_category['name'] = $_POST['cat_name'];
                $new_edit_category['status'] = $_POST['cat_hidden'];
                $this->msql->Insert('categories', $new_edit_category);
            }
            if($_POST['edit_order_form']){
                $new_edit_order = '';
                $new_edit_order .= '`amount` = '.$_POST['amount'];
                $new_edit_order .= ',`id_order_status` = '.$_POST['order_status'];
                $this->msql->Update('`order`', $new_edit_order, 'id_order = '.$_POST['order_id']);
            }
		}
    }
}
