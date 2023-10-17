<?php

namespace App\Http\Controllers\Behaviors;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\BaseFormField;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumn;

/**
 * Allows for the title column on the index table to be displayed with
 * formatting and the title on the edit page to be editable in a WYSIWYG field.
 */
trait HandlesTitleMarkup
{
    protected bool $hasTitleMarkup = false;

    /**
     * Toggle the title editor based on whether the title contains markup.
     */
    public function edit(TwillModelContract|int $id): mixed
    {
        return parent::edit($id)->with(['editableTitle' => !$this->hasTitleMarkup]);
    }

    protected function enableTitleMarkup(bool $hasTitleMarkup = true): void
    {
        $this->hasTitleMarkup = $hasTitleMarkup;
    }

    protected function getTitleColumn(): TableColumn
    {
        $titleField = $this->hasTitleMarkup ? $this->titleColumnKey . '_markup' : $this->titleColumnKey;
        return Text::make()
            ->field($titleField)
            ->title(ucfirst($this->titleColumnKey))
            ->sortable();
    }

    protected function getTitleField(): BaseFormField
    {
        if ($this->hasTitleMarkup) {
            $title = Wysiwyg::make()
                ->name($this->titleColumnKey . '_markup')
                ->label(ucfirst($this->titleColumnKey))
                ->required()
                ->toolbarOptions(['bold', 'italic'])
                ->allowSource();
        } else {
            $title = Input::make()
                ->name($this->titleColumnKey)
                ->required();
        }
        return $title;
    }
}
