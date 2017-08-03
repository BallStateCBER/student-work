<?php $x = 0; ?>
<?php foreach ($localprojects as $localproject): ?>
    <?= $x % 3 == 0 || $x == 0 ? '<div class="row">' : ''; ?>
    <div class="col-lg-3 index text-center">
        <h5><?= $localproject->name; ?></h5>
        <?= $localproject->organization; ?>
        <p>
            <?= $localproject->description; ?>
        </p>
        <?php foreach ($localproject->users as $user): ?>
            <ul>
                <li>
                    <?= $user->name; ?>, <?= $user->_joinData->role; ?>
                </li>
            </ul>
        <?php endforeach; ?>
    </div>
    <?= $x % 3 == 2 ? '</div>' : ''; ?>
    <?php $x = $x + 1; ?>
<?php endforeach; ?>
<?php if (!isset($localproject->id)): ?>
    <p>
        Sorry, there's nothing here! This probably suggests a problem. Contact <a href="mailto:edfox@bsu.edu">the admin</a> for advice!
    </p>
    <p>
        There might also just actually be nothing here. Go ahead and <?= $this->Html->link('add a project', [
            'controller' => 'Localprojects', 'action' => 'add'
        ]) ?> so this lame placeholder message goes away.
    </p>
<?php endif; ?>
