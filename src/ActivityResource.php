<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin Activity
 */
class ActivityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'event' => $this->event,
            'label' => $this->label($request),
            'summary' => $this->summary(),
            'details' => $this->details(),
        ];
    }

    protected function label(Request $request): string
    {
        $label = ucfirst($this->description);

        if ($request->showSubject === true) { //TODO
            switch ($this->event) {
                case 'notification':
                    break;

                default:
                    $name = $this->subject->name ?? '';
                    $type = Str::headline(
                        substr($this->subject_type, strrpos($this->subject_type, '\\') + 1),
                    );

                    return "$type $label $name";
            }
        }

        return $label;
    }

    protected function details(): array
    {
        $details = [];

        if ($this->propertyExists('attributes') === true) {
            $this->attributeDetails($details);
        }

        if ($this->propertyExists('added') === true) {
            $this->changeDetails($details, 'added');
        }

        if ($this->propertyExists('removed') === true) {
            $this->changeDetails($details, 'removed');
        }

        return $details;
    }

    protected function propertyExists(string $property): bool
    {
        return isset($this->properties[$property]) === true
            && empty($this->properties[$property]) === false;
    }

    protected function changeDetails(array &$details, string $property): void
    {
        $details[] = ucfirst($property) . ': ' . implode(', ', $this->properties[$property]);
    }

    protected function attributeDetails(array &$details): void
    {
        foreach ($this->properties['attributes'] as $key => $value) {
            $detail = ucfirst(explode('.', $key, 2)[0]) . ' set to ' . $this->formatValue($value);

            if (isset($this->properties['old'][$key]) === true) {
                $detail .=
                    ' (changed from ' . $this->formatValue($this->properties['old'][$key]) . ')';
            }

            $details[] = $detail;
        }
    }

    protected function summary(): string
    {
        $causer = $this->causer?->name ?? 'System';

        return $this->created_at->format('d/m/Y H:i') . " by {$causer}";
    }

    protected function formatValue(mixed $value): string
    {
        if ($value === null) {
            return 'none';
        }

        if (is_bool($value) === true) {
            return $value === true ? 'On' : 'Off';
        }

        if (is_array($value) === true) {
            return $this->formatArray($value);
        }

        if (
            strpos($value, 'T') === 10
            && strpos($value, 'Z') === 26
        ) {
            return Carbon::parse($value)->format('d/m/Y H:i');
        }

        return $value;
    }

    protected function formatArray(array $values): string
    {
        $output = [];

        foreach ($values as $key => $value) {
            $line = is_string($key) === true
                ? "$key: "
                : '';

            $line .= is_array($value) === true
                ? $this->formatArray($value)
                : $value;

            $output[] = $line;
        }

        return implode(', ', $output);
    }
}
