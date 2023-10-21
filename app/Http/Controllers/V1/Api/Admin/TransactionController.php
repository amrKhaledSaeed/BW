<?php

namespace App\Http\Controllers\V1\Api\Admin;

use Exception;
use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\transactionResource;
use App\Http\Requests\TransactionDetailRequest;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use App\Http\RepositoryInterface\RepositoryTransactionInterface;

class TransactionController extends Controller
{
    use helpers;
    private $transactionInterface;
    public function __construct(RepositoryTransactionInterface $transactionInterface)
    {
        $this->transactionInterface = $transactionInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $isUtharize = $this->authorize('view-transactions');
        }catch (Exception $e){
            return $this->apiResponse($e->getMessage());
        }
        $transactionData = $this->transactionInterface->index();
        if($transactionData->first())
        {
            //return $this->apiResponse(['data' => new transactionResource($transactionData)]);
            return $this->apiResponse(new GeneralCollection($transactionData,transactionResource::class));
        }else{

            return $this->apiResponse(['message' => 'not found']);
        }
    }
    public function viewCustomerTransaction()
    {
        try{
            $isUtharize = $this->authorize('view-transactions-customer');
        }catch (Exception $e){
            return $this->apiResponse($e->getMessage());
        }
        $transactionData = $this->transactionInterface->viewCustomerTransaction();
        if($transactionData->first())
        {
            //return $this->apiResponse(['data' => new transactionResource($transactionData)]);
            return $this->apiResponse(new GeneralCollection($transactionData,transactionResource::class));
        }else{

            return $this->apiResponse(['message' => 'not found']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        try{
            $isUtharize = $this->authorize('create-transactions');
        }catch (Exception $e){
            return $this->apiResponse($e->getMessage());
        }
        $this->authorize('create-transactions');
        $transation = $this->transactionInterface->store($request);
        return $this->apiResponse(['data' => new transactionResource($transation)]);

    }
    public function storeTransactionDetail(TransactionDetailRequest $request)
    {
        try{
            $isUtharize = $this->authorize('create-transactions');
        }catch (Exception $e){
            return $this->apiResponse($e->getMessage());
        }
        $this->authorize('create-transactions');
        $transationTedail = $this->transactionInterface->storeTransactionDetail($request);
        return $this->apiResponse(['data' => new transactionResource($transationTedail)]);

    }

    public function updateAllTransactionStatus()
    {
        Artisan::call('transaction:updateStatus');
        return $this->apiResponse(['message' => 'updated']);
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

    public function reportMonthly()
    {
        try{
            $isUtharize = $this->authorize('generate-reports');
        }catch (Exception $e){
            return $this->apiResponse($e->getMessage());
        }
        $report = $this->transactionInterface->reportMonthly();
        if($report->first())
        {
            //return $this->apiResponse(['data' => new transactionResource($transactionData)]);
            return $this->apiResponse(new GeneralCollection($report,transactionResource::class));
        }else{

            return $this->apiResponse(['message' => 'not found']);
        }
    }
}
