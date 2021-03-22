<?php if (!$data['contactRequest']->getResponse()): ?>
    <div style="background-color: red; color: white">Si zinute dar neatsakyta</div>
<?php else: ?>
    <div style="background-color: green; color: white">Si zinute jau atsakyta</div>
<?php endif ?>
<h1><?= $data['contactRequest']->getSubject() ?></h1>
<h3>(<?= $data['contactRequest']->getSubjectOptional() ?>)</h3>
<p><?= $data['contactRequest']->getMessage() ?></p>
<hr>
<h4>Kontaktai</h4>
<p><?= $data['contactRequest']->getFirstName() . ' ' . $data['contactRequest']->getLastName() ?>
    <br>
    <?= $data['contactRequest']->getEmail() ?>
    <br>
    <?= $data['contactRequest']->getPhone() ?></p>
<hr>

<?php if (!$data['contactRequest']->getResponse()): ?>
    <?php echo $data['form'] ?>
<?php else: ?>
    <p>Si zinute jau atsakyta</p>
    <p>Jusu atsakymas: <?= $data['contactRequest']->getResponse() ?></p>
<?php endif ?>
