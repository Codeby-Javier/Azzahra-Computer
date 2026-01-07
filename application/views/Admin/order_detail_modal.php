<?php
  $safeTrans = preg_replace('/[^A-Za-z0-9_-]/', '-', $row['trans_kode'] ?? 'unknown');
  $modalId   = "detailModal-{$safeTrans}";
  $suffix    = $safeTrans;
?>
<script>
console.log('Loading modal for trans_kode:', '<?= $row['trans_kode'] ?>');
console.log('Modal ID:', '<?= $modalId ?>');
console.log('Row data:', <?= json_encode($row) ?>);
</script>
<div class="modal" id="<?= $modalId ?>" style="z-index: 10000;">
  <div class="modal__content modal__content--xl p-0" style="max-height: 90vh; overflow-y: auto; width: 90%; max-width: 1200px;">
    <div class="bg-white rounded-lg overflow-hidden">
      <div class="p-8 text-center">
        <h2 class="text-2xl font-bold text-green-600 mb-4">MODAL IS WORKING!</h2>
        <p class="text-lg text-gray-700 mb-4">Transaction Code: <strong><?= $row['trans_kode']; ?></strong></p>
        <p class="text-gray-600">If you can see this message, the modal is properly displayed on top of other elements.</p>
        <button type="button" data-tw-dismiss="modal" class="mt-6 px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
          Close Modal
        </button>
      </div>
    </div>
  </div>
</div>
