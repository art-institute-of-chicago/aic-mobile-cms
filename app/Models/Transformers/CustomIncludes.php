<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use Illuminate\Support\Str;

/**
 * Allows for setting a different serializer than that of the parent transformer
 * for an included child transformer.
 *
 * Usage:
 *  protected $customIncludes = [
 *      'related_models' => DifferentSerializer::class,
 *  ];
 *
 *  public function transform($model)
 *  {
 *      return $this->withCustomIncludes($model, [
 *          'key' => $model->value,
 *      ]);
 *  }
 *
 *  protected includeRelatedModels($models)
 *  {
 *      return $this->collection($models->related_models, new ModelTransformer(), 'optional_key');
 *  }
 */
trait CustomIncludes
{
    private $currentInclude;

    /**
     * Calls the custom include methods and merges their data into the parent.
     */
    public function withCustomIncludes(TwillModelContract $item, array $data): array
    {
        if (!isset($this->customIncludes)) {
            return $data;
        }
        $data = collect($data);
        foreach ($this->customIncludes as $includeName => $serializerClass) {
            $this->currentInclude = [$includeName => $serializerClass];
            $methodName = Str::of($includeName)->studly()->start('include')->toString();
            $data = $data->merge($this->{$methodName}($item));
        }
        return $data->toArray();
    }

    /**
     * Transforms the data according to the specified serializer.
     */
    public function collection($data, $transformer, $resourceKey = null)
    {
        $collection = collect($data)->map(function ($datum) use ($transformer) {
            return $transformer->transform($datum);
        })->toArray();
        $resourceKey = $resourceKey ?: current(array_keys($this->currentInclude));
        $serializerClass = current($this->currentInclude);
        $serializer = new $serializerClass();
        return $serializer->collection($resourceKey, $collection);
    }
}
