package com.jaalee.BeaconDemo;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.StrictMode;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;

/**
 * http://www.jaalee.com/
 *
 * @author JAALEE, Inc.
 *         We have been trying to provide better services and products! Jaalee Beacon makes
 *         life more simple and cheerful! If you are interested in our product,
 *         please contact us in following ways. We will provide the best service wholeheartedly for you!
 *         <p>
 *         Buy Jaalee Beacon: sales@jaalee.com
 *         <p>
 *         Technical Support: dev@jaalee.com
 */
public class AllDemosActivity extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.all_demos);

        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder()
                    .permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }

        com.jaalee.sdk.utils.L.enableDebugLogging(true);

        findViewById(R.id.distance_demo_button).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(AllDemosActivity.this, ListBeaconsActivity.class);
                intent.putExtra(ListBeaconsActivity.EXTRAS_TARGET_ACTIVITY, DistanceBeaconActivity.class.getName());

                startActivity(intent);
            }
        });
        findViewById(R.id.notify_demo_button).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(AllDemosActivity.this, ListBeaconsActivity.class);
                intent.putExtra(ListBeaconsActivity.EXTRAS_TARGET_ACTIVITY, NotifyDemoActivity.class.getName());
                startActivity(intent);
            }
        });

    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.

        MenuItem mun1 = menu.add(0, -1, 0, "More");
        {
            mun1.setIcon(android.R.drawable.ic_menu_search);
            mun1.setShowAsAction(MenuItem.SHOW_AS_ACTION_IF_ROOM);
        }

        menu.addSubMenu(1, Menu.FIRST, 0, "Hive");
        menu.addSubMenu(1, Menu.FIRST + 10, 1, "Settings");
        menu.addSubMenu(1, Menu.FIRST + 20, 2, "Logout");

        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case -1:
                Uri uri0 = Uri.parse("https://paybackmachine-andreyors.c9.io/");
                startActivity(new Intent(Intent.ACTION_VIEW, uri0));
                break;
            case Menu.FIRST:
                Uri uri = Uri.parse("https://paybackmachine-andreyors.c9.io/");
                startActivity(new Intent(Intent.ACTION_VIEW, uri));
                break;
            case Menu.FIRST + 10:
                Uri url1 = Uri.parse("https://www.jaalee.com/store");
                startActivity(new Intent(Intent.ACTION_VIEW, url1));
                break;
            case Menu.FIRST + 20:
                Uri url2 = Uri.parse("http://www.jaalee.com/contact_en.html");
                startActivity(new Intent(Intent.ACTION_VIEW, url2));
                break;
        }
        return super.onOptionsItemSelected(item);
    }
}
