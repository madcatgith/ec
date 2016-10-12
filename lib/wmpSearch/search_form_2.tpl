<div class="search_form">
    <div class="form_title">Поиск</div>
    <form action="{$action}" method="get">
        <div class="clearfix">
            <input type="text" name="query[code]" placeholder="{Dictionary::GetUniqueWord(488)|escape}">
            <input type="submit" class="submit" value="ОК">	
        </div>
        <div class="clearfix">
            <input type="text" name="query[number]" placeholder="{Dictionary::GetUniqueWord(489)|escape}">
            <input type="submit" class="submit" value="ОК">	
        </div>
    </form>
</div>