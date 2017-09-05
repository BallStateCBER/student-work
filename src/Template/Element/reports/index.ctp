<div class="row">
    <div class="col-lg-9">
        <h3><?= __('Reports') ?></h3>
        <table cellpadding="15" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('project_name') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('student_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('supervisor_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('start_date') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('end_date') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?= h($report->project_name) ?></td>
                    <td><?= h($report->student_id) ?></td>
                    <td><?= h($report->supervisor_id) ?></td>
                    <td><?= h(date('F j, Y', strtotime($report->start_date))) ?></td>
                    <td class="<?= strtotime($report->end_date) > strtotime(date('Y-m-d')) || strtotime($report->end_date) == null ? 'alert alert-danger' : 'alert alert-success'; ?>"><?= !$report->end_date ? 'No end date' : h(date('F j, Y', strtotime($report->end_date))) ?></u></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $report->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $report->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $report->id], ['confirm' => __('Are you sure you want to delete # {0}?', $report->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
        </div>
    </div>
    <div class="col-lg-3">
        <h3>Actions</h3>
        <div id="accordion" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mb-0">
                        <?= $this->Html->link(__('New Report'), ['action' => 'add']) ?>
                    </h6>
                </div>
            </div>
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mb-0">
                        <?= $this->Html->link(__('All Projects'), ['action' => 'index']) ?>
                    </h6>
                </div>
            </div>
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mb-0">
                        <?= $this->Html->link(__('Current Projects'), ['action' => 'current']) ?>
                    </h6>
                </div>
            </div>
            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mb-0">
                        <?= $this->Html->link(__('Past Projects'), ['action' => 'past']) ?>
                    </h6>
                </div>
            </div>
            <div class="card">
                <div class="card-header" role="tab" id="headingTwo">
                    <h6 class="mb-0">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Reports by project name
                        </a>
                    </h6>
                </div>
                <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="card-block">
                        <?php foreach ($projects as $project): ?>
                            <?= $this->Html->link(__($project->name), ['action' => 'project', $project->id]) ?><br />
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" role="tab" id="headingThree">
                    <h6 class="mb-0">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Reports by student
                        </a>
                    </h6>
                </div>
                <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="card-block">
                        <?php foreach ($students as $key => $name): ?>
                            <?= $this->Html->link(__($name), ['action' => 'student', $key]) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" role="tab" id="headingFour">
                    <h6 class="mb-0">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Reports by supervisor
                        </a>
                    </h6>
                </div>
                <div id="collapseFour" class="collapse" role="tabpanel" aria-labelledby="headingFour">
                    <div class="card-block">
                        <?php foreach ($supervisors as $key => $name): ?>
                            <?= $this->Html->link(__($name), ['action' => 'supervisor', $key]) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
