<?php

require_once "DotMaui.php";

$APIKEY = "YOUR_API_KEY";

$client = new DotMaui\Client($APIKEY);

/**
 * List all paste
 *
 * @param DotMaui\Client $client
 */
function ListingPastes($client) {
    $pastes = $client->ListingPastes(0, 500, true);

    echo "<pre>";
    print_r($pastes);
    echo "</pre>";
}

/**
 * Create new paste.
 *
 * @param DotMaui\Client $client
 */
function CreatePaste($client) {

    $paste = new DotMaui\Paste();

    $paste->Author = "DotMaui";
    $paste->Expiration = "10min";
    $paste->Language = "html";
    $paste->Text = "<p class='awesome'>An html string</p>";
    $paste->Title = "A simple test";

    $response = $client->CreatePaste($paste);

    echo "<pre>";
    print_r($response);
    echo "</pre>";

}

/**
 * Delete paste.
 *
 * @param DotMaui\Client $client
 * @param $uid
 */
function DeletePaste($client, $uid) {

    $response = $client->DeletePaste($uid);

    echo "<pre>";
    print_r($response);
    echo "</pre>";

}

/**
 * @param $client
 * @param $uid
 */
function GetCompletePaste($client, $uid) {

    $response = $client->GetCompletePaste($uid);

    echo "<pre>";
    print_r($response);
    echo "</pre>";

}

//ListingPastes($client);
//CreatePaste($client);
//DeletePaste($client, "XXXXXXXX");
GetCompletePaste($client, "XXXXXXXX");

