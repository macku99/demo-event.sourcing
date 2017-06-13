<?php namespace App\Repositories;

use App\Domain\Basket;
use App\Domain\BasketRepository;
use Singularity\Foundation\EventStore\Persistence\AggregateRootEventSourcingRepository;

class BasketEventSourcingRepository extends AggregateRootEventSourcingRepository implements BasketRepository
{
    protected static $aggregateClass = Basket::class;
}
