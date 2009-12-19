{pta_const name="PTA_Control_Form_Field::TYPE_TEXT" to="fieldType"}
{if $field->type == $fieldType}
	<input 
		type="text" 
		name="{$field->name}" 
		id="{$field->name}" 
		value="{$field->value}" 
		{if !empty($field->cssClass)}class="{$field->cssClass}"{/if}
		{if !empty($field->disabled)} disabled="disabled"{/if}
	/>
{/if}

{pta_const name="PTA_Control_Form_Field::TYPE_PASSWORD" to="fieldType"}
{if $field->type == $fieldType}
	<input 
		type="password" 
		name="{$field->name}" 
		id="{$field->name}" 
		value="{$field->value}" 
		{if !empty($field->cssClass)}class="{$field->cssClass}"{/if}
		{if !empty($field->disabled)} disabled="disabled"{/if}
	/>
{/if}

{pta_const name="PTA_Control_Form_Field::TYPE_SELECT" to="fieldType"}
{if $field->type == $fieldType}
	<select 
		name="{$field->name}{if !empty($field->arrayMode)}{section name=foo start=0 loop=`$field->arrayModeDeep` step=1}[]{/section}{/if}" 
		id="{$field->name}" 
		{if !empty($field->cssClass)}class="{$field->cssClass}"
		{elseif !empty($cssClass)}class="{$cssClass}"{/if}
		{if !empty($field->disabled)}disabled="disabled"{/if} 
		{if !empty($field->multiple)}multiple="multiple"{/if}
	>
	{foreach from=$field->options item=item}
		{if in_array($item[0], $field->value)}
			<option value="{$item[0]}" selected="selected">{$item[1]}</option>
		{else}
			<option value="{$item[0]}">{$item[1]}</option>
		{/if}
	{/foreach}
	</select>
{/if}

{pta_const name="PTA_Control_Form_Field::TYPE_CHECKBOX" to="fieldType"}
{if $field->type == $fieldType}
	<input 
		type="checkbox" 
		name="{$field->name}{if !empty($index)}[{$index}]{/if}" 
		id="{$field->name}" 
		{if !empty($field->checked)}checked="checked"{/if} 
		{if !empty($field->cssClass)}class="{$field->cssClass}"{/if}
		{if !empty($field->disabled)} disabled="disabled"{/if} 
		value="{if !empty($field->value)}{$field->value}{/if}"
	/>
{/if}

{pta_const name="PTA_Control_Form_Field::TYPE_SUBMIT" to="fieldType"}
{if $field->type == $fieldType}
	<input 
		type="submit" 
		name="{$field->name}" 
		id="{$field->name}" value="{$field->value}" 
		{if !empty($field->cssClass)}class="{$field->cssClass}"{/if}
		{if !empty($field->disabled)} disabled="disabled"{/if}
	/>
{/if}

{pta_const name="PTA_Control_Form_Field::TYPE_TEXTAREA" to="fieldType"}
{if $field->type == $fieldType}
	<textarea 
		name="{$field->name}{if !empty($index)}[{$index}]{/if}" 
		id="{$field->name}" 
		{if !empty($field->cssClass)}class="{$field->cssClass}"{/if}
		{if !empty($field->disabled)} disabled="disabled"{/if}
	>{if !empty($field->value)}{$field->value}{/if}</textarea>
{/if}

{pta_const name="PTA_Control_Form_Field::TYPE_FILE" to="fieldType"}
{if $field->type == $fieldType}
	<input 
		type="file" 
		name="{$field->name}{if !empty($index)}[{$index}]{/if}" 
		id="{$field->name}" 
		{if !empty($field->checked)}checked="checked"{/if} 
		{if !empty($field->cssClass)}class="{$field->cssClass}"{/if}
		{if !empty($field->disabled)} disabled="disabled"{/if} 
		value="{if !empty($field->value)}{$field->value}{/if}"
	/>
{/if}

{pta_const name="PTA_Control_Form_Field::TYPE_HIDDEN" to="fieldType"}
{if $field->type == $fieldType}
	<input 
		type="hidden" 
		name="{$field->name}{if !empty($index)}[{$index}]{/if}" 
		id="{$field->name}" 
		value="{if !empty($field->value)}{$field->value}{/if}"
	/>
{/if}