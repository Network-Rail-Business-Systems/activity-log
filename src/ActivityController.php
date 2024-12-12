<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioned;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioner;

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
//        dd($class);
//        dd($class::find($id));

        $subject = $class::find($id);

        $permission = $subject->permission();

//        Setting permission to false, will not check -> There is no permission in a package
//        after adding to application, set permission to true/manage
        if ($permission !== false) {
            $this->authorize($permission, $subject);
        }

        return $subject;
    }
}
