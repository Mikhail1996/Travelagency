<input type="text" name="good_name" value="<?=$tour_name?>">
<input type="text" name="good_price" value="<?=$tour_price?>">
<input type="text" name="good_description" value="<?=$tour_description?>">
<input type="text" name="image_source" value="<?=$tour_image_source?>">
<select name="good_category" id="good_category">
    <?=$all_categories_as_select_options?>
</select>
<input type="submit" value="Сохранить">