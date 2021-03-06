<?php

namespace Morebec\Orkestra\EventSourcing\Snapshot;

use Morebec\Orkestra\EventSourcing\EventStore\EventSequenceNumber;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamId;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamVersion;
use Morebec\Orkestra\EventSourcing\Modeling\AbstractEventSourcedAggregateRoot;
use Morebec\Orkestra\Normalization\ObjectNormalizerInterface;

/**
 * Service responsible for taking a snapshot of an {@link AbstractEventSourcedAggregateRoot}.
 */
class Snapshotter
{
    private ObjectNormalizerInterface $objectNormalizer;

    public function __construct(ObjectNormalizerInterface $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    public function takeSnapshot(
        EventStreamId $streamId,
        EventStreamVersion $version,
        EventSequenceNumber $eventSequenceNumber,
        AbstractEventSourcedAggregateRoot $aggregateRoot
    ): Snapshot {
        $data = $this->objectNormalizer->normalize($aggregateRoot);
        unset($data['domainEvents'], $data['version']);

        return new Snapshot($streamId, $version, $eventSequenceNumber, $data);
    }
}
