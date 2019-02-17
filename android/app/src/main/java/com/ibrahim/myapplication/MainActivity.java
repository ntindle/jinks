package com.ibrahim.myapplication;

import android.Manifest;
import android.app.ProgressDialog;
import android.content.pm.ActivityInfo;
import android.content.pm.PackageManager;
import android.media.MediaPlayer;
import android.media.MediaRecorder;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Environment;
import android.os.storage.StorageManager;
import android.speech.tts.TextToSpeech;
import android.support.annotation.NonNull;
import android.support.v4.app.ActivityCompat;
import android.support.v4.app.NotificationCompat;
import android.support.v4.content.ContextCompat;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.method.ScrollingMovementMethod;
import android.util.Base64;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.FirebaseApp;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.storage.FirebaseStorage;
import com.google.firebase.storage.StorageReference;
import com.google.firebase.storage.UploadTask;

import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.SocketTimeoutException;
import java.net.URL;
import java.util.Locale;
import java.util.UUID;

import javax.net.ssl.HttpsURLConnection;

import static com.google.firebase.iid.FirebaseInstanceId.getInstance;

public class MainActivity extends AppCompatActivity
{

    Button recordBtn, stopRecordBtn, repeatBtn;
    MediaRecorder mediaRecorder;
    MediaPlayer mediaPlayer;
    String outputFile = null;
    File file;
    final int REQUEST_PREMISSION_CODE = 1000;
    private TextToSpeech myTTS;


    public class ATask extends AsyncTask<String, Void, String>
    {

        @Override
        protected String doInBackground(String... urls) {

            try {
                URL url = new URL("https://api.jinks.ml/challenge-result");
                HttpsURLConnection client = null;

                try {
                    client = (HttpsURLConnection) url.openConnection();
                    client.setRequestMethod("POST");
                    //client.setRequestProperty("Key", "Value");
                    client.setDoOutput(true);
                    //client.connect();

                    DataOutputStream dStream = new DataOutputStream(client.getOutputStream());
                    file = new File(outputFile);
                    byte[] f = new byte[(int) file.length()];
                    DataInputStream dataInputStream = new DataInputStream(new FileInputStream(file));
                    dataInputStream.readFully(f);
                    dataInputStream.close();
                    String base64 = Base64.encodeToString(f, Base64.DEFAULT);

                    dStream.writeBytes(base64);
                    dStream.flush();
                    dStream.close();

                    InputStream in = client.getInputStream();

                    InputStreamReader isw = new InputStreamReader(in);

                    int data = isw.read();
                    String res = "";
                    while (data != -1) {
                        char current = (char) data;
                        res += current;
                        data = isw.read();
                    }

                    Log.i("Result", res);


                } finally {
                    if(client != null) // Make sure the connection is not null.
                        client.disconnect();
                }

            }catch(MalformedURLException error) {
                error.getMessage();
            } catch(SocketTimeoutException error) {
                error.getMessage();
            } catch (IOException error) {
                error.getMessage();
            }


            return "test2";

        }
    }


    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        this.setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        ActionBar actionBar  = getSupportActionBar();
        actionBar.setDisplayHomeAsUpEnabled(true);

        repeatBtn = findViewById(R.id.repeatBtn);

        TextView outText = (TextView) findViewById(R.id.textView2);
        outText.setMovementMethod(new ScrollingMovementMethod());

        recordBtn = findViewById(R.id.recordBtn);
        stopRecordBtn = findViewById(R.id.stopRecordBtn);

        String TOKEN = FirebaseInstanceId.getInstance().getToken();
        Log.e("TOKEN", TOKEN);

        initializeTextToSpeech();


        if (checkPermissionFromDevice())
        {
            stopRecordBtn.setEnabled(false);
            outputFile = Environment.getExternalStorageDirectory().getAbsolutePath()
                    + "/myrec.3gp";
            mediaRecorder = new MediaRecorder();
            mediaRecorder.setAudioSource(MediaRecorder.AudioSource.MIC);
            mediaRecorder.setOutputFormat(MediaRecorder.OutputFormat.AMR_NB);
            mediaRecorder.setAudioEncoder(MediaRecorder.AudioEncoder.DEFAULT);
            mediaRecorder.setOutputFile(outputFile);


        }
        else
        {
            requestPermissions();
        }

    }

    private void initializeTextToSpeech()
    {
        myTTS = new TextToSpeech(this, new TextToSpeech.OnInitListener()
        {
            @Override
            public void onInit(int i)
            {
                if(myTTS.getEngines().size() == 0)
                {
                    Toast.makeText(MainActivity.this, "No TTS engine on your device", Toast.LENGTH_SHORT).show();
                    finish();
                }
                else
                {
                    myTTS.setLanguage(Locale.US);
                    speak("Please read the message that's on the website");
                }
            }
        });
    }

    private void speak(String s)
    {
        if(Build.VERSION.SDK_INT >= 21)
        {
            myTTS.speak(s, TextToSpeech.QUEUE_FLUSH, null, null);
        }
        else
        {
            myTTS.speak(s, TextToSpeech.QUEUE_FLUSH, null);
        }
    }


    public void start(View view)
    {
        try
        {
            mediaRecorder.prepare();;
            mediaRecorder.start();
        }
         catch (IOException e)
        {
            e.printStackTrace();
        }

        recordBtn.setEnabled(false);
        stopRecordBtn.setEnabled(true);
        Toast.makeText(this, "Recording started", Toast.LENGTH_SHORT).show();
    }
    public void stop(View view)
    {
        mediaRecorder.stop();
        mediaRecorder.release();
        mediaRecorder = null;
        stopRecordBtn.setEnabled(false);


        ATask task = new ATask();
        String result = null;
        try {
            result = task.execute("https://api.jinks.ml/challenge-result").get();
        } catch (Exception e) {
            e.getMessage();
        }



        Log.i("Result",result);
        Toast.makeText(this, "Thank you...", Toast.LENGTH_SHORT).show();
        finish();

    }




    public void repeatBtn(View view)
    {
        speak("Please read the message that's on the website");
    }



    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults)
    {
        switch (requestCode)
        {
            case REQUEST_PREMISSION_CODE:
            {
                if((grantResults.length > 0) && grantResults[0] == PackageManager.PERMISSION_GRANTED)
                {
                    Toast.makeText(this, "Permission Granted", Toast.LENGTH_SHORT).show();
                }
                else
                {
                    Toast.makeText(this, "Permission Denied", Toast.LENGTH_SHORT).show();
                }

            }
                break;
        }
    }

    private void requestPermissions()
    {
        ActivityCompat.requestPermissions(this, new String[]
        {
            Manifest.permission.WRITE_EXTERNAL_STORAGE, Manifest.permission.RECORD_AUDIO

        }, REQUEST_PREMISSION_CODE);
    }

    private boolean checkPermissionFromDevice()
    {
        int write_external_storage_result = ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE);
        int record_audio_result = ContextCompat.checkSelfPermission(this, Manifest.permission.RECORD_AUDIO);
        return write_external_storage_result== PackageManager.PERMISSION_GRANTED && record_audio_result==PackageManager.PERMISSION_GRANTED;
    }

    @Override
    protected void onPause()
    {
        super.onPause();
        myTTS.shutdown();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                // app icon in action bar clicked; goto parent activity.
                this.finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

}
