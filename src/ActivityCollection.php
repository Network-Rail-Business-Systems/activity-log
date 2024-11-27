<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Http\Resources\Json\ResourceCollection;
use NetworkRailBusinessSystems\ActivityLog\ActivityResource;

class ActivityCollection extends ResourceCollection
{
    public $collects = ActivityResource::class;

    public bool $showSubject = false;

    public function toArray($request)
    {
        $request->showSubject = $this->showSubject;

        return parent::toArray($request);
    }

    public function showSubject(): self
    {
        $this->showSubject = true;

        return $this;
    }
}
