package com.onemanpower;

import org.apache.http.HttpStatus;

import java.io.*;
import java.net.*;

public class HTTPClient {

    static final String USER_AGENT = "HiveClient/1.0";

    /**
     *
     * @param url
     * @param request
     * @return
     * @throws Exception
     */
    public static String get(String url, String request) throws Exception {
        URL obj = new URL(url);
        HttpURLConnection conn = (HttpURLConnection) obj.openConnection();

        conn.setDoOutput(false);

        conn.setRequestMethod("POST");

        conn.setRequestProperty("User-Agent", USER_AGENT);

        conn.setRequestProperty("Content-Type", "application/json");
        conn.setRequestProperty("Accept", "application/json");

        conn.connect();

        OutputStream os = conn.getOutputStream();
        BufferedWriter writer = new BufferedWriter(new OutputStreamWriter(os, "UTF-8"));
        writer.write(request);
        writer.close();
        os.close();

        int status = conn.getResponseCode();

        BufferedReader in;

        if(status >= HttpStatus.SC_BAD_REQUEST)
            in = new BufferedReader(new InputStreamReader(conn.getErrorStream()));
        else
            in = new BufferedReader(new InputStreamReader(conn.getInputStream()));

        String inputLine;
        StringBuffer response = new StringBuffer();

        while ((inputLine = in.readLine()) != null) {
            response.append(inputLine);
        }
        in.close();

        return response.toString();
    }
}
