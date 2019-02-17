package com.ibrahim.myapplication.Service;

import android.app.Notification;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Build;
import android.support.v4.app.NotificationCompat;
import android.util.Log;

import com.google.firebase.FirebaseApp;
import com.google.firebase.iid.FirebaseInstanceIdService;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;
import com.ibrahim.myapplication.MainActivity;
import com.ibrahim.myapplication.R;

import java.util.Map;
import java.util.Random;

public class MyFireBase extends FirebaseMessagingService
{

    @Override
    public void onCreate()
    {
        super.onCreate();


    }

    @Override
    public void onMessageReceived(RemoteMessage remoteMessage)
    {
        super.onMessageReceived(remoteMessage);
        Log.d("Receved", "Receved");
        if (remoteMessage.getData().isEmpty())
        {
            showNotification(remoteMessage.getNotification().getTitle(), remoteMessage.getNotification().getBody());

        }
        else
        {
            showNotification(remoteMessage.getData());
        }
    }


    private void showNotification(Map<String, String> data)
    {
        Log.d("Receved", "Receved");
        String title = data.get("title").toString();
        String body = data.get("body").toString();
        Log.i("DATA: ", title);
        Log.i("DATA: ", body);
        NotificationManager notificationManager = (NotificationManager)getSystemService(Context.NOTIFICATION_SERVICE);
        String NOTIFICATION_CHANNEL_ID = "com.ibrahim.test";

        if(Build.VERSION.SDK_INT >= Build.VERSION_CODES.O)
        {
            NotificationChannel notificationChannel = new NotificationChannel(NOTIFICATION_CHANNEL_ID, "Notification",
                    NotificationManager.IMPORTANCE_DEFAULT);

            notificationChannel.setDescription("EDMT Channel");
            notificationChannel.enableLights(true);
            notificationChannel.setLightColor(Color.BLUE);
            notificationChannel.setVibrationPattern(new long[]{0,1000,500,1000});
            notificationManager.createNotificationChannel(notificationChannel);
        }

        NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this, NOTIFICATION_CHANNEL_ID);
        notificationBuilder.setAutoCancel(true).setDefaults(Notification.DEFAULT_ALL)
                .setWhen(System.currentTimeMillis())
                .setSmallIcon(R.drawable.ic_notification).
                setContentTitle(title)
                .setContentText(body)
                .setContentInfo("info");

        notificationManager.notify(new Random().nextInt(), notificationBuilder.build());

        PendingIntent contentIntent = PendingIntent.getActivity(this, 0,
                new Intent(this, MainActivity.class), 0);


    }


    private void showNotification(String title, String body)
    {
        Log.d("Receved", "Receved");
        NotificationManager notificationManager = (NotificationManager)getSystemService(Context.NOTIFICATION_SERVICE);
        String NOTIFICATION_CHANNEL_ID = "com.ibrahim.test";

        if(Build.VERSION.SDK_INT >= Build.VERSION_CODES.O)
        {
            NotificationChannel notificationChannel = new NotificationChannel(NOTIFICATION_CHANNEL_ID, "Notification",
                    NotificationManager.IMPORTANCE_DEFAULT);

            notificationChannel.setDescription("EDMT Channel");
            notificationChannel.enableLights(true);
            notificationChannel.setLightColor(Color.BLUE);
            notificationChannel.setVibrationPattern(new long[]{0,1000,500,1000});
            notificationManager.createNotificationChannel(notificationChannel);
        }

        NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this, NOTIFICATION_CHANNEL_ID);
        notificationBuilder.setAutoCancel(true).setDefaults(Notification.DEFAULT_ALL)
        .setWhen(System.currentTimeMillis())
        .setSmallIcon(R.mipmap.rounded).
        setContentTitle(title)
        .setContentText(body)
        .setContentInfo("info");

        notificationManager.notify(new Random().nextInt(), notificationBuilder.build());

        PendingIntent contentIntent = PendingIntent.getActivity(this, 0,
                new Intent(this, MainActivity.class), 0);

    }


    @Override
    public void onNewToken(String s)
    {
        super.onNewToken(s);
        Log.d("TOKENFIREBASE", s);
    }
}
