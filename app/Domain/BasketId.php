<?php namespace App\Domain;

use Ramsey\Uuid\Uuid;
use Singularity\Foundation\Contracts\Identifier;

class BasketId implements Identifier
{
    /**
     * @var Uuid
     */
    protected $uuid;

    /**
     * @param string $uuid
     */
    protected function __construct($uuid)
    {
        $this->uuid = Uuid::fromString($uuid);
    }

    /**
     * Generate a new value object instance.
     *
     * @return static
     */
    public static function generate()
    {
        return new static(Uuid::uuid4());
    }

    /**
     * Create a new value object instance.
     *
     * @param  string $uuid
     * @return static
     */
    public static function make($uuid)
    {
        return new static(Uuid::fromString($uuid));
    }

    /**
     * Checks equality with another value object.
     *
     * @param  static $other
     * @return bool
     */
    public function equals($other)
    {
        return $this->isSameClass($other) && (string) $this == (string) $other;
    }

    /**
     * Determine whether an identifier has the same class as this.
     *
     * @param  $other
     * @return bool
     */
    protected function isSameClass($other)
    {
        return !is_null($other) && get_class($this) == get_class($other);
    }

    /**
     * Returns the value object as a string.
     *
     * @return string
     */
    public function toString()
    {
        return (string) $this->uuid->toString();
    }

    /**
     * Returns the value object as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
