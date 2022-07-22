<?php

use App\Jobs\TelegramNotify;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('coin', function () {
    $coin =  Http::get('https://api.coingecko.com/api/v3/coins/cryptocars/')->object();
    $coin2 =  Http::get('https://api.coingecko.com/api/v3/coins/binancecoin/')->object();

    $symbol = $coin->symbol;
    $name = $coin->name;
    $brl = $coin->market_data->current_price->brl;
    $bnb = $coin->market_data->current_price->bnb;
    $bnbToBrl = $coin2->market_data->current_price->brl;

    $convert = $bnbToBrl * $bnb;

    $lastUpdated = date('d-m-Y H:i', strtotime($coin->last_updated));

    $moeda = [
        'CCAR->BRL' => [$name, $symbol, $brl, $lastUpdated],
        'CCAR->BNB' => [$name, $symbol, $bnb, $lastUpdated],
        'CCAR->BNB->BRL' => $convert
    ];

    $token = '5500446766:AAFXa0NBgpW7Du2_Q_kYepwszMqv2QjGV10';
    $chatId = '-600495284';
    $message = "Token = {$name}\nSimbolo = {$symbol}\nCCAR -> BRL = {$brl}\nCCAR -> BNB = {$bnb}\nCCAR -> BNB -> BRL = {$convert}\nAtualizado em = {$lastUpdated}";
    $sendMessage = Http::get("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chatId}&text={$message}");


    return response()->json($moeda);


    return $coin->market_data->current_price->brl;
});

Route::get('/', function () {

    TelegramNotify::dispatch();
    return view('welcome');
});
