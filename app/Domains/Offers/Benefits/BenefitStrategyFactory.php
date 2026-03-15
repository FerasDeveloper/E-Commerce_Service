<?php

namespace App\Domains\Offers\Benefits;

use InvalidArgumentException;

class BenefitStrategyFactory
{
  public function make(string $benefitType): BenefitStrategy
  {
    return match (strtolower($benefitType)) {
      'percentage' => new PercentageDiscountStrategy(),
      'fixed_amount' => new FixedAmountDiscountStrategy(),
      default => throw new InvalidArgumentException("Unsupported benefit_type: {$benefitType}"),
    };
  }
}

