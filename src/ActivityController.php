<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioned;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioner;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityController extends Controller
{
    use AuthorizesRequests;

    /** @param class-string<Actioner> $class */
    public function actions(int|string $id, string $class): View
    {
        return $this
            ->loadSubject($id, $class)
            ->viewActions();
    }

    /** @param class-string<Actioned> $class */
    public function activities(int|string $id, string $class): View
    {
        return $this
            ->loadSubject($id, $class)
            ->viewActivities();
    }

    /** @param class-string<Actioned|Actioner|Model> $class */
    protected function loadSubject(int|string $id, string $class): Actioned|Actioner
    {
        $model = new $class();

        /** @var Actioned|Actioner $subject */
        $subject = $model::query()
            ->where($model->getRouteKeyName(), '=', $id)
            ->when(
                in_array(SoftDeletes::class, class_uses($model)) === true,
                function (Builder $query) {
                    /** @phpstan-ignore-next-line */
                    $query->withTrashed();
                },
            )
            ->firstOrFail();

        $permission = $subject->permission();
        if ($permission !== false) {
            $this->authorize($permission, $subject);
        }

        return $subject;
    }
}
