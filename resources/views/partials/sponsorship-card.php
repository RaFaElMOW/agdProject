<?php
/** @var array $card */
?>
<div class="col-md-6 mb-4 ftco-animate">
  <div class="staff sponsor-card text-center">
    <?php if (!empty($card['icon'])): ?><div class="icon-wrap"><span class="<?php echo e($card['icon']); ?>"></span></div><?php endif; ?>
    <h3 class="mb-2"><?php echo e($card['title']); ?></h3>
    <?php if ($card['value'] !== null): ?>
    <p class="sponsor-price"><?php echo e($card['currency'] . ' ' . $card['value']); ?> <span>/mês</span></p>
    <?php endif; ?>
    <?php if (!empty($card['description'])): ?><p><?php echo e($card['description']); ?></p><?php endif; ?>
    <?php if (!empty($card['image'])): ?><p><img src="<?php echo e(public_asset_url($card['image'])); ?>" alt="" style="max-width:100%; border-radius:6px;"></p><?php endif; ?>
    <?php if (!empty($card['cta_link'])): ?>
    <p class="mb-0"><a href="<?php echo e($card['cta_link']); ?>" class="btn btn-primary px-4 py-3"<?php echo str_starts_with($card['cta_link'], 'http') ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo t('I Want To Sponsor Button'); ?></a></p>
    <?php endif; ?>
  </div>
</div>
