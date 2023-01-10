<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use App\Models\SensorUnit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;


class SensorDataController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'dev_id' => ['required'],
            'payload_fields' => ['required'],
            'metadata' => ['required'],
        ]);

        $unit = SensorUnit::whereAuthKey($request->get('dev_id'));

        if (!$unit->exists()) {
            return response(200);
        }


        $unit = $unit->first();
        $metadata = $request->get('metadata');
        $data = $request->get('payload_fields');
        $data = array_pop($data);#
        $size = count($data);
        $time = Carbon::parse($metadata['time']);
        $threshold = $unit->threshold;
        $trigger = false;

        SensorData::create(
            [
                'sensor_unit_id' => $unit->id,
                'type' => "Battery",
                'value' => $data[$size - 1],
                'measured_at' => $time
            ]
        );

        for ($i = 0; $i < $size - 1; $i++) {
            SensorData::create(
                [
                    'sensor_unit_id' => $unit->id,
                    'type' => $unit->type,
                    'value' => $data[$i],
                    'measured_at' => $time->subSeconds(150)
                ]
            );

            if ($threshold !== null) {
                if ($data[$i] >= $threshold) {
                    $trigger = true;
                }
            }
        }

        $receiver = (string)User::whereId($unit->user_id)->first()->email;


        if($trigger) {
            Mail::send(['text' => 'email.OverThresholdMail'], ["unit" => $unit->toArray()], function ($message) use ($receiver)
            {
                $message->to($receiver)
                    ->subject('Sensor over threshold');
                $message->from(config('mail.from.address'), 'SensorDash');
            });
        }

        return response(200);

    }

    public function test()
    {
        $arr = [
            "app_id" => "my-app-id",
            "dev_id" => "my-dev-id",
            "hardware_serial" => "0102030405060708", // In case of LoRaWAN: the DevEUI
            "port" => 1,                             // LoRaWAN FPort
            "counter" => 2,                          // LoRaWAN frame counter
            "is_retry" => false,                     // Is set to true if this message is a retry (you could also detect this from the counter)
            "confirmed" => false,                    // Is set to true if this message was a confirmed message
            "payload_raw" => "AQIDBA==",             // Base64 encoded payload=> [0x01, 0x02, 0x03, 0x04]
            "payload_fields" => [],
            "metadata" => [
                "time" => "1975-01-01T00:00:00Z",   // Time when the server received the message
                "frequency" => 868.1,                // Frequency at which the message was sent
                "modulation" => "LORA",              // Modulation that was used - LORA or FSK
                "data_rate" => "SF7BW125",           // Data rate that was used - if LORA modulation
                "bit_rate" => 50000,                 // Bit rate that was used - if FSK modulation
                "coding_rate" => "4/5",              // Coding rate that was used
                "gateways" => [
                    [
                        "gtw_id" => "ttn-herengracht-ams", // EUI of the gateway
                        "timestamp" => 12345,              // Timestamp when the gateway received the message
                        "time" => "1970-01-01T00:00:00Z",  // Time when the gateway received the message - left out when gateway does not have synchronized time
                        "channel" => 0,                    // Channel where the gateway received the message
                        "rssi" => -25,                     // Signal strength of the received message
                        "snr" => 5,                        // Signal to noise ratio of the received message
                        "rf_chain" => 0,                   // RF chain where the gateway received the message
                        "latitude" => 52.1234,             // Latitude of the gateway reported in its status updates
                        "longitude" => 6.1234,             // Longitude of the gateway
                        "altitude" => 6                    // Altitude of the gateway
                    ],
                    "latitude" => 52.2345,              // Latitude of the device
                    "longitude" => 6.2345,              // Longitude of the device
                    "altitude" => 2                     // Altitude of the device
                ],
                "downlink_url" => "https://integrations.thethingsnetwork.org/ttn-eu/api/v2/down/my-app-id/my-process-id?key=ttn-account-v2.secret"

            ]
        ];

        dd(json_encode($arr));
    }

}
