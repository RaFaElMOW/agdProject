<?php
use App\Services\DonationService;

$donationService = new DonationService();
$presetsBRL = $donationService->presetsFor('BRL');
$presetsUSD = $donationService->presetsFor('USD');
$accountNational = $donationService->primaryAccountFor('national');
$accountInternational = $donationService->primaryAccountFor('international');
?>
<div class="modal fade" id="donationModal" tabindex="-1" role="dialog" aria-labelledby="donationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="donationModalLabel">Fazer uma doação</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#donationNational" role="tab">Nacional (BRL)</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#donationInternational" role="tab">Internacional (USD)</a></li>
        </ul>
        <div class="tab-content mt-4">
          <div class="tab-pane fade show active" id="donationNational" role="tabpanel">
            <?php if ($accountNational === null): ?>
              <p class="text-muted">Pagamento online indisponível para o Brasil no momento. Use os dados bancários/PIX na <a href="donate.php">página de doação</a>.</p>
            <?php else: ?>
              <div class="donation-amounts mb-3">
                <?php foreach ($presetsBRL as $p): ?>
                  <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2 donation-amount-btn" data-amount="<?php echo e($p['amount']); ?>">R$ <?php echo e($p['amount']); ?></button>
                <?php endforeach; ?>
              </div>
              <div class="form-group">
                <label>Outro valor (R$)</label>
                <input type="number" min="1" step="0.01" class="form-control donation-custom-amount">
              </div>
              <button type="button" class="btn btn-primary btn-block donation-submit" data-account="<?php echo (int) $accountNational['id']; ?>">Continuar para o PayPal</button>
            <?php endif; ?>
          </div>
          <div class="tab-pane fade" id="donationInternational" role="tabpanel">
            <?php if ($accountInternational === null): ?>
              <p class="text-muted">Online payment is currently unavailable. Please see the bank details on the <a href="donate.php">donation page</a>.</p>
            <?php else: ?>
              <div class="donation-amounts mb-3">
                <?php foreach ($presetsUSD as $p): ?>
                  <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2 donation-amount-btn" data-amount="<?php echo e($p['amount']); ?>">USD <?php echo e($p['amount']); ?></button>
                <?php endforeach; ?>
              </div>
              <div class="form-group">
                <label>Other amount (USD)</label>
                <input type="number" min="1" step="0.01" class="form-control donation-custom-amount">
              </div>
              <button type="button" class="btn btn-primary btn-block donation-submit" data-account="<?php echo (int) $accountInternational['id']; ?>">Continue to PayPal</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('#donationModal .donation-amount-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var group = btn.closest('.donation-amounts');
      group.querySelectorAll('.donation-amount-btn').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      btn.closest('.tab-pane').querySelector('.donation-custom-amount').value = btn.dataset.amount;
    });
  });
  document.querySelectorAll('#donationModal .donation-submit').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var pane = btn.closest('.tab-pane');
      var amount = parseFloat(pane.querySelector('.donation-custom-amount').value);
      if (!amount || amount <= 0) {
        pane.querySelector('.donation-custom-amount').focus();
        return;
      }
      window.location.href = 'donate-redirect.php?account_id=' + encodeURIComponent(btn.dataset.account) + '&amount=' + encodeURIComponent(amount.toFixed(2));
    });
  });
});
</script>
