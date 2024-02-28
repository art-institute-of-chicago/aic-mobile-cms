<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Api\GalleryRepository;

class TourTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'tour_stops',
        'translations',
    ];

    public function transform(TwillModelContract $tour): array
    {
        $repository = App::make(GalleryRepository::class);
        $gallery = null;
        if ($tour->gallery_id) {
            $gallery = Cache::remember('galleryRepo-one-' . $tour->gallery_id, 300, function () use ($repository, $tour) {
                return $repository->getBaseModel()->newQuery()->getSingle($tour->gallery_id);
            });
        }
        $thumbnail = $tour->image('upload', 'thumbnail');
        $image = $tour->image('upload');

        return [
            'title' => $tour->title,
            'nid' => (string) $tour->id,
            'location' => $gallery?->latlon,
            'latitude' => $gallery?->latitude,
            'longitude' => $gallery?->longitude,
            'floor' => $gallery?->floor,
            'image_url' => $image,
            'thumbnail_full_path' => $thumbnail,
            'large_image_full_path' => $image,
            'selector_number' => (string) $tour->selector?->number,
            'description' => $tour->description,
            'intro' => $tour->intro,
            'tour_duration' => $tour->duration_in_minutes,
            'tour_audio' => $tour->selector?->locales,
            'category' => null, // Legacy from Drupal
            'weight' => $tour->position,
        ];
    }

    protected function includeTourStops($tour)
    {
        $stops = $tour->stops->filter(function ($stop) {
            return $stop->selector?->object?->is_on_view;
        });
        return $this->collection($stops, new StopTransformer());
    }

    protected function includeTranslations($tour)
    {
        $translations = $tour->translations()
            ->where('active', true)
            ->whereNot('locale', config('app.locale'))
            ->get()
            ->map(function ($translation) use ($tour) {
                $translation->duration = trans_choice(
                    "{1} :minutes minute|[2,*] :minutes minutes",
                    $tour->duration,
                    ['minutes' => $tour->duration],
                    locale: $translation->locale,
                );
                return $translation;
            });
        return $this->collection($translations, new TourTranslationTransformer());
    }
}
