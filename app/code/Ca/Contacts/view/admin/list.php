<div class="contact-request-box-list">
    <?php foreach ($data['contactRequests'] as $contactRequest): ?>
        <div class="contact-request-box">
    <span class="subject"><?= $contactRequest['subject'] ?>
        <span class="subject-optional">(<?= $contactRequest['subject_optional'] ?>)</span>
        <?php if (!$contactRequest['seen']): ?>
            <sup class="not-seen">NO SEEN YET</sup>
        <?php endif ?>
        </span>
            <br/>
            <?= $contactRequest['first_name'] . ' ' . $contactRequest['last_name'] . '(' . $contactRequest['email'] . ')' ?>
            <br/><br />
            <div class="edit">
                <a href="/contacts/show/<?= $contactRequest['id'] ?>">Rodyti zinute</a>
            </div>
            <form method="post" action="/contacts/remove" class="delete">
                <input type="hidden" name="id" value="<?= $contactRequest['id'] ?>">
                <input type="submit" value="X">
            </form>
        </div>
    <?php endforeach; ?>
</div>