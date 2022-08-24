<?if(!check_bitrix_sessid()) return;?>
<?
echo CAdminMessage::ShowNote(\Bitrix\Main\Localization\Loc::getMessage('IBS_SUCCESS_DELETED'));
?>
<form action='<?=$APPLICATION->GetCurPage()?>'>
    <input type='hidden' name='lang' value='<?=LANGUAGE_ID?>'>
    <input type='submit' name='' value='<?=\Bitrix\Main\Localization\Loc::getMessage('MOD_BACK')?>'>
</form>