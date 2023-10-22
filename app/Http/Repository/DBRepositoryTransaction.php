<?php
namespace App\Http\Repository;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\RepositoryInterface\RepositoryTransactionInterface;

class DBRepositoryTransaction implements RepositoryTransactionInterface
{
    private $categoryModel;
    private $subCategoryModel;
    private $transactionModel;
    private $transactioDetailModel;
    public function __construct(Transaction $transactionModel,TransactionDetail $transactioDetailModel,Category $categoryModel,SubCategory $subCategoryModel)
    {
        $this->categoryModel = $categoryModel;
        $this->subCategoryModel = $subCategoryModel;
        $this->transactionModel = $transactionModel;
        $this->transactioDetailModel = $transactioDetailModel;
    }
    public function index()
    {
        $transactionData = $this->transactionModel->with('subCategory','category','user','transactionDetail')->get();
        return $transactionData;

    }
    public function viewCustomerTransaction()
    {
        $transactionData = $this->transactionModel->with('subCategory','category','user','transactionDetail')->
        where('payer',auth()->user()->id)->get();
        return $transactionData;

    }



    public function store($request)
    {
        $transaction = $this->transactionModel->create([
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'amount' => $request->amount,
            'payer' => $request->payer,
            'due_on' => $request->due_on,
            'vat' => $request->vat,
            'is_vat_inclusive' => $request->is_vat_inclusive,
            'status' => $this->status($request),
        ]);
        return $transaction;
    }
    public function storeTransactionDetail($request)
    {
        $transactionDetail = $this->transactioDetailModel->create([
            'transaction_id' => $request->transaction_id,
            'amount_paied' => $request->amount_paied,
            'paied_on' => now(),
            'details' => $request->details,

        ]);
        return $transactionDetail;
    }

    public function status($request)
    {

        $method = request()->method();
        if(!empty(request()->route()) && request()->segment )
        {
            $route = $request->segment(count((request()->segments())));
        }else{
            $route = false;
        }
       // dd($method, $route);
       if($method == 'POST' && $route == 'transaction')// status by due_on transaction
       {
        if($request->due_on < now())
        {
            return 'overdue';
        }elseif($request->due_on > now())
        {
            return 'outstanding';
        }
       }else{ //else is will be get status by transaction details
            $transaction = $this->transactionModel->whereHas('transactionDetail',function($q)use($request){
                $q->where('transaction_id',$request->transaction_id);
            })->withSum('transactionDetail','amount_paied')->get();
           // dd(empty($transaction->first()));
            if(!empty($transaction->first()))
            {
                $amount = $this->getTotalAmount($transaction->first()->amount, $transaction->first()->vat,$transaction->first()->is_vat_inclusive);
                if($transaction->due_on < now())
                {
                    if($transaction->sum_transactionDetail_amount < $amount)
                    {
                        $this->updateStatus($transaction->first()->id,'overdue');
                        return 'overdue';
                    }
                    if($transaction->sum_transactionDetail_amount == $amount)
                    {
                        $this->updateStatus($transaction->first()->id,'paied');

                        return 'paied';
                    }
                }else{
                    if($transaction->sum_transactionDetail_amount_paied < $amount)
                    {
                        if($transaction->transactionDetail->latest()->first()->paied_on < $transaction->due_on)
                        {
                            $this->updateStatus($transaction->first()->id,'outstanding');

                            return 'outstanding';
                        }elseif(now() > $transaction->transactionDetail->latest()->first()->paied_on)
                        {
                            $this->updateStatus($transaction->first()->id,'overdue');

                            return 'overdue';
                        }


                    }

                }

            }else{
                return null;
            }


       }

    }
    public function updateStatus($transactio_id,$status)
    {
        $updateTransaction = $this->transactionModel->where('transaction_id', $transactio_id)->update([
            'status' => $status
        ]);

    }

    public function getTotalAmount($amount,$vat,$isVatInclusive)
    {
                if($isVatInclusive == 1)
        {return $amount;}else{
            $vatOfAmount = $amount *$vat/100;
            $amountWithVat = $amount + $vatOfAmount;
            return $amountWithVat;
        }
    }

    public function reportMonthly()
    {
    //   return  $this->transactionModel->with(['transactionDetail' => function($q){
    //   return  $q->select('paied_on',DB::raw('MONTH(paied_on) as month, YEAR(paied_on), SUM(amount_paied) as Paied'))
    //      ->groupBy(DB::raw('MONTH(paied_on), YEAR(paied_on)'));
    // //   ->orderBy(DB::raw('YEAR(paied_on)'), 'asc')
    // //   ->orderBy(DB::raw('MONTH(paied_on)'), 'asc');
    //   }])
    // //   ->select(DB::raw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(transaction_details.amount_paied) as paid, SUM(outstanding) as outstanding, SUM(overdue) as overdue'))
    // //   ->groupBy(DB::raw('MONTH(created_at), YEAR(created_at)'))
    // //   ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
    // //   ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
    //   ->get();
      $dd =$this->transactioDetailModel->join('transactions','transactions.id','=','transaction_details.transaction_id')->select(DB::raw('MONTH(paied_on) as moth
      ,Year(paied_on) as year
      , SUM(amount_paied) as paied
        '))
      ->groupBy(DB::raw('MONTH(paied_on),Year(paied_on)'))
      ->get();
      return $dd;

    }

}

