<?php

namespace App\Http\Controllers;

use App\Models\cards;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public $hidden = ['id', 'created_at', 'updated_at'];

    public function getAllCards(Request $request)
    {
        $cards = cards::all();
        $search = $request->search;

        if ($search) {
            return response()->json([
                'message' => 'Showing searched cards',
                'data' => cards::where('name', 'LIKE', "%$search%")->get()->makeHidden($this->hidden)
                ]);
        }
        return response()->json([
            'message' => 'Presenting all cards',
            'data' => $cards->makeHidden($this->hidden),
        ]);
    }

    public function getSingleCard(int $id)
    {
        $card = cards::find($id);

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
            'data' => $card->makeHidden($this->hidden),
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
