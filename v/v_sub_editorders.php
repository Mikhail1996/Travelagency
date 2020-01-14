<div class="sub_editorders">
    <h3>Заказ №<?=$order_id?></h3>
    <form method="post" action="">
        <p>Общая стоимость:</p> <input type="text" name="amount" value="<?=$order_amount?>">
        <p>Статус заказа:</p>
        <select name="order_status" id="order_status">
            <?=$all_order_statuses?>
        </select>
        <input type="submit" value="Сохранить">
        <input type="button" class="delete_order" id="<?=$order_id?>" value="Удалить заказ">
        <input type="hidden" name="order_id" value="<?=$order_id?>">
        <input type="hidden" name="edit_order_form" value="edit_orders">
    </form>
    <p>Id покупателя: <?=$user_id?></p>
    <p>Имя покупателя: <?=$user_name?></p>
    <p>E-mail: <?=$user_login?></p>
    <h3>Состав заказа: </h3>
    <?=$order_content?>
    <script>
        var selectedId = '#ordercat_' + <?=$order_status_id?>;
        $(selectedId).prop("selected", true);
        $('.order_option').removeAttr('id');
    </script>
</div>