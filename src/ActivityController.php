<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use AnthonyEdmonds\GovukLaravel\Helpers\GovukPage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use NetworkRailBusinessSystems\ActivityLog\ActivityCollection;

class ActivityController extends Controller
{
    public function actions(User $user): View
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

    public function user(User $user): View
    {
        return $this->view($user, 'name', 'admin.users.show');
    }

    /**
     * @param  User  $subject
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
