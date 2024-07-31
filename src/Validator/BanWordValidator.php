<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BanWordValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var BanWord $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        //convert in string word
        $value = strtolower($value);
        //verification if the word string is in banWords
        foreach ($constraint->banWords as $banWord){
            //if the verification is correct this function return a message of BanWord.php and add the word banner in the message
            if (str_contains($value, $banWord)){
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{banWord}}', $banWord)
                    ->addViolation();
            }
        }
    }
}
