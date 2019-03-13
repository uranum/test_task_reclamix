<?php

namespace App;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

class Service implements ServiceInterface
{
    const TIME_FORMAT = 'Y-m-d H:i:s';
    const TIME_ENDING = '23:59:59';
    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';
    const STATUS_COMPLETE_REMOVED = 'removed';

    private $id = 1;
    private $user_id = 1;
    private $codename = 'test_service';
    private $status = self::STATUS_ACTIVE;
    private $create_at;
    private $ending_at;


    public static function create(array $attributes)
    {
        $service = new static();

        if (isset($attributes['id'])) {
            $service->id = $attributes['id'];
        }

        if (isset($attributes['user_id'])) {
            $service->user_id = $attributes['user_id'];
        }

        if (isset($attributes['codename'])) {
            $service->codename = $attributes['codename'];
        }

        if (isset($attributes['status'])) {
            $service->status = $attributes['status'];
        }

        if (isset($attributes['ending_at'])) {
            $service->ending_at = $attributes['ending_at'];
        }

        $service->create_at = new DateTime('now', new DateTimeZone(config('timezone')));

        $service->validate();

        return $service;
    }

    private function validate()
    {
        if (null === $this->ending_at) {
            throw new InvalidArgumentException("Param 'ending_at' is required!");
        }

        if ( ! $this->ending_at instanceof DateTime) {
            throw new InvalidArgumentException("Param 'ending_at' must be an instance of a DateTime!");
        }

        $format = $this->ending_at->format('H:i:s');
        if (self::TIME_ENDING !== $format) {
            throw new InvalidArgumentException("Время `ending_at` всегда должно быть '".self::TIME_ENDING."'!");
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return mixed
     */
    public function getCodename()
    {
        return $this->codename;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }

    /**
     * @return mixed
     */
    public function getEndingAt()
    {
        return $this->ending_at;
    }
}