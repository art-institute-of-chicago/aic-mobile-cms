<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * The data in Drupal legacy sections is no longer used, but for backwards
 * compatibility, the section keys are included in the output.
 */
class LegacySectionController extends Controller
{
    public function __invoke()
    {
        return [str(class_basename(get_class($this)))->snake()->toString() => []];
    }
}
