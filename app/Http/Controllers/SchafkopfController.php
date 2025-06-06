<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sk\Card;

class SchafkopfController extends Controller
{

    private $cards = [
            1 => "Eichel Ass",
            2 => "Gras Ass",
            3 => "Herz Ass",
            4 => "Schelln Ass",
            5 => "Eichel 10",
            6 => "Gras 10",
            7 => "Herz 10",
            8 => "Schelln 10",
            9 => "Eichel Ober",
            10 => "Gras Ober",
            11 => "Herz Ober",
            12 => "Schelln Ober",
            13 => "Eichel Unter",
            14 => "Gras Unter",
            15 => "Herz Unter",
            16 => "Schelln Unter",
            17 => "Eichel König",
            18 => "Gras König",
            19 => "Herz König",
            20 => "Schelln König",
            21 => "Eichel 9",
            22 => "Gras 9",
            23 => "Herz 9",
            24 => "Schelln 9",
            25 => "Eichel 8",
            26 => "Gras 8",
            27 => "Herz 8",
            28 => "Schelln 8",
            29 => "Eichel 7",
            30 => "Gras 7",
            31 => "Herz 7",
            32 => "Schelln 7",
        ];

    public function index(){

        //$this->mixAndDealOutCards($game_id, $player_ids);
        $player_ids = ['8','12','24','83'];
        $game_id = 1;
        $cards = Card::where('game_id',1)->get();


        return view('sk.game', compact('cards'));
    }

    public function initGame(){

    }

    public function mixAndDealOutCards($game_id, $player_ids){
        // Erstelle ein Array von Karten mit card_id
        $cardsList = [];
        foreach ($this->cards as $id => $karte) {
            $cardList[] = [
                'card_id' => $id,
                'name' => $karte,
            ];
        }

        // Mische die Karten
        shuffle($cardList);

        // Verteile 8 Karten an jeden der 4 Spieler
        $playerCount = 4;
        $cardsPerPlayer = 8;

        // Lösche vorhandene Karten für dieses Spiel
        Card::where('game_id', $game_id)->delete();

        // Verteile die Karten und speichere sie in der Datenbank
        for ($player = 0; $player < $playerCount; $player++) {
            for ($i = 0; $i < $cardsPerPlayer; $i++) {
                $player_id = $player_ids[$player];
                $cardIndex = ($player * $cardsPerPlayer + $i);
                $card = $cardList[$cardIndex];

                Card::create([
                    'game_id' => $game_id,
                    'player_id' => $player_id,
                    'card_id' => $card['card_id'],
                    'played' => false,
                ]);
            }
        }
    }
}
