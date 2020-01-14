<div class="tour_card" id="<?=$tour_id?>_tour">
    <div class="photo"><img src="<?=$tour_image_source?>" alt="no"></div>
    <h3><?=$tour_name?></h3>
    <p><?=$tour_price?> р</p>
    <div class="busket_button_wrap">
        <? if($is_basket_page): ?>
            <button id="<?=$tour_id?>" class="delete_from_basket">Удалить из корзины</button>
        <? elseif(!$is_order_page): ?>
            <button id="<?=$tour_id?>" class="add_to_basket">Добавить в корзину</button>
        <? endif; ?>
    </div>
</div>