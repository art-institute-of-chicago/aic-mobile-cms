<?php

namespace App\Models\Transformers;

use App\Models\Api\CollectionObject as ApiCollectionObject;
use App\Models\Annotation;
use App\Models\CollectionObject;
use App\Models\LoanObject;

/**
 * What is a NID? Nobody knows. All we know is that they need to be unique
 * integers representing either a loan / collection object, or an annotation of
 * a certain type . To create an NID, we calculate the Cantor Pair integer based
 * on the item's type and ID.
 */
trait HasNid
{
    public const COLLECTION_OBJECT = 1;
    public const LOAN_OBJECT = 2;

    public function calculateNid($object): int
    {
        switch (get_class($object)) {
            case Annotation::class:
                $objectType = $object->types->first()->id;
                break;
            case ApiCollectionObject::class:
                $objectType = self::COLLECTION_OBJECT;
                $object = $object->getAugmentedModel();
                break;
            case CollectionObject::class:
                $objectType = self::COLLECTION_OBJECT;
                break;
            case LoanObject::class:
                $objectType = self::LOAN_OBJECT;
                break;
            default:
                $objectType = 0;
        }

        return self::cantorPair((int)$objectType, (int) $object->id);
    }

    /**
     * Generate a unique ID based on a combination of two numbers.
     */
    public static function cantorPair(int $x, int $y): int
    {
        return (($x + $y) * ($x + $y + 1)) / 2 + $y;
    }
}
