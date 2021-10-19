<?php foreach ($reports as $report): ?>
    <div class="card my-2">
        <div class="card-header">
            <?php if ($report->city->name): ?>
                <?= $report->city->name ?>:
            <?php else: ?>
                Общий:
            <?php endif; ?>
            <?= $report->title ?>
        </div>
        <div class="card-body">
            <p class="card-text"> <?= $report->text ?> </p>
            <p class="card-text"></p>
        </div>
    </div>
<?php endforeach; ?>