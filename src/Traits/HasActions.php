<?php

namespace NetworkRailBusinessSystems\ActivityLog\Traits;

use AnthonyEdmonds\GovukLaravel\Helpers\GovukPage;
use Illuminate\Contracts\View\View;
use NetworkRailBusinessSystems\ActivityLog\ActivityCollection;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioner;

/**
 * @mixin Actioner
 */
trait HasActions
{
    public function viewActions(): View
    {
        $actions = ActivityCollection::make(
            $this
                ->actions()
                ->with(['causer', 'subject'])
                ->paginate(10),
        )->showSubject();

        return GovukPage::custom("Activities performed by {$this->label()}", 'activity', [])
            ->setBack($this->backRoute())
            ->with('activities', $actions->toArray(request()))
            ->with('pagination', $actions->resource->toArray())
            ->with('showSubject', true)
            ->with('subject', $this);
    }
}
