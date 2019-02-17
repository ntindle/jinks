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
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.File;
import android.widget.*;
import com.google.firebase.FirebaseApp;
import com.google.firebase.iid.FirebaseInstanceIdService;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;
import com.ibrahim.myapplication.MainActivity;
import com.ibrahim.myapplication.R;

import java.io.FileWriter;
import java.io.IOException;
import java.util.Map;
import java.util.Random;

public class MyFireBase extends FirebaseMessagingService
{

    String id;
    String file1 = "myNewFile";
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
        //if (remoteMessage.getData().isEmpty())
        //{
        //    Log.d("SN","1");
            showNotification(remoteMessage.getNotification().getTitle(), remoteMessage.getNotification().getBody(), remoteMessage.getData());

        //}
        //else
        //{
         //   Log.d("SN","2");
         //   showNotification(remoteMessage.getData());
        //}

    }





    private void showNotification(Map<String, String> data)
    {

        for(String key: data.keySet())
        {
            Log.i("KeyAAAA: ", key+ " "+data.get(key));
        }

        Log.d("Receved", "Receved");
        String title = data.get("title").toString();
        String body = data.get("body").toString();
        String id = data.get("id").toString();

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
                .setSmallIcon(R.mipmap.rounded).
                setContentTitle(title)
                .setContentText(body)
                .setContentInfo("info");

        notificationManager.notify(new Random().nextInt(), notificationBuilder.build());

        try {
            FileOutputStream fOut = openFileOutput(file1,MODE_PRIVATE);
            fOut.write(id.getBytes());
            fOut.close();
            File filePath = new File(getFilesDir(),file1);
                    /*if (filePath.exists()){
                        filePath.delete();
                    }
                    filePath.createNewFile();*/
            Toast.makeText(getBaseContext(), "File Saved at " +filePath +"Contents " +id, Toast.LENGTH_LONG).show();
        }
        catch (Exception e){
            e.printStackTrace();
            Log.i("TAAAG", "HELLO");
        }


        PendingIntent contentIntent = PendingIntent.getActivity(this, 0,
          new Intent(this, MainActivity.class), 0);

    }


    private void showNotification(String title, String body, Map<String, String> data)
    {

        for(String key: data.keySet())
        {
            Log.d("KeyAAAA: ", key+ " "+data.get(key));
        }

        Log.d("Received", "Received");
        Log.d("datalog: ", data.toString());
        Log.d("datalog2: ", ""+data.size());
        id = data.get("id");
        //Log.d("dataid: ", id2);

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

        /*
        try {
            FileOutputStream fOut = openFileOutput(file1,MODE_PRIVATE);
            fOut.write(id.getBytes());
            fOut.close();
            File filePath = new File(getFilesDir(),file1);
                    if (filePath.exists()){
                        filePath.delete();
                    }
                    filePath.createNewFile();
            Toast.makeText(getBaseContext(), "File Saved at " +filePath +"Contents " +id, Toast.LENGTH_LONG).show();
        }
        catch (Exception e){
            e.printStackTrace();
            Log.i("TAAAG", "HELLO");
        }
        */

        Intent i = new Intent(this, MainActivity.class);
        i.putExtra("id",id);
        i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK |Intent.FLAG_ACTIVITY_CLEAR_TASK);

        startActivity(i);
        //PendingIntent contentIntent = PendingIntent.getActivity(this, 0, i, 0);

    }


    @Override
    public void onNewToken(String s)
    {
        super.onNewToken(s);
        Log.d("TOKENFIREBASE", s);
    }
}
