<div class="row box" id="toolbar">
    <?php if($button): ?> <div class="col-md-9 col-sm-8 col-xs-7"><?= $button; ?></div><?php endif; ?>
    <?php if(!empty($placeholder)): ?>
        <?= $this->render('search', compact('placeholder')); ?>
    <?php endif; ?>
</div>