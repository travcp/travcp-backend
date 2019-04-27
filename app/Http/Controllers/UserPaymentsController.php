<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPayments\UserPaymentsStoreRequest;
use App\Http\Requests\UserPayments\UserPaymentsUpdateRequest;
use App\UserPayment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Resources\UserPayment as UserPaymentsResource;

class UserPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get user payments
        $user_payments = UserPayment::orderBy('id', 'DESC')->paginate(10);

        // return collection of user payments as a resource
        return UserPaymentsResource::collection($user_payments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserPaymentsStoreRequest $request
     * @return UserPaymentsResource
     */
    public function store(UserPaymentsStoreRequest $request)
    {
        // create user payment object
        $user_payment =  new UserPayment;

        // validate request and return validated data
        $validated = $request->validated();

        // add other user payments object properties
        $user_payment->description = $validated['description'];
        $user_payment->user_id = $validated['user_id'];
        $user_payment->experience_id = $validated["experience_id"];
        $user_payment->transaction_id = $validated["transaction_id"];
        $user_payment->amount = $validated["amount"];
        $user_payment->currency = $validated["currency"];

        // save user payment if transaction goes well
        if($user_payment->save()){
            return new UserPaymentsResource($user_payment);
        }

        return new UserPaymentsResource(null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return UserPayment
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single user payments
        try{
            $user_payment = UserPayment::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["user payment entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single user payment as a resource
        return new UserPaymentsResource($user_payment);
    }

    /**
     * /**
     * Update the specified resource in storage.
     *
     * @param UserPaymentsUpdateRequest $request
     * @param  int $id
     * @return UserPaymentsResource
     */
    public function update(UserPaymentsUpdateRequest $request, $id)
    {
        // create user payment object
        $user_payment =  UserPayment::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        // add other user payment object properties
        $user_payment->description = empty($validated['description'])? $user_payment->description : $validated['description'];
        $user_payment->user_id = empty($validated['user_id'])? $user_payment->user_id : $validated['user_id'];
        $user_payment->experience_id = empty($validated["experience_id"])? $user_payment->experience_id : $validated["experience_id"];
        $user_payment->transaction_id = empty($validated["transaction_id"])? $user_payment->transaction_id : $validated["transaction_id"];
        $user_payment->amount = empty($validated["amount"])? $user_payment->amount : $validated["amount"];
        $user_payment->currency = empty($validated["currency"])? $user_payment->currency : $validated["currency"];

        // save payment if transaction goes well
        if($user_payment->save()){
            return new UserPaymentsResource($user_payment);
        }

        return new UserPaymentsResource(null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return UserPaymentsResource
     */
    public function destroy($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single user payment
        try{
            $user_payment = UserPayment::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["user payment entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete user payment entry
        if($user_payment->delete()){
            return new UserPaymentsResource($user_payment);
        }

        return new UserPaymentsResource(null);
    }
}
