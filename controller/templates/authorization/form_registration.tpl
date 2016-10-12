<div class="form">		
	<form action="{Menu::get($langID, 14, 'href')}" method="post" id="rForm">
		<div class="row">
			<div class="input-holder">
				<input type="text" id="rName" name="data[name]" placeholder="{$local.name}" value="{$data.name}" />
				<div class="req">*</div>
			</div>
		</div>
		<div class="row">
                    <div class="input-holder">
                        <input type="text" id="rPatronymic" name="data[patronymic]" placeholder="{$local.patronymic}" value="{$data.patronymic}" />
                    </div>
		</div>
		<div class="row">
			<div class="input-holder">
				<input type="text" id="rSurname" name="data[surname]" placeholder="{$local.surname}" value="{$data.surname}" />
				<div class="req">*</div>
			</div>
		</div>
		<div class="row">
			<div class="input-holder">
				<input type="text" id="rRegion" name="data[region]" placeholder="{$local.region}" value="{$data.region}" />
				<div class="req">*</div>
			</div>
		</div>
		<div class="row">
			<div class="input-holder">
				<input type="text" id="rLogin" name="data[email]" placeholder="{$local.email}" value="{$data.email}" />
				<div class="req">*</div>
			</div>
		</div>
		<div class="row">
			<div class="input-holder">
				<input type="text" id="rLogin" name="data[login]" placeholder="{$local.login}" value="{$data.login}" />
				<div class="req">*</div>
			</div>
		</div>
		<div class="row">
			<div class="input-holder">
				<input type="text" id="rPhone" name="data[phone]" placeholder="{$local.phone}" value="{$data.phone}" />
				<div class="req">*</div>
			</div>
		</div>
		<div class="row">
			<div class="input-holder">
				<input type="password" id="rPassword" name="data[password]" placeholder="{$local.password}" value="" />
				<div class="req">*</div>
			</div>
		</div>
		<div class="row">
			<div class="input-holder">
				<input type="password" id="rRepassword" name="data[repassword]" placeholder="{$local.repassword}" value="" />
				<div class="req">*</div>
			</div>
		</div>
		<div class="row sendBtn">
			<a href="javascript:void(0)" class="btn-large"><span>{$local.sendBtn}</span></a>
		</div>
		<input type="hidden" name="action" value="registration" />
	</form>
</div>