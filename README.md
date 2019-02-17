# JINKS
![Jinks Logo](branding/logo(Dark).png)

JINKS is an in-development technology that leverages an android app, PHP, cURL, and and a REST API in order to verify the user is in a stable state of mind.

## How it works
JINKS works by awaiting a transaction and sending the verification to the Android app. When the user opens the notification from JINKS, they have to repeat aloud a statement that is psuedorandomlly generated. Their spoken audio is recorded and then transmitted back to our server and passed through a emotion recognization API. The data that comes out of the API is then used to approve or deny the user's purchase.

## Extensability
This could be improved with an addition of voice authentication to verify the user is an authorized user. Additonally the same technology and backend could be used with voice assistants such as Google Assistant or Amazon Alexa. 
