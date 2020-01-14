function deleteCategory(){
    var id = this.id;
    $.ajax({
      type: "POST",
      data: "delete_category_id="+id,
      success: function(msg){
        //alert(msg);
        location.replace("/catalog/editcat");
      }
    });
}

function deleteOrder(){
    var id = this.id;
    $.ajax({
      type: "POST",
      data: "delete_order_id="+id,
      success: function(msg){
        //alert(msg);
        location.replace("/orders/edit");
      }
    });
}

function deleteGood(){
    var id = this.id;
    $.ajax({
      type: "POST",
      data: "delete_good_id="+id,
      success: function(msg){ 
        var params = msg.split("&&&");
        if (params.length > 1){
            alert('Нельзя удалить товар, т.к. он находится в заказе покупателей №'+params[0]+'. Сначала выполните данные заказы и удалите их из списка.');
        };
        location.replace("/catalog/edit");
      }
    });
}

$.each($('.delete_category'), function(){
   this.onclick = deleteCategory;
});

$.each($('.delete_order'), function(){
   this.onclick = deleteOrder;
});

$.each($('.delete_good'), function(){
   this.onclick = deleteGood;
});