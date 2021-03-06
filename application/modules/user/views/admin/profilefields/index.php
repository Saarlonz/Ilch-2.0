<h1>
    <?=$this->getTrans('menuProfileFields') ?>
    <a class="badge" data-toggle="modal" data-target="#infoModal">
        <i class="fa fa-info"></i>
    </a>
</h1>
<form class="form-horizontal" id="downloadsForm" method="POST" action="">
    <?=$this->getTokenField(); ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col class="icon_width">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th><?=$this->getCheckAllCheckbox('check_users') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('profileFieldName') ?></th>
                </tr>
            </thead>
            <tbody id="sortable">
                <?php
                $profileFields = $this->get('profileFields');
                $profileFieldsTranslation = $this->get('profileFieldsTranslation');

                foreach ($profileFields as $profileField) :
                ?>
                <tr id="<?=$profileField->getId() ?>">
                    <td><?=$this->getDeleteCheckbox('check_users', $profileField->getId()) ?></td>
                    <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $profileField->getId()]) ?></td>
                    <td> <?=$this->getDeleteIcon(['action' => 'delete', 'id' => $profileField->getId()]) ?></td>
                    <?php
                    $found = false;

                    foreach ($profileFieldsTranslation as $profileFieldTrans) {
                        if ($profileField->getId() == $profileFieldTrans->getFieldId()) {
                            $profileFieldName = $profileFieldTrans->getName();
                            $found = true;
                            break;
                        }
                    } 

                    if (!$found) {
                        $profileFieldName = $profileField->getName();
                    }
                    ?>

                    <?php if (!$profileField->getType()) : ?>
                        <td><?=$this->escape($profileFieldName) ?></td>
                    <?php else: ?>
                        <td><b><?=$this->escape($profileFieldName) ?></b></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    </div>

    <div class="content_savebox">
        <button type="submit" class="btn btn-default" name="save" value="save">
            <?=$this->getTrans('saveButton') ?>
        </button>
        <input type="hidden" class="content_savebox_hidden" name="action" value="" />
        <div class="btn-group dropup">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <?=$this->getTrans('selected') ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu listChooser" role="menu">
                <li><a href="#" data-hiddenkey="delete"><?=$this->getTrans('delete') ?></a></li>
            </ul>
        </div>
    </div>
</form>

<?=$this->getDialog("infoModal", $this->getTrans('info'), $this->getTrans('profileFieldInfoText')); ?>

<script>
$(function() {
$( "#sortable" ).sortable();
$( "#sortable" ).disableSelection();
});

$('#downloadsForm').submit (
    function () {
        var items = $("#sortable tr");

        var linkIDs = [items.size()];
        var index = 0;

        items.each(
            function(intIndex) {
                linkIDs[index] = $(this).attr("id");
                index++;
            });

        $('#hiddenMenu').val(linkIDs.join(","));
    }
);
</script>
