<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit\Resources;

use NetworkRailBusinessSystems\ActivityLog\Activity;
use NetworkRailBusinessSystems\ActivityLog\ActivityResource;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use NetworkRailBusinessSystems\ActivityLog\Tests\TestCase;

class ActivityResourceTest extends TestCase
{
    protected Activity $activity;

    protected ActivityResource $resource;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testFormatsSubjectLabel(): void
    {
        $activity = activity()
            ->on($this->user)
            ->byAnonymous()
            ->log('my description');

        $this->assertEquals(
            "User My description {$this->user->name}",
            $this->makeResource($activity)['label'],
        );
    }

    public function testFormatsNotificationLabel(): void
    {
        $activity = activity()
            ->on($this->user)
            ->byAnonymous()
            ->event('notification')
            ->log('my description');

        $this->assertEquals(
            'My description',
            $this->makeResource($activity)['label'],
        );
    }

    public function testSkipsDetails(): void
    {
        $activity = activity()
            ->on($this->user)
            ->log('my description');

        $this->assertEquals(
            [],
            $this->makeResource($activity)['details'],
        );
    }

    public function testFormatsDetails(): void
    {
        $activity = activity()
            ->on($this->user)
            ->withProperties([
                'attributes' => [
                    'age' => 12,
                    'name' => 'New name',
                ],
                'old' => [
                    'name' => 'Old name',
                ],
            ])
            ->log('my description');

        $this->assertEquals(
            [
                'Age set to 12',
                'Name set to New name (changed from Old name)',
            ],
            $this->makeResource($activity)['details'],
        );
    }

    public function testFormatsValues(): void
    {
        $activity = activity()
            ->on($this->user)
            ->withProperties([
                'attributes' => [
                    'null' => null,
                    'true' => true,
                    'false' => false,
                    'array' => [
                        'one',
                        'two' => 'potato',
                        [
                            'three',
                        ],
                    ],
                    'string' => 'string',
                    'Carbon Thingy' => '2024-12-16T16:45:25Z',
                ],
            ])
            ->log('my description');

        $this->assertEquals(
            [
                'Null set to none',
                'True set to On',
                'False set to Off',
                'Array set to one, two: potato, three',
                'String set to string',
                "Carbon Thingy set to 16/12/2024 16:45",
            ],
            $this->makeResource($activity)['details'],
        );
    }

    public function testAddsDetails(): void
    {
        $activity = activity()
            ->on($this->user)
            ->withProperties([
                'added' => [
                    'Potato',
                ],
            ])
        ->log('my description');

        $this->assertEquals(
            ['Added: Potato'],
            $this->makeResource($activity)['details'],
        );
    }

    public function testRemovesDetails()
    {
        $activity = activity()
            ->on($this->user)
            ->withProperties([
                'removed' => [
                    'Potato 2',
                ],
            ])
            ->log('my description');

        $this->assertEquals(
            ['Removed: Potato 2'],
            $this->makeResource($activity)['details'],
        );
    }

    protected function makeResource(Activity $activity): array
    {
        $resource = new ActivityResource($activity);
        $request = request();
        $request->showSubject = true;

        return $resource->toArray($request);
    }
}
