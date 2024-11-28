<?php

namespace NetworkRailBusinessSystems\ActivityLog\app\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ActivityCollection extends ResourceCollection
{
    public $collects = ActivityResource::class;

    public bool $showSubject = false;

    public function toArray($request)
    {
        $request->showSubject = $this->showSubject; //TODO

        return parent::toArray($request);
    }

    public function showSubject(): self
    {
        $this->showSubject = true;

        return $this;
    }
}
