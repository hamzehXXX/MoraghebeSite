<?php
class GooglesheetTest
{
    function __construct()
    {
    }

    function runSpreadSheet($select) {
//        echo 'ljkljklj';
        require __DIR__ . '/vendor/autoload.php';
//        echo 'a;sldkjf';

//var_dump(openssl_get_cert_locations()); die;
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(__DIR__ . '/quickstart-1607764632156-97e2080c18bf.json');
// disable ssl for local environment
//        $guzzleClient = new \GuzzleHttp\Client(['verify' => false]);
//        $client->setHttpClient($guzzleClient);
        $service = new Google_Service_Sheets($client);
        $spreadsheetId= "1PLR3p6f3uD1nn8WBN92RjXS-0XHv7AxinwfjxkopJJs";

// READ DATA FROM SPREADSHEET
        $range = "tarketark!D2:E2";
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        if (empty($values)) {
            return "داده ای یافت نشد. \n";
        } else {
            // $mask = "%10s %-10s %s\n";
            // foreach ($values as $row) {
            //     echo sprintf($mask, $row[2], $row[1], $row[0]);
            // }
        }
        
        
///// ADD A NEW ROW AT THE BOTTOM
        $newRowRange = "tarketark";
        $newRowVal = [
            ["This", "is", "a", "new", "row"]
        ];
        $newSelect = [
            $select
        ];

        $body = new Google_Service_Sheets_ValueRange([
            'values' => $newSelect
        ]);
        $params = [
            'valueInputOption' => 'RAW'
        ];
        $insert = [
            "insertDataOption" => "INSERT_ROWS"
        ];
//        var_dump('lsdjf444 454 45 l;kasjf');
//        echo '<br/>';
//        var_dump($select);
        $insertResult = $service->spreadsheets_values->append(
            $spreadsheetId,
            $newRowRange,
            $body,
            $params,
            $insert
        );
    }
}