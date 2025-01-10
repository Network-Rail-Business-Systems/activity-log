<?php

namespace NetworkRailBusinessSystems\ActivityLog\Traits;

use AnthonyEdmonds\GovukLaravel\Helpers\GovukPage;
use Illuminate\Contracts\View\View;
use NetworkRailBusinessSystems\ActivityLog\ActivityCollection;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioned;

/**
 * @mixin Actioned
 */
trait HasActivities
{
    public function viewActivities(): View
    {
        $activities = ActivityCollection::make(
            $this
                ->activities()
                ->with(['causer', 'subject'])
                ->paginate(10),
        );

        return GovukPage::custom("Activity log of {$this->label()}", 'govuk-activity-log::activity', [])
            ->setBack($this->backRoute())
            ->with('activities', $activities->toArray(request()))
            ->with('pagination', $activities->resource->toArray())
            ->with('showSubject', false)
            ->with('subject', $this);
    }
}
