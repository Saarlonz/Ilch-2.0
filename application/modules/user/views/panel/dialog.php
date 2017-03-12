<?php $profil = $this->get('profil'); ?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>
        </div>
        <div class="col-lg-10">
            <legend><?=$this->getTrans('dialog'); ?></legend>
            <div class="panel-body">
                <ul class="dialog">
                <?php if ($this->get('dialog') == !''): ?>
                <?php foreach ($this->get('dialog') as $dialog): ?>
                        <?php $date = new \Ilch\Date($dialog->getTime()); ?>
                    <li class="left clearfix" <?php if ($dialog->getRead() != null) {echo 'style="background-color: rgba(239, 229, 219, 0.5)"';} ?>>
                        <span class="pull-left">
                            <img class="img-circle avatar" src="<?=$this->getUrl().'/'.$dialog->getAvatar() ?>" alt="User Avatar">
                        </span>
                        <div class="dialog-body clearfix">
                            <div class="header">
                                <strong>
                                    <a href="<?=$this->getUrl(['controller' => 'panel', 'action' => 'dialogview', 'id' => $dialog->getCId()]) ?>"><?=$this->escape($dialog->getName()) ?></a>
                                </strong>
                                <small class="pull-right">
                                    <i class="fa fa-clock-o"></i> <?=$date->format('d.m.Y H:i', true) ?>
                                </small>
                            </div>
                            <p>
                               <?=nl2br($this->getHtmlFromBBCode($this->escape($dialog->getText()))) ?>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
                <?php else: ?> 
                    <p><?=$this->getTrans('noDialog') ?></p>
                <?php endif; ?>   
                </ul>
            </div>
        </div>
    </div>
</div>
