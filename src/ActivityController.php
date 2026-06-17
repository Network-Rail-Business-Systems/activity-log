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

/** @property class-string<Actioner|Actioned|Model> $class */
class ActivityController extends Controller
{
    use AuthorizesRequests;

    public ?string $class = null;

    public ?string $id = null;

    public function __construct()
    {
        $this->class = request()
            ->route()
            ?->parameter('class');

        $this->id = request()
            ->route()
            ?->parameter('id');
    }

    public function actions(): View
    {
        return $this
            ->loadSubject()
            ->viewActions();
    }

    public function activities(): View
    {
        return $this
            ->loadSubject()
            ->viewActivities();
    }

    protected function loadSubject(): Actioned|Actioner
    {
        $model = new $this->class();

        /** @var Actioned|Actioner $subject */
        $subject = $model::query()
            ->where($model->getRouteKeyName(), '=', $this->id)
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
