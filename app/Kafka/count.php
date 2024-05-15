<?php

$conf = new RdKafka\Conf();
$conf->set('metadata.broker.list', 'localhost:9092');
$conf->set('group.id', 'my_consumer_group');
$consumer = new RdKafka\KafkaConsumer($conf);

$topic = 'test';
$specific_date = '2024-05-16';

$groups = shell_exec("/home/zim/Documents/Code/kafka/bin/kafka-consumer-groups.sh --bootstrap-server localhost:9092 --list");

$totalMessages = 0;
$consumedMessagesByGroup = [];

$groupList = explode("\n", trim($groups));
foreach ($groupList as $group) {
    $output = shell_exec("/home/zim/Documents/Code/kafka/bin/kafka-consumer-groups.sh --bootstrap-server localhost:9092 --describe --group $group");
    preg_match_all("/$topic\s+(\d+)\s+(\d+)\s+(\d+)/", $output, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        if ($match[1] == 0) {
            $offsetLag = intval($match[2]);
            $totalMessages = intval($match[3]);
            $consumedMessagesByGroup[$group] = $offsetLag;
            // $totalMessages += $offsetLag;
        }
    }
    // echo $offsetLag;
}


$remainingMessages = $totalMessages - $totalMessages;

echo "Total messages produced on $specific_date: $totalMessages\n";
foreach ($consumedMessagesByGroup as $group => $consumedMessages) {
    // echo "Total messages consumed by group $group on $specific_date: $consumedMessages\n";
    echo "Total messages consumed by group $group on: $consumedMessages\n";
}

$consumer->close();
?>
