<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use AnthonyEdmonds\GovukLaravel\Helpers\GovukPage;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class ActivityController extends Controller //TODO
{
    public function actions(Model $user): View //TODO change typehint to interface - Actioner, Actioned, root macro on service provider
    {
        $this->authorize('manage', $user);

        $actions = ActivityCollection::make(
            $user
                ->actions()
                ->with(['causer', 'subject'])
                ->paginate(10),
        )->showSubject();

        return GovukPage::custom("Activities performed by {$user->name}", 'activity', [])
            ->setBack(route('admin.users.show', $user))
            ->with('activities', $actions->toArray(request()))
            ->with('pagination', $actions->resource->toArray())
            ->with('showSubject', true)
            ->with('subject', $user);
    }

    public function user(Model $user): View
    {
        return $this->view($user, 'name', 'admin.users.show');
    }

    /**
     * @param  Model  $subject
     */
    protected function view(Model $subject, string $labelKey, string $back): View
    {
        $this->authorize('manage', $subject);

        $activities = ActivityCollection::make(
            $subject
                ->activities()
                ->with(['causer', 'subject'])
                ->paginate(10),
        );

        return GovukPage::custom("Activity log of {$subject->$labelKey}", 'activity', [])
            ->setBack(route($back, $subject))
            ->with('activities', $activities->toArray(request()))
            ->with('pagination', $activities->resource->toArray())
            ->with('showSubject', false)
            ->with('subject', $subject);
    }
}
