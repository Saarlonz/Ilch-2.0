<link href="<?=$this->getModuleUrl('static/css/maintenance.css') ?>" rel="stylesheet">
<link href="<?=$this->getStaticUrl('js/datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">

<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('menuMaintenance') ?></legend>
    <div class="form-group">
        <label for="maintenanceMode" class="col-lg-2 control-label">
            <?=$this->getTrans('maintenanceMode') ?>:
        </label>
        <div class="col-lg-2">
            <div class="radio">
                <label>
                    <input type="radio"
                       name="maintenanceMode"
                       value="1"
                <?php if ($this->get('maintenanceMode') == '1') { echo 'checked="checked"';} ?> /> <?=$this->getTrans('on') ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio"
                       name="maintenanceMode"
                       value="0"
                <?php if ($this->get('maintenanceMode') != '1') { echo 'checked="checked"';} ?>> <?=$this->getTrans('off') ?>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="maintenanceDateTime" class="col-md-2 control-label">
            <?=$this->getTrans('maintenanceDateTime') ?>:
        </label>
        <div class="col-lg-2 input-group date form_datetime">
            <input class="form-control"
                   type="text"
                   name="maintenanceDateTime"
                   value="<?=date('d.m.Y H:i', strtotime($this->get('maintenanceDate'))) ?>"
                   readonly>
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="maintenanceStatus" class="col-md-2 control-label">
            <?=$this->getTrans('maintenanceStatus') ?>:
        </label>
        <div class="col-lg-4">
            <div class="range">
                <input type="range" name="maintenanceStatus" min="0" max="100" value="<?=$this->escape($this->get('maintenanceStatus')) ?>" onchange="range.value=value">
                <output id="range"><?=$this->escape($this->get('maintenanceStatus')) ?></output>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="maintenanceText" class="col-lg-2 control-label">
            <?=$this->getTrans('maintenanceText') ?>:
        </label>
        <div class="col-lg-10">
            <textarea class="form-control"
                   name="maintenanceText" 
                   id="ilch_html"
                   rows="5"><?=$this->escape($this->get('maintenanceText')) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>

<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?=$this->getStaticUrl('js/datetimepicker/js/locales/bootstrap-datetimepicker.de.js')?>" charset="UTF-8"></script>
<script type="text/javascript">
    $( document ).ready(function()
    {
        $(".form_datetime").datetimepicker({
            format: 'dd.mm.yyyy hh:ii',
            startDate: new Date(),
            autoclose: true,
            language: 'de',
            minuteStep: 15
        });
    });
</script>