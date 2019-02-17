# JINKS
![Jinks Logo](branding/logo(Dark).png)

JINKS is an in-development technology that leverages an android app, PHP, cURL, and and a REST API in order to verify the user is in a stable state of mind.

## What problem does it solve? 
 The difficulty for banks to correctly determine which transactions are legitimate and which are fraudulent have been a serious issue since online banking has been introduced. Banks often have to consider whether inconveniencing a customer with two- factor authentication is worth the hassle for extra security in fraudulent transactions over the internet. Banks often only check accounts for fraudulent transactions when an account is deemed as engaging in risky behavior across several transactions. Unfortunately, unwanted purchases occur more often than can be detected from banks and account holders alike. 

## How it works
JINKS works by awaiting a transaction and sending the verification to the Android app. When the user opens the notification from JINKS, they have to repeat aloud a statement that is psuedorandomlly generated. Their spoken audio is recorded and then transmitted back to our server and passed through a emotion recognization API. The data that comes out of the API is then used to approve or deny the user's purchase.

## Extensability
This could be improved with an addition of voice authentication to verify the user is an authorized user. Additonally the same technology and backend could be used with voice assistants such as Google Assistant or Amazon Alexa. 
