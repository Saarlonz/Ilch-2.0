<?php
$countOfProfileFields = $this->get('countOfProfileFields');
$profileField = $this->get('profileField');
$profileFieldsTranslation = $this->get('profileFieldsTranslation');
$localeList = $this->get('localeList');

if ($profileField->getId()) {
    $fieldsetLegend = $this->getTrans('editProfileField');
} else {
    $fieldsetLegend = $this->getTrans('addProfileField');
}
?>

<script>
var indexList = [];

function addIndex(index) {
    indexList.push(index);
}
</script>

<fieldset>
    <legend>
        <?=$fieldsetLegend ?>
        <a class="badge" data-toggle="modal" data-target="#infoModal">
            <i class="fa fa-info"></i>
        </a>
    </legend>
    <form action="<?=$this->getUrl(['module' => 'user', 'controller' => 'profilefields', 'action' => 'save']) ?>"
          method="POST"
          class="form-horizontal"
          id="profileFieldForm">
        <?=$this->getTokenField() ?>
        <input name="profileField[id]"
               type="hidden"
               value="<?=$profileField->getId() ?>" />
        <div class="form-group">
            <label for="profileFieldName"
                   class="col-lg-3 control-label">
                <?=$this->getTrans('profileFieldName') ?>
            </label>
            <div class="col-lg-9">
                <input name="profileField[name]"
                       type="text"
                       id="profileFieldName"
                       class="form-control required"
                       placeholder="<?=$this->getTrans('profileFieldName') ?>"
                       value="<?=$this->escape($profileField->getName()) ?>" />
            </div>
            <div class="col-lg-9">
                <label>
            <?php if (!$profileField->getType()) : ?>
                <input value="1" type="checkbox" name="profileField[type]" /> <?=$this->getTrans('cat') ?>
            <?php else : ?>
                <input value="1" type="checkbox" name="profileField[type]" checked="checked" /> <?=$this->getTrans('cat') ?>
            <?php endif; ?>
                </label>
                <input name="profileField[position]"
                       type="hidden"
                       value="<?=($profileField->getId()) ? $profileField->getPosition() : $countOfProfileFields ?>" />
            </div>
        </div>
        <?php 
        $i = 0;
        foreach ($profileFieldsTranslation as $profileFieldTranslation) : ?>
        <div class="form-group" id="profileFieldTrans<?=$i ?>">
            <div class="col-lg-3">
                <button type="button" class="btn" onclick="deleteTranslation(<?=$i ?>)">-</button>
                <label for="profileFieldName"
                       class="control-label">
                    <?=(isset($localeList[$profileFieldTranslation->getLocale()])) ? $localeList[$profileFieldTranslation->getLocale()] : $profileFieldTranslation->getLocale() ?>
                </label>
            </div>
            <input name="profileFieldTrans<?=$i?>[field_id]"
                   type="hidden"
                   value="<?=$profileField->getId() ?>" />
            <input name="profileFieldTrans<?=$i?>[locale]"
                   type="hidden"
                   value="<?=$profileFieldTranslation->getLocale() ?>" />
            <div class="col-lg-9">
                <input name="profileFieldTrans<?=$i?>[name]"
                       type="text"
                       id="profileFieldName"
                       class="form-control"
                       placeholder="<?=$this->getTrans('profileFieldName') ?>"
                       value="<?=$this->escape($profileFieldTranslation->getName()) ?>" />
            </div>
            <script>addIndex(<?=$i ?>)</script>
        </div>
        <?php $i++;
        endforeach; ?>
        <div id="addTranslations">
        </div>
        <div class="col-lg-3">
            <button type="button" class="btn" onclick="addTranslations()">+</button>
        </div>
        <?=$this->getSaveBar() ?>
    </form>
</fieldset>

<?=$this->getDialog("infoModal", $this->getTrans('info'), $this->getTrans('profileFieldTransInfoText')); ?>

<script>
var index = <?=$i ?>;

$('#profileFieldForm').validate();

function addTranslations() {
    if (isDuplicate()) {
        return;
    }

    var html =  '<div class="form-group" id="profileFieldTrans'+index+'">'+
                    '<input name="profileFieldTrans'+index+'[field_id]"'+
                        'type="hidden"'+
                        'value="<?=$profileField->getId() ?>" />'+
                    '<div class="col-lg-3">'+
                        '<button type="button" class="btn" onclick="deleteTranslation('+index+')">-</button>'+
                        '<select name="profileFieldTrans'+index+'[locale]" onchange="isDuplicate()">'+
                            '<option value=""></option>'+
                        <?php
                        foreach ($localeList as $locale) :?>
                            '<option value="<?=key($localeList) ?>"><?=$locale ?></option>'+
                        <?php next($localeList);
                        endforeach; ?>
                        '</select>'+
                    '</div>'+
                    '<div class="col-lg-9">'+
                        '<input name="profileFieldTrans'+index+'[name]"'+
                               'type="text"'+
                               'id="profileFieldName"'+
                               'class="form-control"'+
                               'placeholder="<?=$this->getTrans('profileFieldName') ?>"'+
                               'value="" />'+
                    '</div>'+
                '</div>';
    var d1 = document.getElementById('addTranslations');
    d1.insertAdjacentHTML('beforeend', html);
    addIndex(index);
    index++;
}

function isDuplicate() {
    var allElements;
    var select_id;

    // indexList is undefined after deleting the last element with array.splice().
    if (indexList == undefined) {
        indexList = [];
    }

    for(x = 0; x < indexList.length; x++) {
        allElements = document.getElementsByName('profileFieldTrans'+indexList[x]+'[locale]')[0];
        for(y = x+1; y < indexList.length; y++) {
            select_id = document.getElementsByName('profileFieldTrans'+indexList[y]+'[locale]')[0];
            if(select_id.options[select_id.selectedIndex].value != "" && select_id.options[select_id.selectedIndex].value == allElements.value) {
                alert('<?=$this->getTrans('translationAlreadyExisting') ?>');
                deleteTranslation(indexList[y]);
                // Delete the locale so this one gets discarded.
                document.getElementsByName('profileFieldTrans'+indexList[y]+'[locale]')[0].value = '';
                indexList.splice(y,1);
                return true;
            }
        }
    }
    return false;
}

function deleteTranslation(a) {
    document.getElementById('profileFieldTrans'+a).style.display = 'none';
    document.getElementsByName('profileFieldTrans'+a+'[name]')[0].value = '';    
}
</script>