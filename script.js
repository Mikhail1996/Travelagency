function showTourCard(){
    location.href = '/tourcard/index/'+this.id;
}

function addToBasket(){
    if (idUser){
        var id = this.id;
        $.ajax({
          type: "POST",
          data: "add_to_basket_id="+id,
          success: function(msg){
            alert("Тур успешно добавлен в корзину");
          }
        });
    }
    else {
        location.href = '/auth/login';
    }
}

function deleteFromBasket(){
    var id = this.id;
    $.ajax({
      type: "POST",
      data: "delete_from_basket_id="+id,
      success: function(msg){
        alert("Тур удалён из корзины");
        location.reload();
      }
    });
}

function formOrder(){
    $.ajax({
      type: "POST",
      data: "form_order=1&amount="+totalCost,
      success: function(msg){
        alert("Поздравляем! Ваш заказ оформлен! В ближайшее время наш менеджер свяжется с Вами для подтверждения.");
        location.href = '/orders/index';
      }
    });
}

function accountExit(){
    $.ajax({
      type: "POST",
      data: "account_exit=true",
      success: function(msg){
        location.href = '/auth/login';
      }
    });
}

$.each($('.add_to_basket'), function(){
   this.onclick = addToBasket;
});

$.each($('.tour_card'), function(){
   this.onclick = showTourCard;
});

$.each($('.delete_from_basket'), function(){
   this.onclick = deleteFromBasket; 
});

$("#form_order").click( function(){
    formOrder();
});

$(".exit_account").click( function(){
    accountExit();
});