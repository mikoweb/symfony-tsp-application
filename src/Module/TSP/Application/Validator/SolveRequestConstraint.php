<?php

namespace App\Module\TSP\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Attribute;

#[Attribute]
class SolveRequestConstraint extends Constraint
{
    public function validatedBy(): string
    {
        return SolveRequestValidator::class;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
