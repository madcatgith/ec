{if !$auth->isLogin()}
	<ul class="login-list">
		<li>
			<i class="icon-user"></i><a href="{Menu::get($langID, 13, 'href')}">{Menu::get($langID, 13, 'title')}</a>
		</li>
		<li>
			<a href="{if Url::get('menuID') neq 14}#rModal{else}javascript:void(0);{/if}" id="rModalShow" class="rModal">{Menu::get($langID, 14, 'title')}</a>
		</li>
	</ul>
	{if Url::get('menuID') neq 14}
		<div id="rModal" class="popup">
			<div class="showHead">{Menu::get($langID, 14, 'title')}</div>
			{$auth->getRegistrationForm()}
		</div>
	{/if}
{else}
	<ul class="login-list">
		<li>
			<i class="icon-user"></i>{$auth->get('login')}
		</li>
		<li>
			<a href="?logOut=1">{Dictionary::GetUniqueWord(642)}</a>
		</li>
	</ul>
{/if}