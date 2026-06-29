<?php
/** @var array $book */
?>
<div class="col-md-6 mb-4 ftco-animate">
  <div class="staff sponsor-card text-center">
    <?php if (!empty($book['cover'])): ?>
    <?php $coverUrl = e(public_asset_url($book['cover'])); ?>
    <?php if (!empty($book['link'])): ?>
    <a href="<?php echo e($book['link']); ?>" target="_blank" rel="noopener" class="block-20 d-block" style="background-image: url(<?php echo $coverUrl; ?>); margin: -25px -25px 20px -25px; border-radius: 4px 4px 0 0;"></a>
    <?php else: ?>
    <div class="block-20 d-block" style="background-image: url(<?php echo $coverUrl; ?>); margin: -25px -25px 20px -25px; border-radius: 4px 4px 0 0;"></div>
    <?php endif; ?>
    <?php endif; ?>
    <h3 class="mb-1"><?php echo e($book['title']); ?></h3>
    <?php if (!empty($book['author'])): ?><p class="mb-2" style="color:#7cbd1e;font-weight:500;"><?php echo e($book['author']); ?></p><?php endif; ?>
    <?php if (!empty($book['format']) || $book['price'] !== null): ?>
    <p class="mb-3">
      <?php if ($book['price'] !== null): ?><span class="badge badge-pill mr-1 mb-1" style="background:#fff6f2;color:#f86f2d;padding:8px 14px;"><?php echo e(($book['currency'] ?: '') . ' ' . $book['price']); ?></span><?php endif; ?>
      <?php if (!empty($book['format'])): ?><span class="badge badge-pill mb-1" style="background:#fff6f2;color:#f86f2d;padding:8px 14px;"><?php echo e($book['format']); ?></span><?php endif; ?>
    </p>
    <?php endif; ?>
    <?php if (!empty($book['description'])): ?><p><?php echo e($book['description']); ?></p><?php endif; ?>
    <?php if (!empty($book['link'])): ?>
    <p class="mb-0"><a href="<?php echo e($book['link']); ?>" target="_blank" rel="noopener" class="btn btn-primary px-4 py-3"><?php echo t('I Want To Buy Button'); ?></a></p>
    <?php endif; ?>
  </div>
</div>
