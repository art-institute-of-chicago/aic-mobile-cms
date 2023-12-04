<?php

namespace Tests\Unit;

use A17\Twill\Models\Model;
use App\Models\Transformers\CustomIncludes;
use League\Fractal\TransformerAbstract;
use PHPUnit\Framework\TestCase;

class CustomIncludesTest extends TestCase
{
    public function test_include_function_is_called(): void
    {
        $model = new FakeModel();
        $mock = $this->getMockBuilder(FakeTransformer::class)
            ->onlyMethods(['includeTestInclude', 'includeNotConfigured'])
            ->getMock();
        $mock->expects($this->once())
            ->method('includeTestInclude')
            ->with($this->equalTo($model));
        $mock->expects($this->never())
            ->method('includeNotConfigured');
        $mock->withCustomIncludes($model, array());
    }

    public function test_collection_is_called(): void
    {
        $model = new FakeModel();
        $childTransformer = new FakeChildTransformer();
        $mock = $this->getMockBuilder(FakeTransformer::class)
            ->onlyMethods(['collection'])
            ->getMock();
        $mock->expects($this->once())
            ->method('collection')
            ->with($this->equalTo($model->data), $this->equalTo($childTransformer));
        $mock->withCustomIncludes($model, array());
    }

    public function test_data_is_included(): void
    {
        $data = ['test' => 'data'];
        $transformer = new FakeTransformer();
        $result = $transformer->withCustomIncludes(new FakeModel(), $data);
        $this->assertArrayHasKey('test', $result);
        $this->assertArrayHasKey('test_include', $result);
    }
}

// phpcs:disable PSR1.Classes.ClassDeclaration.MultipleClasses,PSR2.Classes.ClassDeclaration.OpenBraceNewLine,Squiz
class FakeModel extends Model {
    public $data = ['fake', 'model'];
}

class FakeChildTransformer extends TransformerAbstract {
    public function transform($data)
    {
        return $data;
    }
}

class FakeSerializer {
    public function collection($resourceKey, $data)
    {
        return [$resourceKey => $data];
    }
}

class FakeTransformer {
    use CustomIncludes;

    public $customIncludes = [
        'test_include' => FakeSerializer::class,
    ];

    public function includeTestInclude($model)
    {
        return $this->collection($model->data, new FakeChildTransformer());
    }

    public function includeNotConfigured($model) {}
}
// phpcs:enable
