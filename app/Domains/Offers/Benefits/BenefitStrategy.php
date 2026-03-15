<?php

namespace App\Domains\Offers\Benefits;

interface BenefitStrategy
{
  /**
   * @param array $config Offer.benefit_config
   */
  public function calculate(float $originalPrice, int $quantity, array $config): float;
}

