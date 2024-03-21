<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public $hidden = ['created_at', 'updated_at'];

    public function getAllCards(Request $request)
    {
        $cards = Card::all();
        $search = $request->search;

        if ($search) {
            return response()->json([
                'message' => 'Showing searched card',
                'data' => Card::where('name', 'LIKE', "%$search%")->get()->makeHidden($this->hidden)
                ]);
        }
        return response()->json([
            'message' => 'Presenting all card',
            'data' => $cards->makeHidden($this->hidden),
        ]);
    }

    public function getSingleCard(int $id)
    {
        $card = Card::find($id);

        return response()->json([
            'message' => 'Presenting single card',
            'data' => $card->makeHidden($this->hidden),
        ]);
    }

    public function changeCard(int $id, Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|string',
            'number' => 'required|min:0|integer',
            'type' => 'required|max:50|string',
            'collected' => 'required|boolean',
        ]);

        $card = Card::find($id);

        if (! $card) {
            return response()->json([
                    'message' => 'Card not found'
            ], 404);
        }

        $card->name = $request->name;
        $card->number = $request->number;
        $card->type = $request->type;
        $card->collected = $request->collected;

        if (! $card->save()) {
            return response()->json([
                'message' => 'Could not update card',
            ], status: 500);
        }

        return response()->json([
            'message' => 'Card updated',
            'data' => $card->makeHidden($this->hidden),
        ]);
    }

    public function addCard(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|string',
            'number' => 'required|min:0|integer',
            'type' => 'required|max:50|string',
            'collected' => 'required|boolean',
        ]);

        $card = new Card();
        $card->name = $request->name;
        $card->number = $request->number;
        $card->type = $request->type;
        $card->collected = $request->collected;

        if (! $card->save()) {
            return response()->json([
                'message' => 'Could not add card',
            ], status: 500);
        }

        return response()->json([
            'message' => 'Card added',
            'data' => $card->makeHidden($this->hidden),
        ], status: 201);
    }

    public function deleteCard(int $id)
    {
        $card = Card::find($id);
        $card->delete();

        return response()->json([
            'message' => 'Card deleted',
        ]);
    }
}
