<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioned;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioner;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityController extends Controller
{
    use AuthorizesRequests;

    /**
     * @param class-string<Actioner> $class
     */
    public function actions(int|string $id, string $class): View
    {
        return $this
            ->loadSubject($id, $class)
            ->viewActions();
    }

    /**
     * @param class-string<Actioned> $class
     */
    public function activities(int|string $id, string $class): View
    {
        return $this
            ->loadSubject($id, $class)
            ->viewActivities();
    }

    /**
     * @param class-string<Actioned>|class-string<Actioner> $class
     */
    protected function loadSubject(int|string $id, string $class): Actioned|Actioner
    {
        $subject = in_array(
            SoftDeletes::class,
            class_uses($class),
        ) === true
            ? $class::query()->withTrashed()->find($id)
            : $class::query()->find($id);

        $permission = $subject->permission();

        if ($permission !== false) {
            $this->authorize($permission, $subject);
        }

        return $subject;
    }
}
