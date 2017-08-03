<?php $x = 0; ?>
<?php foreach ($sites as $site): ?>
    <?= $x % 3 == 0 || $x == 0 ? '<div class="row">' : ''; ?>
    <div class="col-lg-3 index text-center">
        <?php if ($site->image): ?>
            <?= $this->Html->link($this->Html->image('sites'.DS.$site->image, [
                'alt' => $site->site_name,
                'class' => 'img-index'
            ]),
            ['controller' => 'Sites', 'action' => 'view', $site->id],
            ['escape' => false]); ?>
        <?php else: ?>
            <?= $this->Html->link($this->Html->image('cber-logo.png', [
                'alt' => $site->site_name,
                'class' => 'img-index'
            ]),
            ['controller' => 'Sites', 'action' => 'view', $site->id],
            ['escape' => false]); ?>
        <?php endif; ?>
        <h5><?= $site->site_name; ?></h5>
        <a href="<?= $site->url ?>"><?= $site->url ?></a>
        <p>
            <u><?= $site->in_progress == 1 ? 'In progress' : 'Live'; ?></u>
        </p>
    </div>
    <?= $x % 3 == 2 ? '</div>' : ''; ?>
    <?php $x = $x + 1; ?>
<?php endforeach; ?>
<?php if (!isset($site->id)): ?>
    <p>
        Sorry, there's nothing here! This probably suggests a problem. Contact <a href="mailto:edfox@bsu.edu">the admin</a> for advice!
    </p>
    <p>
        There might also just actually be nothing here. Go ahead and <?= $this->Html->link('add a site', [
            'controller' => 'Sites', 'action' => 'add'
        ]) ?> so this lame placeholder message goes away.
    </p>
<?php endif; ?>
