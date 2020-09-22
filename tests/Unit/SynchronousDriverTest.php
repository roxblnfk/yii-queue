<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Queue\Tests\Unit;

use Yiisoft\Yii\Queue\Enum\JobStatus;
use Yiisoft\Yii\Queue\Message\Message;
use Yiisoft\Yii\Queue\Queue;
use Yiisoft\Yii\Queue\Tests\App\SimplePayload;
use Yiisoft\Yii\Queue\Tests\TestCase;

final class SynchronousDriverTest extends TestCase
{
    protected function needsRealDriver(): bool
    {
        return true;
    }

    public function testNonIntegerId(): void
    {
        $queue = $this->getQueue();
        $job = new SimplePayload();
        $id = $queue->push($job);
        $wrongId = "$id ";
        self::assertEquals(JobStatus::waiting(), $queue->status($wrongId));
    }

    public function testIdSetting(): void
    {
        $message = new Message('simple', [], []);
        $driver = $this->getDriver();
        $driver->setQueue($this->createMock(Queue::class));

        $ids = [];
        $ids[] = $driver->push($message);
        $ids[] = $driver->push($message);
        $ids[] = $driver->push($message);

        self::assertCount(3, array_unique($ids));
    }
}
