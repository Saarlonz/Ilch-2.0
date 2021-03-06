<?php
$date = new \Ilch\Date();
$entrantsMapper = $this->get('entrantsMapper');
?>

<?php include APPLICATION_PATH.'/modules/events/views/index/navi.php'; ?>

<?php if ($this->get('eventListUpcoming') == '' AND $this->get('getEventList') == '' AND $this->get('getEventListOther') == '' AND $this->get('eventListPast') == ''): ?>
    <h1><?=$this->getTrans('menuEventAll') ?></h1>
    <div class="row">
        <div class="col-lg-12"><?=$this->getTrans('noEvent') ?></div>
    </div>
<?php endif; ?>

<?php if ($this->get('eventListUpcoming') != ''): ?>
    <h1><?=$this->getTrans('menuEventUpcoming') ?></h1>
    <div class="row">
        <div class="col-lg-12">
            <ul class="event-list">
                <?php if ($this->get('eventListUpcoming') != ''): ?>
                    <?php foreach ($this->get('eventListUpcoming') as $eventlist): ?>
                        <?php $date = new \Ilch\Date($eventlist->getStart()); ?>
                        <li>
                            <time>
                                <span class="day"><?=$date->format("j", true) ?></span>
                                <span class="month"><?=$date->format("M", true) ?></span>
                            </time>
                            <div class="info">
                                <h2 class="title"><a href="<?=$this->getUrl('events/show/event/id/' . $eventlist->getId()) ?>"><?=$this->escape($eventlist->getTitle()) ?></a></h2>
                                <p class="desc">
                                    <?php $place = explode(', ', $this->escape($eventlist->getPlace()), 2); ?>
                                    <?=$place[0] ?>
                                    <?php if (!empty($place[1])): ?>
                                        <br /><span class="text-muted"><?=$place[1] ?></span>
                                    <?php endif; ?>
                                </p>
                                <?php $agree = 1; $maybe = 0; ?>
                                <?php if ($entrantsMapper->getEventEntrantsById($eventlist->getId()) != ''): ?>
                                    <?php foreach ($entrantsMapper->getEventEntrantsById($eventlist->getId()) as $eventEntrantsUser): ?>
                                        <?php if ($eventEntrantsUser->getStatus() == 1): ?>
                                            <?php $agree++; ?>
                                        <?php elseif ($eventEntrantsUser->getStatus() == 2): ?>
                                            <?php $maybe++; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <ul>
                                    <li style="width:33%;"><?=$this->getTrans('guest') ?></li>
                                    <li style="width:33%;"><?=$agree ?> <i class="fa fa-check"></i></li>
                                    <li style="width:33%;"><?=$maybe ?> <i class="fa fa-question"></i></li>
                                </ul>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?=$this->getTrans('noEvent') ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->get('getEventListOther') != ''): ?>
    <br />
    <h1><?=$this->getTrans('menuEventOtherList') ?></h1>
    <div class="row">
        <div class="col-lg-12">
            <ul class="event-list">
                <?php foreach ($this->get('getEventListOther') as $eventlist): ?>
                    <?php $date = new \Ilch\Date($eventlist->getStart()); ?>
                    <li>
                        <time>
                            <span class="day"><?=$date->format("j", true) ?></span>
                            <span class="month"><?=$date->format("M", true) ?></span>
                        </time>
                        <div class="info">
                            <h2 class="title"><a href="<?=$this->getUrl('events/show/event/id/' . $eventlist->getId()) ?>"><?=$this->escape($eventlist->getTitle()) ?></a></h2>
                            <p class="desc">
                                <?php $place = explode(', ', $this->escape($eventlist->getPlace()), 2); ?>
                                <?=$place[0] ?>
                                <?php if (!empty($place[1])): ?>
                                    <br /><span class="text-muted"><?=$place[1] ?></span>
                                <?php endif; ?>
                            </p>
                            <?php $agree = 1; $maybe = 0; ?>
                            <?php if ($entrantsMapper->getEventEntrantsById($eventlist->getId()) != ''): ?>
                                <?php foreach ($entrantsMapper->getEventEntrantsById($eventlist->getId()) as $eventEntrantsUser): ?>
                                    <?php if ($eventEntrantsUser->getStatus() == 1): ?>
                                        <?php $agree++; ?>
                                    <?php elseif ($eventEntrantsUser->getStatus() == 2): ?>
                                        <?php $maybe++; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <ul>
                                <li style="width:33%;"><?=$this->getTrans('guest') ?></li>
                                <li style="width:33%;"><?=$agree ?> <i class="fa fa-check"></i></li>
                                <li style="width:33%;"><?=$maybe ?> <i class="fa fa-question"></i></li>
                            </ul>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->get('eventListPast') != ''): ?>
    <br />
    <h1><?=$this->getTrans('menuEventPast') ?></h1>
    <div class="row">
        <div class="col-lg-12">
            <ul class="event-list">
                <?php foreach ($this->get('eventListPast') as $eventlist): ?>
                    <?php $date = new \Ilch\Date($eventlist->getStart()); ?>
                    <li>
                        <time>
                            <span class="day"><?=$date->format("j", true) ?></span>
                            <span class="month"><?=$date->format("M", true) ?></span>
                        </time>
                        <div class="info">
                            <h2 class="title"><a href="<?=$this->getUrl('events/show/event/id/' . $eventlist->getId()) ?>"><?=$this->escape($eventlist->getTitle()) ?></a></h2>
                            <p class="desc">
                                <?php $place = explode(', ', $this->escape($eventlist->getPlace()), 2); ?>
                                <?=$place[0] ?>
                                <?php if (!empty($place[1])): ?>
                                    <br /><span class="text-muted"><?=$place[1] ?></span>
                                <?php endif; ?>
                            </p>
                            <?php $agree = 1; $maybe = 0; ?>
                            <?php if ($entrantsMapper->getEventEntrantsById($eventlist->getId()) != ''): ?>
                                <?php foreach ($entrantsMapper->getEventEntrantsById($eventlist->getId()) as $eventEntrantsUser): ?>
                                    <?php if ($eventEntrantsUser->getStatus() == 1): ?>
                                        <?php $agree++; ?>
                                    <?php elseif ($eventEntrantsUser->getStatus() == 2): ?>
                                        <?php $maybe++; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <ul>
                                <li style="width:33%;"><?=$this->getTrans('guest') ?></li>
                                <li style="width:33%;"><?=$agree ?> <i class="fa fa-check"></i></li>
                                <li style="width:33%;"><?=$maybe ?> <i class="fa fa-question"></i></li>
                            </ul>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
