<?php

namespace App\Service;


use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class ConstraintsInterface
{
    /**
     * @param string $subjectName - En cas d'erreur "{{ $subjectName }} doit etre superieur ou égale à {{ $min }}
     */
    public static function NumberConstraint(int|float $min, int|float $max, string $subjectName, string $type = 'integer'): array
    {
        $constraint = [
            new Range([
                'min' => $min,
                'max' => $max,
                'minMessage' => "$subjectName doit être supérieure ou égale à $min" ,
                'maxMessage' => "$subjectName doit être inférieure à $max",
            ]),
        ];
        $type_constraint = match($type) {
            'float' => [new Type(['type' => 'float', 'message' => 'Le champ doit être un nombre décimal'])],
            'integer' => [new Type(['type' => 'integer', 'message' => 'Le champ doit être un entier'])],
            'default' => []
        };
        return array_merge($type_constraint, $constraint);
    }

    public static function StringConstraint(int $min_len, int $max_len, string $subject, bool $notBlank = true, bool $notNull = true): array
    {
        $constraints = [];
        if ($notNull)
            $constraints = array_merge($constraints, [new NotNull(['message' => "$subject ne doit pas être null"])]);
        if ($notBlank)
            $constraints = array_merge($constraints, [new NotBlank(['message' => "$subject ne doit pas être vide"])]);
        return array_merge($constraints, [
            new Length([
                'min' => $min_len,
                'max' => $max_len,
                'minMessage' => "$subject doit contenir au moins $min_len caractères",
                'maxMessage' => "$subject doit contenir au maximum $max_len caractères",
            ])]);
    }
}