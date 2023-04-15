<?php
function getRepeatCustomers($log) {
    $customerLog = [];
    $repeatCustomers = [];

    $lines = explode("\n", $log);

    foreach ($lines as $line) {
      
        $fields = explode(" ", $line);

        if (count($fields) < 3) {
            continue; 
        }

        $timestamp = strtotime($fields[0] . " " . $fields[1] . " " . $fields[2]);
        $customerId = $fields[3];

        if (!isset($customerLog[$customerId])) {
            $customerLog[$customerId] = [];
        }
        $customerLog[$customerId][] = $timestamp;

        $numConsecutiveDays = 0;
        $lastTimestamp = null;
        foreach ($customerLog[$customerId] as $visitTimestamp) {
            if ($lastTimestamp !== null && ($visitTimestamp - $lastTimestamp) <= 86400) {
                
                $numConsecutiveDays++;
            } else {
                
                $numConsecutiveDays = 1;
            }
            if ($numConsecutiveDays >= 3 && !in_array($customerId, $repeatCustomers)) {
                $repeatCustomers[] = $customerId;
                break;
            }
            $lastTimestamp = $visitTimestamp;
        }
    }

    return $repeatCustomers;
}

$log = '08-Jun-2012 1:00 AM 4ABCDEFGHI
09-Jun-2012 1:00 AM 1ABCDEFGHI
09-Jun-2012 9:23 AM 3ABCDEFGHI
10-Jun-2012 1:00 AM 2ABCDEFGHI
10-Jun-2012 2:03 AM 2ABCDEFGHI
10-Jun-2012 1:00 AM 1ABCDEFGHI
10-Jun-2012 7:23 AM 3ABCDEFGHI
10-Jun-2012 9:23 AM 3ABCDEFGHI
11-Jun-2012 1:00 AM 1ABCDEFGHI
11-Jun-2012 2:12 AM 2ABCDEFGHI
11-Jun-2012 8:23 AM 3ABCDEFGHI
12-Jun-2012 10:21 PM 1ABCDEFGHI';


$repeatCustomers = getRepeatCustomers($log);

echo "Repeat customers: " . implode(", ", $repeatCustomers);


?>