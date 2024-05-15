<?php

$conf = new \RdKafka\Conf();

$conf->set('bootstrap.servers', 'localhost:9092');

$producer = new \RdKafka\Producer($conf);

while (true) {
    $message = readline("Write a message: ");

    $topic = $producer->newTopic('test');
    $topic->produce(0, 0, $message);
    $producer->flush(5000);
}
// for($i=0; $i<6;$i++){
//     $message = "hello";

//     $topic = $producer->newTopic('sample_topic');
//     $topic->produce($i, 0, $message);
//     $producer->flush(5000);
// }
