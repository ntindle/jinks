# JINKS
![Jinks Logo](branding/logo(Dark).png)

JINKS is an in-development technology that leverages an android app, PHP, cURL, and a REST API in order to verify if the user is in a stable state of mind.

## Problems it solves 
Since online banking has been introduced, it has always been very difficult for banks to correctly determine which transactions are legitimate and which are fraudulent. Customers hate to be inconvenienced with multiple layers of authentication, while banks often have to consider whether largely inconveniencing a customer is worth the hassle for extra account security. In fraudulent transactions over the internet, banks often only check accounts for fraudulent transactions when an account is deemed as engaging in risky behavior across several transactions. Unfortunately, unwanted purchases occur more often than can be detected from banks and account holders alike. 

Bank account holders often struggle with authenticating their own transactions due to impulse buys and irrational and reckless behavior. With our emotional stability analytical technology, a transaction will decline when buyers are not in the right state of mind. Customers can now be certain their transactions are valid due to complete conscientiousness during purchases.

The same software without the emotion matching could be used to verify any risky purchases easily and quickly as well. 

## How it works
JINKS works by awaiting a transaction and sending the verification to the Android app. When the user opens the notification from JINKS, they have to repeat aloud a statement that is psuedorandomlly generated. Their spoken audio is recorded and then transmitted back to our server and passed through an emotion recognization API. The data that comes out of the API is then used to approve or deny the user's purchase.

## Expected Outcomes
If the user is in a normal range of moods (happy,tired, etc.), the mood will be confirmed, and the user’s transaction will be authenticated. If the user is in an irregular mood (sad, angry, etc.), the user’s transaction will be denied. If the user cannot pronounce the given sentence without slurring speech or stuttering, the purchase will be denied. This process will keep users from making irrational financial decisions while under the influence while quickly authenticating account activity as soon as it is pending on the account. Because the account it authenticated in seconds, this could eliminate the tedious process of account authentication without compromising security.

## Extensability
This could be improved with an addition of voice authentication to verify the user is an authorized user. Additonally the same technology and backend could be used with voice assistants such as Google Assistant or Amazon Alexa. 
