<?php

namespace App\Http\Controllers;

use App\Http\Requests\MerchantPayments\MerchantPaymentsStoreRequest;
use App\Http\Requests\MerchantPayments\MerchantPaymentsUpdateRequest;
use App\MerchantPayment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\MerchantPayment as MerchantPaymentResource;
class MerchantPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        //get merchant payments
        $merchant_payment = MerchantPayment::orderBy('id', 'DESC')->paginate(10);

        //return collection of merchant payments as a resource
        return MerchantPaymentResource::collection($merchant_payment);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MerchantPaymentsStoreRequest $request
     * @return MerchantPaymentResource
     */
    public function store(MerchantPaymentsStoreRequest $request)
    {
        // create merchant payment object
        $merchant_payment =  new MerchantPayment;

        // validate request and return validated data
        $validated = $request->validated();

        //add other merchant payment object properties
        $merchant_payment->description = $validated['description'];
        $merchant_payment->payer_id = $validated["payer_id"];
        $merchant_payment->merchant_id =  $validated["merchant_id"];
        $merchant_payment->amount = $validated['amount'];
        $merchant_payment->currency = $validated['currency'];
        $merchant_payment->transaction_id = $validated['transaction_id'];

        //save merchant payment if transaction goes well
        if($merchant_payment->save()){
            return new MerchantPaymentResource($merchant_payment);
        }

        return new MerchantPaymentResource(null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return MerchantPaymentResource
     */
    public function show($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single merchant payment
        try{
            $merchant_payment = MerchantPayment::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["merchant payment not found"];
            return response(['errors'=> $errors], 404);
        }

        //return single merchant payment as a resource
        return new MerchantPaymentResource($merchant_payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MerchantPaymentsUpdateRequest $request
     * @param  int $id
     * @return MerchantPaymentResource
     */
    public function update(MerchantPaymentsUpdateRequest $request, $id)
    {
        // create merchant payment object
        $merchant_payment =  MerchantPayment::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other merchant payment object properties
        $merchant_payment->description = empty($validated['description'])? $merchant_payment->description : $validated['description'];
        $merchant_payment->payer_id = empty($validated["payer_id"])? $merchant_payment->payer_id : $validated["payer_id"];
        $merchant_payment->merchant_id = empty($validated["merchant_id"])? $merchant_payment->merchant_id : $validated["merchant_id"];
        $merchant_payment->amount = empty($validated['amount'])? $merchant_payment->amount : $validated['amount'];
        $merchant_payment->currency = empty($validated['currency'])? $merchant_payment->currency : $validated['currency'];
        $merchant_payment->transaction_id = empty($validated['transaction_id'])? $merchant_payment->transaction_id : $validated['transaction_id'];

        //save merchant payment if transaction goes well
        if($merchant_payment->save()){
            return new MerchantPaymentResource($merchant_payment);
        }

        return new MerchantPaymentResource(null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return MerchantPaymentResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single merchant payment
        try{
            $merchant_payment = MerchantPayment::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["merchant payment entry not found"];
            return response(['errors'=> $errors], 404);
        }

        //Delete merchant payment
        if($merchant_payment->delete()){
            return new MerchantPaymentResource($merchant_payment);
        }

        return new MerchantPaymentResource(null);
    }
}
