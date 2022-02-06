<?php

exec('mode com3: baud=9600 data=8 stop=1 parity=n xon=on');

$fd = dio_open('com3:', O_RDWR);
$data = dio_read($fd, 4);

$outputObj = new stdClass();
$outputObj->v = $data;
$output = json_encode($outputObj);

echo $output;
?>
