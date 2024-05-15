<?php

$conf = new RdKafka\Conf();
$conf->set('group.id', 'B');
$conf->set('metadata.broker.list', 'localhost:9092');
$conf->set('auto.offset.reset', 'earliest');

$consumer = new RdKafka\KafkaConsumer($conf);
$consumer->subscribe(['test']);

$specific_date = '2024-05-15'; // Specify the desired date
$messageCount = 0;

$start_time = time();

while (true) {
    $message = $consumer->consume(1000); // Poll for messages every 1 second
    // if ($message->err) {
    //     if ($message->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
    //         continue;
    //     } elseif ($message->err === RD_KAFKA_RESP_ERR__TIMED_OUT) {
    //         echo "Timed out waiting for message.\n";
    //         continue;
    //     } else {
    //         echo "Error: {$message->errstr()} ({$message->err})\n";
    //         break;
    //     }
    // }

    $timestamp = $message->timestamp;
    if ($timestamp !== -1) {
        $message_date = date('Y-m-d', $timestamp / 1000);
        if ($message_date === $specific_date) {
            $messageCount++;
        }
    } else {
        echo "Timestamp not available for message.\n";
    }

    // Exit loop after 5 seconds (adjust as needed)
    if (time() - $start_time > 5) {
        break;
    }
}

echo "Total messages produced on $specific_date: $messageCount\n";

$consumer->close();
