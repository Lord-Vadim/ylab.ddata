<?
/**
 * @global $arRequest
 * @global $arOptions
 */

use Bitrix\Main\Localization\Loc;
use Ylab\Ddata\LoadUnits;

Loc::loadMessages(__FILE__);

$oRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$sEntityID = $oRequest->get('generator');
$oClasses = new LoadUnits();
$arClassesData = $oClasses->getDataUnits();

$arEntity = [];
foreach ($arClassesData as $arClass) {
    if ($arClass['ID'] == $sEntityID) {
        $arData = $arClass;
    }
}

$oData = new $arData['CLASS']();
$sRandom = $oData->sRandom;
$arSelectedGroups = $oData->arSelectedGroups;
?>
<script type='text/javascript'>
    BX.ready(function () {
        var inputOptions = BX.findChild(
            BX(document),
            {
                attribute: {
                    'name': '<?= $sPropertyName ?>[<?= $sGeneratorID ?>]'
                }
            },
            true,
            true
        )[0];
        if (inputOptions) {
            var optionsValue = JSON.parse(inputOptions.value);
        }
        if (inputOptions != undefined) {
            Object.keys(optionsValue).forEach(function (key, item) {

                var optionsForm = BX.findChild(
                    BX('WindowEntityDataForm'),
                    {
                        attribute: {
                            'name': 'option[' + key + ']'
                        }
                    },
                    true,
                    true
                )[0];
                if (optionsForm) {
                    optionsForm.value = optionsValue[key];
                }

                var optionsFormMultiple = BX.findChild(
                    BX('WindowEntityDataForm'),
                    {
                        attribute: {
                            'name': 'option[' + key + '][]'
                        }
                    },
                    true,
                    true
                )[0];
                if (optionsFormMultiple) {

                    var optionsForms = optionsFormMultiple.options;

                    for (var i = 0; i < optionsForms.length; i++) {

                        for (var j = 0; j < optionsValue[key].length; j++) {

                            if (optionsForms[i].value == optionsValue[key][j]) {

                                optionsForms[i].selected = true;
                            }
                        }
                    }
                }
            });
        }
    });
</script>
<table class="adm-detail-content-table edit-table">
    <tbody>
    <tr>
        <td width="40%" class="adm-detail-content-cell-l">
            <?= Loc::getMessage('GENERATE_RANDOM') ?>
        </td>
        <td width="60%" class="adm-detail-content-cell-r">
            <select class="data-option" name="option[random]">
                <option value="N" <?= $sRandom == 'N' ? 'selected' : '' ?>><?= Loc::getMessage('RANDOM_VALUE_NO') ?></option>
                <option value="Y" <?= $sRandom == 'Y' ? 'selected' : '' ?>><?= Loc::getMessage('RANDOM_VALUE_YES') ?></option>
            </select>
        </td>
    </tr>
    <? if ($arGroupList): ?>
        <tr>
            <td width="40%" class="adm-detail-content-cell-l">
                <?= Loc::getMessage('SELECT_GROUPS') ?>
            </td>
            <td width="60%" class="adm-detail-content-cell-r">
                <select class="data-option" name="option[selected-groups][]" multiple size="5" style="width: 50%;">
                    <? foreach ($arGroupList as $group): ?>
                        <option value="<?= $group['ID'] ?>" <?= in_array($group['ID'],
                            $arSelectedGroups) ? 'selected' : '' ?>><?= $group['NAME'] ?></option>
                    <? endforeach; ?>
                </select>
            </td>
        </tr>
    <? endif; ?>
    </tbody>
</table>