<div class="edit_catalog">
    <form method="post" action="">
        <? require('v/v_sub_sub_editcatalog.php'); ?>
        <input type="button" class="delete_good" id="<?=$tour_id?>" value="Удалить тур">
        <input type="hidden" name="good_id" value="<?=$tour_id?>">
        <input type="hidden" name="edit_catalog_form" value="edit_catalog">
    </form>
    <script>
        if (<?=$tour_category?>){
            var selectedId = '#optioncat_' + <?=$tour_category?>;
            $(selectedId).prop("selected", true);
        }
        $('.category_option').removeAttr('id');
    </script>
</div>