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
    public const string REQUEST_KEY = 'show_activity_log_subject';

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

        if ($request->showSubject === true) {
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

        foreach ($this->properties as $key => $values) {
            if (empty($values) === true) {
                continue;
            }

            match ($key) {
                'added',
                'removed' => $this->changeDetails($details, $key),
                'attributes' => $this->attributeDetails($details),
                'old' => null,
                default => $this->otherDetails($details, $key, $values),
            };
        }

        return $details;
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
                $detail .= ' (changed from ' . $this->formatValue($this->properties['old'][$key]) . ')';
            }

            $details[] = $detail;
        }
    }

    protected function otherDetails(array &$details, string $key, mixed $value): void
    {
        $key = ucfirst($key);

        $value = match (true) {
            is_array($value) => implode(', ', $value),
            is_object($value) => null,
            default => $value,
        };

        if (empty($value) === false) {
            $details[] = "$key set to \"$value\"";
        }
    }

    protected function summary(): string
    {
        $causer = $this->causer->name ?? 'System';

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
            str_contains($value, 'T') === true
            && str_contains($value, 'Z') === true
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
