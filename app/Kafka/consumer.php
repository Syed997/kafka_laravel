<?php

$conf = new \RdKafka\Conf();


$conf->set('bootstrap.servers', 'localhost:9092');
$conf->set('group.id', 'B');
$conf->set('auto.offset.reset', 'earliest');

$consumer = new \RdKafka\KafkaConsumer($conf);

$consumer->subscribe(['test']);

while (true) {
    $message = $consumer->consume(120*1000);

    var_dump("message: ".$message->payload. ", partition: ".$message->partition) ;
    sleep(3);

}

