<?php
//
// Конттроллер страниц.
//

class C_Orders extends C_Base
{
    private $all_orders;
    
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
            $this->title .= '::Мои заказы';
            $this->set_variables(1);
            //print_r($this->all_orders);
            $this->content = $this->Template('v/v_orders.php', array('all_orders' => $this->all_orders));
        } else {
            $this->redirect('../auth/login');
        }
	}
    
    private function get_all_orders($action/* 1 - мои заказы, 2 - редактирование заказов админом */){
        if ($action == 1){
            $query = "SELECT id_order, amount, order_status_name FROM `order` LEFT JOIN `order_status` ON `order`.`id_order_status` = `order_status`.`id_order_status` WHERE id_user=".$this->muser->id_user;

            $result = $this->msql->Select($query);

            //print_r($result);
            ob_start(); 

            $is_order_page = true;

            foreach($result as $value){
                $order_id = $value["id_order"];
                $order_amount = $value["amount"];
                $order_status = $value["order_status_name"];
                $order_content = '';
                $this->total_cost = 0;

                $sub_result = $this->msql->Select("SELECT basket.id_basket, goods.name, goods.price FROM basket LEFT JOIN goods ON basket.id_good = goods.id_good WHERE id_order=".$order_id);
                //print_r($sub_result.'<br>');
                foreach($sub_result as $sub_value){
                    $tour_name = $sub_value['name'];
                    $tour_price = $sub_value['price'];
                    $tour_id = $sub_value['id_basket'];
                    $this->total_cost += $sub_value['price'];
                    ob_start();
                        require('v/v_sub_tourcard.php');
                    $order_content .= ob_get_clean();
                };

                require('v/v_sub_order.php');

            };

            return ob_get_clean();
        } else if ($action == 2){
            $query = "SELECT id_order, amount, order_status_name, `order_status`.id_order_status, user.id_user, user_name, user_login FROM `order` LEFT JOIN `order_status` ON `order`.`id_order_status` = `order_status`.`id_order_status` LEFT JOIN `user` ON `order`.`id_user` = `user`.`id_user`";

            $result = $this->msql->Select($query);

            //print_r($result);
            ob_start(); 

            $is_order_page = true;

            foreach($result as $value){
                $order_id = $value["id_order"];
                $order_amount = $value["amount"];
                $order_status = $value["order_status_name"];
                $order_status_id = $value["id_order_status"];
                $user_id = $value["id_user"];
                $user_name = $value["user_name"];
                $user_login = $value["user_login"];
                $order_content = '';
                $this->total_cost = 0;

                $sub_result = $this->msql->Select("SELECT basket.id_basket, goods.name, goods.price FROM basket LEFT JOIN goods ON basket.id_good = goods.id_good WHERE id_order=".$order_id);
                //print_r($sub_result.'<br>');
                foreach($sub_result as $sub_value){
                    $tour_name = $sub_value['name'];
                    $tour_price = $sub_value['price'];
                    $tour_id = $sub_value['id_basket'];
                    $this->total_cost += $sub_value['price'];
                    ob_start();
                        require('v/v_sub_tourcard.php');
                    $order_content .= ob_get_clean();
                };
                
                $all_order_statuses = $this->get_all_order_statuses();

                require('v/v_sub_editorders.php');

            };

            return ob_get_clean();
        }
    }
    
    private function get_all_order_statuses(){
       ob_start();
       $orders_result = $this->msql->Select("SELECT id_order_status, order_status_name FROM `order_status`");
       foreach($orders_result as $orders_value){
            echo('<option value='.$orders_value["id_order_status"].' id = "ordercat_'.$orders_value["id_order_status"].'" class = "order_option"'.'>'.$orders_value["order_status_name"].'</option>');
       }
       return ob_get_clean(); 
    }
    
    private function set_variables($action/* 1 - мои заказы, 2 - редактирование заказов админом */){
        
        $this->total_cost = 0;
        $this->all_orders = $this->get_all_orders($action);
        
    }
	
	public function action_edit(){
        if ($this->muser->user_role == 3){
            $this->title .= '::Редактирование заказов';
            $this->set_variables(2);
            $this->content = $this->Template('v/v_orders.php', array('all_orders' => $this->all_orders));
        } else {
            alert('У Вас недостаточно прав доступа! Войдите под именем администратора!');
            $this->redirect('../auth/login');
        }
	}
}
