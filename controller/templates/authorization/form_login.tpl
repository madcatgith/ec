<div class="form">		
	<form action="{Menu::get($langID, 13, 'href')}" method="post" id="rForm">
		<div class="row">
			<div class="input-holder">
				<input type="text" id="uLogin" name="data[login]" placeholder="{$local.login}" />
				<div class="req">*</div>
			</div>
		</div>
		<div class="row">
			<div class="input-holder">
				<input type="password" id="uPassword" name="data[password]" placeholder="{$local.password}" />
				<div class="req">*</div>
			</div>
		</div>
		<div class="row sendBtn">
			<a href="javascript:void(0)" class="btn-large"><span>{$local.sendBtn}</span></a>
		</div>
		<input type="hidden" name="action" value="authorization" />
	</form>
</div>