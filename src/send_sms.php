<?php

// Set the phone number you want to send the SMS to
$phoneNumber = '09995316843';

// Set the message you want to send
$message = 'This is a test message';

// Open a serial port to the GSM modem
$serial = fopen('/dev/ttyUSB0', 'w+');

// Send the AT command to the modem
fwrite($serial, 'AT' . PHP_EOL);

// Wait for the modem to respond
sleep(1);

// Send the command to set the phone number
fwrite($serial, 'AT+CMGS="' . $phoneNumber . '"' . PHP_EOL);

// Wait for the modem to respond
sleep(1);

// Send the message
fwrite($serial, $message . chr(26));

// Close the serial port
fclose($serial);

echo 'SMS sent successfully!';

?>