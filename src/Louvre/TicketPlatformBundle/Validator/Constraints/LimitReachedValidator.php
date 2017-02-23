<?php

namespace Louvre\TicketPlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LimitReachedValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}