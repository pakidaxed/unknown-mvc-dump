<hr>
<div class="products-list">
<?php foreach ($data['products'] as $product): ?>
    <div class="row">
        <div class="product-name">
            <?= $product->getName(); ?>
        </div>
        <div class="product-sku">
            <?= $product->getSku(); ?>
        </div>
        <div class="product-qty">
            <?= $product->getQty(); ?>
        </div>
        <div class="product-qty">
            <?= $product->getDescription(); ?>
        </div>
        <div class="edit">
            <a href="/products/edit/<?= $product->getId() ?>">Edit</a>
        </div>
        <form method="post" action="/products/remove">
            <input type="hidden" name="id" value="<?= $product->getId() ?>">
            <input type="submit" value="X">
        </form>
    </div>
<?php endforeach;?>
</div>
<hr>