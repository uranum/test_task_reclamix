<?php
include "../vendor/autoload.php";

use App\Service;
use App\ServiceHelper;


try {
    $service = Service::create([
        'ending_at' => new DateTime('2019-03-15 23:59:59', new DateTimeZone(config('timezone'))),
    ]);

    $helper = new ServiceHelper($service);

    echo "<h3>Дата окончания действия услуги: {$service->getEndingAt()->format(Service::TIME_FORMAT)}</h3>";
    echo "<h3></h3>";

    // На мой взгляд удобнее вместо аргументов испльзовать свойства объекта service внутри помощника, но по заданию делаю с аргументами
    echo "Расчет количества дней срока действия услуги:  ";
    echo $helper->getEstimatedDays($service->getEndingAt());
    echo "<br>";
    echo "<br>";
    echo "Определение статуса активности услуги:  ";
//    var_dump($helper->isActive($service->getEndingAt()));
    echo "<br>";
    echo "<br>";
    echo "Определение статуса начала завершения услуги:  ";
//    var_dump($helper->isServiceInFinishStatus($service->getEndingAt())); // для проверки раскомментировать и дату окончания выставить в будущее +10 дней
    echo "<br>";
    echo "<br>";
    echo "Расчет количества дней удаления услуги:  ";
//    var_dump($helper->getCountDaysAfterService($service->getEndingAt())); // для проверки раскомментировать и дату окончания выставить в прошлое
    echo "<br>";
    echo "<br>";
    echo "Определение статуса удаления услуги:  ";
//    var_dump($helper->isPreDeleteStatus($service->getEndingAt())); // для проверки раскомментировать и дату окончания выставить в прошлое -9 дней
    echo "<br>";
    echo "<br>";
    echo "Расчет даты окончательного удаления услуги:  ";
    echo $helper->getDateFinalRemoving($service->getEndingAt());
    echo "<br>";
    echo "<br>";
    echo "Определение статуса окончательного удаления услуги:  ";
//    var_dump($helper->isNeedFinalRemoving($service->getEndingAt())); // для проверки раскомментировать и дату окончания выставить в прошлое -10 дней

} catch (\Exception $e) {
    echo $e->getMessage();
}

/*
echo "<pre>";
var_dump($service);
echo "</pre>";
*/
