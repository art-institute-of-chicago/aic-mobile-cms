<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AnnotationRepository;
use App\Repositories\Serializers\AnnotationSerializer;
use Illuminate\Support\Facades\App;

class MapAnnotations extends Controller
{
    public function __invoke()
    {
        $repository = App::make(AnnotationRepository::class);
        $annotations = $repository
            ->getBaseModel()
            ->newQuery()
            ->distinctByType()
            ->get()
            ->map(function ($annotation) {
                return $annotation->load(['types' => function ($query) use ($annotation) {
                    $query->where('id', $annotation->annotation_type_id);
                }]);
            });
        $serializer = new AnnotationSerializer();
        return $serializer->serialize($annotations);
    }
}
