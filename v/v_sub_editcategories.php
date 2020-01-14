<div class="edit_categories">
    <form method="post" action="">
        <h5><?=$cat_id?></h5>
        <input type="text" name="cat_name" value="<?=$cat_name?>">
        <input type="checkbox" id="cat_visible_<?=$cat_id?>" name="cat_hidden" value="1">
        <input type="submit" value="Сохранить">
        <input type="button" class="delete_category" id="<?=$cat_id?>" value="Удалить">
        <input type="hidden" name="category_id" value="<?=$cat_id?>">
        <input type="hidden" name="edit_category_form" value="edit_cat">
        <input type="hidden" name="category_form" value="edit_cat">
    </form>
    <script>
            var catHidden = <?=$cat_hidden?>;
            var catId = '#cat_visible_' + <?=$cat_id?>;
            if (catHidden){
               $(catId).prop("checked", true); 
            }
    </script>
</div>