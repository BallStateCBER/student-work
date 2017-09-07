<h1>
    Fund: <?= $fund->name; ?>
</h1>
<small>Admin: <?= $this->Html->link('Edit this funding source', ['controller' => 'Funds', 'action' => 'edit', $fund->id], ['class' => 'text-danger']) ?></small>
<div class="row">
    <div class="col-lg-3">
        <label class="form-control-label">
            Organization
        </label>
        <div class="form-control">
            <?= $fund->organization ?>
        </div>
    </div>
    <div class="col-lg-3">
        <label class="form-control-label">
            Amount
        </label>
        <div class="form-control">
            <?= $fund->amount ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <label class="form-control-label">
            Funding details
        </label>
        <div class="form-control">
            <?= $fund->funding_details ?>
        </div>
    </div>
</div>
