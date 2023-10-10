<?php

namespace App\Models\Behaviors;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasTitleMarkup
{
    public function __construct()
    {
        $this->appends += ['title_markup'];
    }

    public function title(): Attribute
    {
        return Attribute::make(
            get: fn ($title) => strip_tags($title),
            set: fn ($title) => $this->attributes['title'] = $title,
        );
    }

    public function titleMarkup(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['title'],
            set: fn ($title) => ['title' => $title],
        );
    }
}
