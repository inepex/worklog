<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL);
require_once 'classes/PhpConsole.php';
require_once 'classes/Notification.php';
require_once 'classes/Company.php';
PhpConsole::start();
require_once '../worklog-config.php';
require_once 'classes/User.php';
require_once 'classes/AssociatedUser.php';
require_once 'classes/Category.php';
require_once 'classes/AssociatedCategory.php';
require_once 'classes/Project.php';
require_once 'classes/ProjectPlan.php';
require_once 'classes/ProjectPlanEntry.php';
require_once 'classes/Log.php';
require_once 'classes/ProjectStatus.php';
require_once 'classes/WorkPlace.php';
require_once 'include/notifications.php';
require_once 'classes/Tools.php';

$text = "Ahhoz, hogy ezt a hírt a helyén kezeljük, tudni kell, hogy bár http://asdfís.hu az üvegszálas kábelekben a fény az, ami az információátvitelt lehetővé teszi, az információ sebessége meg sem közelíti a fény vákuumban mért sebességét. Míg az utóbbi nagyjából 300 000 kilométer per másodperc, a manapság széles körben alkalmazott, a légüres térnél jóval sűrűbb üvegszálas kábelekben leadott fényimpulzusok csak 200 000 kilométert tesznek meg másodpercenként.
http://assdfasdfasdfdfís.ddd
A brit kutatók úgy gyorsították fel a kábelben száguldó fény sebességét, hogy kivették az üveget az üvegszálból. Az így létrehozott, belül üreges, de a fény irányát továbbra is megtartó kábel belsejében azonban még mindig túl nagy a főleg hosszú távon bekövetkező jelveszteség ahhoz, hogy a távközlési vállalatok egymás lábára taposva rohanjanak lecserélni az óceánokban most használt megoldásokat. A 3,5 dB/km jelveszteséggel dolgozó új kábel amúgy 37 párhuzamos csatornán egyszerre adva éri el a másodpercenkénti 1,48 terabites adatátviteli sebességet, és ezzel főleg a rövid távú összeköttetés oldható meg. A sebesség például egy-egy több száz gépet alkalmazó adatközpontban jöhet jól az adatokat tároló és feldolgozó egységek összekötésére.
http://asasdfasdfasdfasdfasdfdfís.adsf
A kutatók most azon dolgoznak, hogy a hosszabb távú kapcsolatoknál szükség";

echo Tools::identify_link($text);
?>