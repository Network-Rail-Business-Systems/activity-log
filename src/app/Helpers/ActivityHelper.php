<?php

namespace NetworkRailBusinessSystems\ActivityLog\app\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Contracts\Activity;

class ActivityHelper
{
    public static function logNotification(
        Model $subject,
        string $notification,
        string|array|Collection $to,
        string|array|Collection $cc = [],
    ): Activity {
        $attributes = [
            'to' => self::formatRecipients($to),
        ];

        if (empty($cc) === false) {
            $attributes['cc'] = self::formatRecipients($cc);
        }

        return activity()
            ->on($subject)
            ->event('notification')
            ->withProperties([
                'attributes' => $attributes,
            ])
            ->log("$notification notification sent");
    }

    public static function logState(
        Model $subject,
        string $newState,
        string $oldState = 'none',
    ): Activity {
        return activity()
            ->on($subject)
            ->event('state')
            ->withProperties([
                'attributes' => [
                    'state' => $newState,
                ],
                'old' => [
                    'state' => $oldState,
                ],
            ])
            ->log("State changed to $newState");
    }

    public static function logRoleChange(Model $subject, string $roleName, string $action): Activity
    {
        return activity()
            ->on($subject)
            ->event('role')
            ->log("$roleName Role $action");
    }

    public static function formatRecipients(string|array|Collection $recipients): string
    {
        if (empty($recipients) === true) {
            return 'none';
        }

        if (is_string($recipients) === true) {
            return $recipients;
        }

        $list = [];

        if (is_array($recipients) === true) {
            foreach ($recipients as $email => $name) {
                $list[] = is_array($name) === true
                    ? self::formatListItem($name['name'], $name['address'])
                    : self::formatListItem($name, $email);
            }
        } elseif ($recipients instanceof Collection) {
            foreach ($recipients as $recipient) {
                $list[] =
                    is_string($recipient) === true
                        ? $recipient
                        : self::formatListItem($recipient->name, $recipient->email);
            }
        }

        return implode(', ', $list);
    }

    protected static function formatListItem(?string $name, string $address): string
    {
        return $name !== null
            ? "$name ($address)"
            : $address;
    }
}
