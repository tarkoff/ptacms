{assign var="fieldTypeField" value=$data->data.fieldtype}
{assign var="fieldAutoField" value=$data->data.autocomplete}
{pta_const name="PTA_Control_Form_Field::TYPE_TEXT" to="selectType"}
{literal}
<script type="text/javascript">
/*
	$("#{/literal}{$data->name}{literal}").load(function(){
		$("#{/literal}{$fieldTypeField->name}{literal}").trigger('change');
	});
*/
	$("#{/literal}{$fieldTypeField->name}{literal}").trigger('change');
	$("#{/literal}{$fieldTypeField->name}{literal}").change(function(){
		if ({/literal}{$selectType}{literal} == $(this).val()) {
			$("#{/literal}{$fieldAutoField->name}{literal}").parents("tr:first").show();
		} else {
			$("#{/literal}{$fieldAutoField->name}{literal}").parents("tr:first").hide();
		}
	});
</script>
{/literal}
{include file="generic/form.tpl" form=$data}
