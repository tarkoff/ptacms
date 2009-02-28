{pta_const name="PTA_Control_Form_Field::TYPE_TEXT" to="fieldType"}
{if $field->type == $fieldType}
	<input type="text" name="{$field->name}" id="{$field->name}" value="{$field->value}" {if !empty($field->cssClass)}class="{$field->cssClass}"{/if}{if !empty($field->disabled)} disabled="disabled"{/if}/>
{/if}

{if $field->type == 'TextArea'}
	<textarea name="{$field->name}" id="{$field->name}"{if !empty($field->cssClass)} class="{$field->cssClass}"{/if}{if !empty($field->disabled)} disabled="disabled"{/if}>{if !empty($field->value)}{$field->value}{/if}</textarea>
{/if}

{pta_const name="PTA_Control_Form_Field::TYPE_SELECT" to="fieldType"}
{if $field->type == $fieldType}
    <select name="{$field->name}" id="{$field->name}"{if !empty($field->cssClass)} class="{$field->cssClass}"{/if}{if !empty($field->disabled)} disabled="disabled"{/if}>
    {if empty($field->value)}
    	{assign var="selected" value="0"}
    {else}
    	{assign var="selected" value=$field->value}
    {/if}
    {foreach from=$field->options item=item}
    	{if $selected == $item[0]}
            <option value="{$item[0]}" selected="selected">{$item[1]}</option>
         {else}
         	<option value="{$item[0]}">{$item[1]}</option>
         {/if}
     {/foreach}
    </select>
{/if}

{pta_const name="PTA_Control_Form_Field::TYPE_CHECKBOX" to="fieldType"}
{if $field->type == $fieldType}
	<input type="checkbox" name="{$field->name}{if !empty($index)}[{$index}]{/if}" id="{$field->name}" {if !empty($field->checked)}checked="checked"{/if} {if !empty($field->cssClass)}class="{$field->cssClass}"{/if}{if !empty($field->disabled)} disabled="disabled"{/if} value="{if !empty($field->value)}{$field->value}{/if}"/>
{/if}

{pta_const name="PTA_Control_Form_Field::TYPE_SUBMIT" to="fieldType"}
{if $field->type == $fieldType}
	<input type="submit" name="{$field->name}" id="{$field->name}" value="{$field->value}" {if !empty($field->cssClass)}class="{$field->cssClass}"{/if}{if !empty($field->disabled)} disabled="disabled"{/if}/>
{/if}
