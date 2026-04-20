<?php

namespace App\Domains\E_Commerce\Actions\Stock;

use App\Services\CMS\CMSApiClient;

class UpdateStockInCMSAction
{
  public function __construct(
    protected CMSApiClient $cms
  ) {}

  public function execute(array $items)
  {
    foreach ($items as $item) {

      // إذا ما عنده stock skip
      if (!isset($item['count'])) {
        continue;
      }

      // 🔥 إعادة جلب entry من CMS (لمنع التضارب)
      $entry = $this->cms->getEntryValuesById($item['slug']);

      $currentCount = (int) $entry['values']['count'];
      $requested = (int) $item['quantity'];

      if ($requested > $currentCount) {
        throw new \Exception(
          "Stock changed! Product {$item['title']} now has only {$currentCount}"
        );
      }

      $newCount = $currentCount - $requested;

      // 🔥 تحديث CMS
      $this->cms->updateEntry($item['product_id'], [
        'values' => [
          'count' => $newCount
        ]
      ]);
    }
  }
}
