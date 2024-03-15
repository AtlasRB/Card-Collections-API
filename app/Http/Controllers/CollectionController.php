<?php

namespace App\Http\Controllers;

use App\Models\cards;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function getAllCards()
    {
        $cards = cards::all();

        return response()->json([
            'message' => 'Presenting all cards',
            'data' => $cards,
        ]);
    }

    public function getSingleCard(int $id)
    {
        $card = cards::find($id);

        return response()->json([
            'message' => 'Presenting single card',
            'data' => $card,
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

        $card = cards::find($id);
        $card->name = $request->name;
        $card->number = $request->number;
        $card->type = $request->type;
        $card->collected = $request->collected;

        if (! $card->save()) {
            return response()->json([
                'message' => 'Could not update card',
            ]);
        }

        return response()->json([
            'message' => 'Card updated',
            'data' => $card,
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

        $card = new cards();
        $card->name = $request->name;
        $card->number = $request->number;
        $card->type = $request->type;
        $card->collected = $request->collected;

        if (! $card->save()) {
            return response()->json([
                'message' => 'Could not add card',
            ]);
        }

        return response()->json([
            'message' => 'Card added',
            'data' => $card,
        ], status: 201);
    }

    public function deleteCard(int $id)
    {
        $card = cards::find($id);
        $card->delete();

        return response()->json([
            'message' => 'Card deleted',
        ]);
    }
}
