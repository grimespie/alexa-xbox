alexa-xbox
=========================

This is an application for use with Amazon Alexa in order to turn your Xbox on using your Amazon Echo or Echo Dot.

Install
-----

When setting up an Alexa app, you can specify a secure URL where these files should be placed. The only file you will need to edit is the index.php file.

``` php
$Alexa->setApplicationID("amzn1.ask.skill.12345678-1234-1234-1234-123456789123");  // Set the application ID for your skill here
$Alexa->setApplicationName("Xbox On");  // Change this to whatever you are calling your app

$Xbox->setIPAddress("123.456.654.321");  // Set the public IP address of your Xbox here
$Xbox->setXboxLiveID("ABCD1234ABCD1234");  // Set the Xbox live ID here
```

IP Address & Xbox Live device ID
-----

For the app to connect to your Xbox, 3 things are required:

1. Your router must forward port 5050 to your Xbox.
2. As your Xbox will be turned on remotely, the public IP address for your Xbox is needed.
3. The Xbox Live device ID. On your Xbox: All settings > System > Console info & updates.
 
Testing
-----

It's probably a good idea to test the connection first, between the server that runs the app and your Xbox. To do this, you can simply comment out the $Alexa->auth() statement as requests from Alexa will only ever pass this check.

How it works
-----

The first thing the app does, is check that the Xbox is not already running by pinging it. If it isn't, then it will attempt to send a magic packet to the Xbox. It will wait 1 second before pinging again to see if the packet was received and the Xbox turned on. 3 attempts will be performed at turning the Xbox on. After the 3rd failed attempt, it will generate data for Alexa asking you to try again (It's worth noting that turning the Xbox on this way immediatly after you have turned it off doesn't always work. There is normally around 10-30 seconds of cool time before it will accept the packet).