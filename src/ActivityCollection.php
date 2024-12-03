<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ActivityCollection extends ResourceCollection
{
    public $collects = ActivityResource::class;

    public bool $showSubject = false;

    public function toArray(Request $request): array
    {
        $request->query->set(ActivityResource::REQUEST_KEY, $this->showSubject);

        return parent::toArray($request);
    }

    public function showSubject(): self
    {
        $this->showSubject = true;

        return $this;
    }
}
