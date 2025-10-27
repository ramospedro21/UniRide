<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Ratings\RatingsService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class RatingsController extends Controller
{
    protected $ratingsService;

    public function __construct(RatingsService $ratingsService) {
        $this->ratingsService = $ratingsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                "ride_id" => "required|integer",
                "reviewed_id" => "required",
                "score" => "required|between:1,5",
                "comment" => "nullable|string",
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $validator->validated();
            $data['reviewer_id'] = $request->user()->id;

            $this->ratingsService->create($data);
    
            return $this->respondWithOk([], 201);
        } catch (ValidationException $e) {

            $firstError = $e->validator->errors()->first();
            return $this->respondWithErrors($firstError);

        } catch (\Exception $e) {

            return $this->respondWithErrors($e->getMessage());

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
