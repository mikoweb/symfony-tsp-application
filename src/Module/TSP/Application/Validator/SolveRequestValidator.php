<?php

namespace App\Module\TSP\Application\Validator;

use App\Module\TSP\UI\Dto\SolveRequestDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use function Symfony\Component\String\u;

class SolveRequestValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof SolveRequestConstraint) {
            throw new UnexpectedTypeException($constraint, SolveRequestConstraint::class);
        }

        if (!$value instanceof SolveRequestDto) {
            throw new UnexpectedValueException($value, SolveRequestDto::class);
        }

        if (!isset($value->locations[$value->initialLocationIndex])) {
            $this->context->buildViolation(
                u('Out of locations range - the number of elements is ')
                    ->append((string) count($value->locations))
                    ->toString()
            )->atPath('initialLocationIndex')->addViolation();
        }
    }
}
