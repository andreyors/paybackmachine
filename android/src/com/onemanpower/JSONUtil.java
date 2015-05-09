package com.onemanpower;

import org.json.JSONException;
import org.json.JSONObject;

/**
 *
 */
public class JSONUtil {

    /**
     *
     * @param clientId client id
     * @param mac mac address
     * @param minor minor of beacon
     * @param major major of beacon
     * @return String
     * @throws JSONException
     */
    public static String pack(String clientId, String mac, int minor, int major) throws JSONException {
        JSONObject resultJson = new JSONObject();

        resultJson.put("client_id", clientId);
        resultJson.put("mac", mac);
        resultJson.put("minor", String.valueOf(minor));
        resultJson.put("major", String.valueOf(major));

        return resultJson.toString();
    }

    /**
     *
     * @param json JSON-formatted string
     * @return JSONObject
     * @throws JSONException
     */
    public static JSONObject parse(String json) throws JSONException {
        JSONObject jsonResponse = new JSONObject(json);
        JSONObject result = jsonResponse.getJSONObject("response");

        return result;
    }
}


