<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class TelegramNotify  //implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $coin =  Http::get('https://api.coingecko.com/api/v3/coins/cryptocars/')->object();

        $symbol = $coin->symbol;
        $name = $coin->name;
        $brl = $coin->market_data->current_price->brl;
        $variationBrl = $coin->market_data->price_change_percentage_24h_in_currency->brl;

        $lastUpdated = date('d-m-Y H:i', strtotime($coin->last_updated));

        $token = '5500446766:AAFXa0NBgpW7Du2_Q_kYepwszMqv2QjGV10';
        $chatId = '-600495284';
        $message = "{$name} - {$symbol} \nVariação BRL 24H: {$variationBrl}\nCCAR -> BRL = {$brl}\nAtualizado em = {$lastUpdated}";
        $sendMessage = Http::get("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chatId}&text={$message}");
    }
}
