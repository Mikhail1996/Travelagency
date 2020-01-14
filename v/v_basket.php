<div class="content_second_column basket_page">
    <h3>Список выбранных туров</h3>
    <div class="travel_blocks"><?=$catalog_tours?></div>
    <h4>Суммарная стоимость: <?=$total_cost?>р</h4>
    <button id="form_order">Оформить заказ</button>
    <script> var totalCost = <?php echo $total_cost?>; </script>
</div>
