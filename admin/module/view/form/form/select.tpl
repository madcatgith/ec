<select name="formID" id="formID" class="form-control">
    <option value="0">Выберите форму</option>
    {foreach $data as $item}
        <option value="{$item[0].id}">{$item[0].langed_title}</option>
    {/foreach}
</select>