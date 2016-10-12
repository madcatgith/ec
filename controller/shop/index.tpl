{$cartTpl}
{if $success}
    <div class="form-success">{Dictionary::GetUniqueWord(69)}</div>
{else}
    <div id="orderForm" class="custom-form">
        <form action="{$smarty.server.REQUEST_URI}" method="post">
            <fieldset>
                {wmp_sessid_input()}
				<div class="block">
					<div class="title">{Dictionary::GetUniqueWord(108)}</div>
					<div class="fields">
						<div class="form-line checkbox">
							<div class="check-block">
								<label>
									{$fieldsLang.pickupType}
									<input type="radio" checked="" class="check" id="cart_deliveryType1" value="1" name="cart[deliveryType]" />
								</label>
							</div>                
							<div class="check-block">
								<label>
									{$fieldsLang.deliveryType}
									<input type="radio" class="check" id="cart_deliveryType2" value="2" name="cart[deliveryType]" />
								</label>
							</div>                
							<div class="check-block">
								<label>
									{$fieldsLang.mailType}
									<input type="radio" class="check" id="cart_deliveryType3" value="3" name="cart[deliveryType]" />
								</label>
							</div>
						</div>
						<div class="form-line">
							<label for="cart_city">{$fieldsLang.city}</label>
							<div class="input-holder">
								<select id="cart_phone" name="cart[city]" class="custom-select">
									{foreach $cities as $city}
										<option value="{$city.id}"{if $city.id eq $fields.city} selected="selected"{/if}>{$city.title}</option>
									{/foreach}
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="block">
					<div class="title">{Dictionary::GetUniqueWord(109)}</div>
					<div class="fields">
						<div class="form-line">
							<label for="cart_name">{$fieldsLang.name} <span class="req">*</span></label>
							<div class="input-holder">
								<input type="text" id="cart_name" name="cart[name]" value="{$fields.name}" />
							</div>
							{if !empty($errors['name'])}
								<div class="error">
									{$errors['name']}
								</div>
							{/if}
						</div>
						<div class="form-line">
							<label for="cart_email">{$fieldsLang.email} <span class="req">*</span></label>
							<div class="input-holder">
								<input type="text" id="cart_email" name="cart[email]" value="{$fields.email}" />
							</div>
							{if !empty($errors['email'])}
								<div class="error">
									{$errors['email']}
								</div>
							{/if}
						</div>
						<div class="form-line">
							<label for="cart_phone">{$fieldsLang.phone} <span class="req">*</span></label>
							<div class="input-holder">
								<input type="text" id="cart_phone" name="cart[phone]" value="{$fields.phone}" />
							</div>
							{if !empty($errors['phone'])}
								<div class="error">
									{$errors['phone']}
								</div>
							{/if}
						</div>
						<div class="form-line">
							<label for="cart_comment">{$fieldsLang.comment}</label>
							<textarea id="cart_comment" name="cart[comment]">{$fields.comment}</textarea>
						</div>
						<div class="form-line btn-holder">
							<div class="btn-green">
								{Dictionary::GetUniqueWord(40)}
								<input type="submit" />
							</div>
						</div>
					</div>
				</div>
            </fieldset>
        </form>
		<div class="delivery-block">
			<div class="delivery-info">
				<div class="delivery-title">{Dictionary::GetUniqueWord(110)}</div>
				<div class="delivery-desc">{Dictionary::GetUniqueWord(111)}</div>
				<div class="delivery-button"><a class="delivery-fancy" href="#delivery">{Dictionary::GetUniqueWord(32)}</a></div>
			</div>
		</div>
    </div>
	<div id="delivery" style="display:none;">
		{Content::upContentOne(5, 'default')}
	</div>
{/if}