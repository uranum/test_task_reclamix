<?php

namespace App;

use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

/**
 * Class ServiceHelper
 *
 * @property ServiceInterface $service
 */
class ServiceHelper
{
    private $service;
    private $now;

    const FINISHED_PERIOD = 10;
    const NEED_TO_DELETE_PERIOD = 10;

    public function __construct(ServiceInterface $service)
    {
        $this->service = $service;
        $this->now     = new DateTime('now', new DateTimeZone(config('timezone')));
    }

    /**
     * Расчет количества дней срока действия услуги
     *  *будем считать от времени создания услуги
     *
     *
     *  На мой взгляд удобнее вместо аргументов испльзовать свойства объекта service внутри помощника, но по заданию делаю с аргументами
     *
     * @param $endingDate
     *
     * @return string
     * @throws Exception
     */
    public function getEstimatedDays(DateTime $endingDate)
    {
        $createAt = $this->service->getCreateAt();

        if ($createAt < $endingDate) {
            $diff = $endingDate->diff($createAt);

            return $diff->d; // return integer (count days)
//            return $diff->format('%a days to ending of the service.'); // return formatted text
        } else {
            throw new Exception("No estimated days. The service is gone!");
        }
    }

    /**
     * Определение статуса активности услуги
     *      Срок действия услуги меньше текущей даты
     *
     * @param DateTime $endingDate
     *
     * @return bool
     */
    public function isActive(DateTime $endingDate)
    {
        return $this->now < $endingDate;
    }

    /**
     * Определение статуса начала завершения услуги
     *      Осталось 10 или менее дней срока действия услуги
     *
     * @param DateTime $endingDate
     *
     * @return bool
     * @throws Exception
     */
    public function isServiceInFinishStatus(DateTime $endingDate)
    {
        $createAt = $this->service->getCreateAt();

        if ($createAt < $endingDate) {
            $diff = $endingDate->diff($createAt);

            return $diff->d <= self::FINISHED_PERIOD;
        } else {
            throw new Exception("No estimated days. The service is gone!");
        }
    }

    /**
     * Расчет количества дней удаления услуги
     *      Количество дней после завершения срока действия услуги.
     *      Не более 10 дней
     *
     * @param DateTime $endingDate
     *
     * @return string
     * @throws Exception
     */
    public function getCountDaysAfterService(DateTime $endingDate)
    {
        if ($this->now > $endingDate) {
            $diff = $this->now->diff($endingDate);

            return $diff->d;
        } else {
            throw new Exception("The service is active yet!");
        }
    }

    /**
     * Определение статуса удаления услуги
     *
     * @param DateTime $endingDate
     *
     * @return bool
     * @throws Exception
     */
    public function isPreDeleteStatus(DateTime $endingDate)
    {
        return $this->getCountDaysAfterService($endingDate) < self::NEED_TO_DELETE_PERIOD;
    }

    /**
     * Расчет даты окончательного удаления услуги
     *
     * @param DateTime $endingDate
     *
     * @return string
     * @throws Exception
     */
    public function getDateFinalRemoving(DateTime $endingDate)
    {
        $dateForRemove = $endingDate->add(new DateInterval("P" . self::NEED_TO_DELETE_PERIOD . "D"));

        return $dateForRemove->format(Service::TIME_FORMAT);
    }

    /**
     * Определение статуса окончательного удаления услуги
     *
     * @param DateTime $endingDate
     *
     * @return bool
     * @throws Exception
     */
    public function isNeedFinalRemoving(DateTime $endingDate)
    {
        return $this->getCountDaysAfterService($endingDate) >= self::NEED_TO_DELETE_PERIOD;
    }
}