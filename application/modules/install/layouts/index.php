<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Ilch <?=VERSION ?> - Installation</title>
        <meta name="description" content="Ilch <?=VERSION ?> - Installation">
        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">
        <link href="<?=$this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('../application/modules/install/static/css/install.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ui-lightness/jquery-ui.min.css') ?>" rel="stylesheet">
        <script src="<?=$this->getVendorUrl('components/jquery/jquery.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery-ui.min.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    </head>
    <body>
        <div class="container">
            <div class="col-lg-offset-2 col-lg-8 col-md-12 col-sm-12 install_container">
                <div class="logo" title="<?=$this->getTrans('ilchInstall', (string)VERSION) ?>"></div>
                <div class="installVersion" title="<?=$this->getTrans('ilchInstall', (string)VERSION) ?>">
                    <?=$this->getTrans('ilchInstallVersion', (string)VERSION) ?>
                </div>
                <div class="alert alert-danger">This is a special version of Ilch 2 to test the update-feature.<br>
You should only have installed this one if you want to take part in testing the update-feature.<br>
If this is not the case then visit <a href="http://www.ilch.de">www.ilch.de</a> to download the latest stable.<br><br>
February 2017</div>
                <form autocomplete="off" class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
                    <?=$this->getTokenField() ?>
                    <div class="col-lg-4 col-md-3 col-sm-3 hidden-xs verticalLine install_step">
                        <?php $done = 1; ?>
                        <?php $menuCounter = count($this->get('menu')); ?>
                        <?php $lastAction = ''; ?>
                        <?php foreach ($this->get('menu') as $key => $values): ?>
                            <?php if (isset($values['done'])): ?>
                                <?php $done++; ?>
                                <?php $lastAction = $key; ?>
                            <?php endif; ?>
                            <div class="step-item <?=isset($values['done']) ? 'done': '' ?><?=$this->getRequest()->getActionName() == $key ? 'active': '' ?>">
                                <div class="step-content">
                                    <?=$this->getTrans($values['langKey']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-lg-8 col-md-9 col-sm-9">
                        <?php foreach ($this->get('menu') as $key => $values): ?>
                            <?php if ($this->getRequest()->getActionName() == $key): ?>
                                <h2><?=$this->getTrans($values['langKey']) ?></h2>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?=$this->getContent() ?>
                    </div>


                    <div class="save_box">
                        <?php if (!in_array($this->getRequest()->getActionName(), ['index', 'finish'])): ?>
                            <a href="<?=$this->getUrl(['action' => $lastAction]) ?>" class="btn btn-default pull-left">
                                <?=$this->getTrans('backButton') ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($this->getRequest()->getActionName() != 'finish'): ?>
                            <button type="submit" class="btn btn-primary pull-right" name="save">
                                <?php $buttonTrans = 'nextButton'; ?>

                                <?php if ($this->getRequest()->getActionName() == 'configuration'): ?>
                                    <?php $buttonTrans = 'installButton'; ?>
                                <?php endif; ?>

                                <?=$this->getTrans($buttonTrans) ?>
                            </button>
                        <?php endif; ?>

                        <?php if ($this->getRequest()->getActionName() == 'finish'): ?>
                            <div class="pull-right">
                                <a target="_blank" href="<?=$this->getUrl() ?>" class="btn btn-success">
                                    Frontend
                                </a>
                                <a target="_blank" href="<?=$this->getUrl().'/admin' ?>" class="btn btn-primary">
                                    Administration
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
