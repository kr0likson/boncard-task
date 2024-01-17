<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Validators\CardValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = Card::paginate(request('perPage', 10));
        return response()->json($cards);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = (new CardValidator())->validate($request);
            if (!$validated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data'
                ], 422);
            }
            $card = Card::factory()->make($request->all());
            $card->save();
            return response()->json($card, 201);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving card.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card): JsonResponse
    {
        try {
            $validated = (new CardValidator())->validate($request);
            if (!$validated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $card->update($request->all());
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating card.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json($card);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        try {
            $card->delete();
            return response()->json([
                'success' => true,
                'message' => 'Card deleted successfully.',
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting card.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
