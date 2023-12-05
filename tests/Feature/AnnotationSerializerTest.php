<?php

namespace Tests\Feature;

use App\Models\Annotation;
use App\Models\AnnotationCategory;
use App\Models\AnnotationType;
use App\Models\Floor;
use App\Repositories\Serializers\AnnotationSerializer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnotationSerializerTest extends TestCase
{
    use RefreshDatabase;

    public function test_serialize(): void
    {
        $types = AnnotationType::factory()
            ->count(10)
            ->for(AnnotationCategory::factory(), 'category')
            ->create();
        $annotations = Annotation::factory()
            ->count(10)
            ->for(Floor::factory())
            ->create();
        foreach ($annotations as $index => $annotation) {
            $annotation->types()->attach($types[$index]);
        }
        $annotations->each->refresh();
        $serializer = new AnnotationSerializer();
        $serialized = $serializer->serialize($annotations);

        $this->assertArrayHasKey('map_annontations', $serialized, 'The key is intentionally misspelled for backwards compatibility');
        foreach ($serialized['map_annontations'] as $id => $annotation) {
            $this->assertStringContainsString(':', $id);
            $this->assertArrayHasKey('title', $annotation);
            $this->assertArrayHasKey('status', $annotation);
            $this->assertArrayHasKey('nid', $annotation);
            $this->assertArrayHasKey('type', $annotation);
            $this->assertArrayHasKey('translations', $annotation);
            $this->assertArrayHasKey('location', $annotation);
            $this->assertArrayHasKey('latitude', $annotation);
            $this->assertArrayHasKey('longitude', $annotation);
            $this->assertArrayHasKey('floor', $annotation);
            $this->assertArrayHasKey('description', $annotation);
            $this->assertArrayHasKey('label', $annotation);
            $this->assertArrayHasKey('annotation_type', $annotation);
            $this->assertArrayHasKey('text_type', $annotation);
            $this->assertArrayHasKey('amenity_type', $annotation);
            $this->assertArrayHasKey('image_filename', $annotation);
            $this->assertArrayHasKey('image_url', $annotation);
            $this->assertArrayHasKey('image_filemime', $annotation);
            $this->assertArrayHasKey('image_filesize', $annotation);
            $this->assertArrayHasKey('image_width', $annotation);
            $this->assertArrayHasKey('image_height', $annotation);
        }
    }
}
