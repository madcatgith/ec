
<h2 class="section-title">{$product->getTitle()}</h2>
{Controller::run('iblock/productattr/main')}
<div class="row-btn text-center">
	<a class="btn btn-fix" id="order" href="#">{Dictionary::getUniqueWord(87)}</a>
    <a class="btn btn-flat m-red m-wide" href="{Url::setUrl(['lang'=>$langID,'menu'=>3])}">{Dictionary::getUniqueWord(73)}</a>
</div>
{Controller::run('forms/popup')}