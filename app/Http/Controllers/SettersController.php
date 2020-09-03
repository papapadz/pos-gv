<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Session;
use App\Http\Requests;
use DB;
use FPDF;
use App\ProductsModel as PRODUCTS;
use App\SalesModel as SALES;
use App\TransactionsModel as TRANSACTIONS;
use App\ExtraChargesModel as EXTRACHARGES;
use App\DiscountsModel as DISCOUNTS;
use App\GreenPerksModel as CUSTOMERS;
use App\BeginningBalancesModel as BEGINNING;
use App\CreditsModel as CREDITS;
use App\ExpenseReportsModel as EXPENSEREPORTS;
use App\ExpenseCategoriesModel as EXPENSECATEGORIES;
use App\User as USERS;
use App\GreenPerksModel as GREENPERKS;
use App\ProductCategoryModel as PRODUCTCATEGORIES;
use App\ProductPricesModel as PRODUCTPRICES;
use App\ExpenseNamesModel as EXPENSENAMES;

class SettersController extends Controller
{

    public function setSales(Request $req) {

      if($req['tid']) {
        $tid = $req['tid'];
      } else {

        $trans = new TRANSACTIONS;
        $trans->user_id = Auth::User()->id;
        $trans->save();
        $tid = $trans->transaction_id;
      }

      $item_id = $req['item']['id'];    
      $item_qty = $req['item']['qty'];
      $item_price = $req['item']['price'];
      $discount_id = $req['discount']['did'];
      $discount_amt = $req['discount']['amt'];

      $salesArray = [];
      foreach ($item_id as $k => $i) {
        
        $sales = new SALES;
        $sales->product_id = $item_id[$k];
        $sales->qty = $item_qty[$k];
        $sales->price_id = $item_price[$k];
        $sales->promo_id = $discount_id[$k];
        $sales->discount_amount = $discount_amt[$k];
        $sales->transaction_id = $tid;
        $sales->SAVE();
      }

      $charge_name = $req['extraChargeName'];
      
      if($charge_name!=NULL) {

        $charge_amt = $req['extraChargeAmt'];
        $extraCharges = new EXTRACHARGES;
        $extraCharges->charge_name = $charge_name;
        $extraCharges->charge_amount = $charge_amt;
        $extraCharges->transaction_id = $tid;
        $extraCharges->SAVE();

        $returnVal = array( 'tid' => $tid, 'sales' => $salesArray, 'charge_name' => $extraCharges->charge_name, 'charge_amt' => $extraCharges->charge_amount );
      } 
    }

    public function setNewProduct(Request $req) {

      $product = PRODUCTS::JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_products.unit_price_id')->FIND($req['inputpid']);

      if(!$product) {
        $productPrice = new PRODUCTPRICES;
        $productPrice->unit_price = $req['unit_price'];
        $productPrice->SAVE();

        $product = new PRODUCTS;
        $product->unit_price_id = $productPrice->price_id;

      } else {

        if($product->unit_price!=$req['unit_price']) {

          $productPrice = new PRODUCTPRICES;
          $productPrice->unit_price = $req['unit_price'];
          $productPrice->SAVE();

          $product->unit_price_id = $productPrice->price_id;
        }
      }

      $product->product_name = $req['product_name'];
      $product->product_category = $req['product_category'];
      $product->SAVE();

      $img = $req->file('image');

        if($img !== null) {
          $input['image'] = $product->product_id.'.'.$img->getClientOriginalExtension();
          $destinationPath = public_path('/img/prod/');
          $img->move($destinationPath, $input['image']);
          
          $p = PRODUCTS::FIND($product->product_id);
          $p->img_file = $input['image'];
          $p->SAVE();
        }
        
      
      return redirect()->back();
    }

    public function setNewProductCategory(Request $req) {

      if($req['catId']==null)
        $productCat = new PRODUCTCATEGORIES;
      else
        $productCat = PRODUCTCATEGORIES::FIND($req['catId']);
        
      $productCat->category = $req['catName'];
      $productCat->description = $req['catDesc'];
      $productCat->SAVE();
    }

    public function deleteProductCategory(Request $req) {

      $productCat = PRODUCTCATEGORIES::FIND($req['id']);
      $productCat->DELETE();
    }

    public function setPaymentSales(Request $req) {

      $transaction_id = $req['transaction_id'];
      $card_num = $req['card_num'];

      $transaction = TRANSACTIONS::FIND($transaction_id);

      if($transaction->is_paid==2) {
        $credit = CREDITS::WHERE('transaction_id', $transaction_id)->FIRST();
        $credit->date_paid = Carbon::now();
        $credit->SAVE();
      }

      $transaction->is_paid = 1;
      $transaction->cash_tendered = $req['c'];
      $transaction->perk_id = $card_num;
      $transaction->user_id = Auth::User()->id;
      $transaction->updated_at = Carbon::now();
      $transaction->SAVE();

      if($card_num != 0 && $req['total'] >= 100) {

        if($card_num <= 49) {
          
          $pts = floor($req['total']/100);
          $customer = CUSTOMERS::WHERE('card_num', $card_num)->FIRST();
          $customer->total_points = $customer->total_points + $pts;
          $customer->SAVE();
        }
        else if($card_num > 49 && $req['total'] >= 1000) {

          $pts = floor($req['total']/1000);
          $customer = CUSTOMERS::WHERE('card_num', $card_num)->FIRST();
          $customer->total_points = $customer->total_points + $pts;
          $customer->SAVE();
        }  
      }

      $sales = SALES::SELECT(
        'qty',
        'discount_amount',
        'promo_name',
        'tbl_transactions.transaction_id',
        'tbl_transactions.perk_id',
        'is_paid',
        'cash_tendered',
        'product_name',
        'unit_price',
        'tbl_users.first_name',
        'tbl_users.last_name',
        DB::RAW('tbl_green_perks.first_name as customer_first_name'),
        DB::RAW('tbl_green_perks.last_name as customer_last_name'),
        'extra_charge_id',
        'charge_name',
        'charge_amount',
        'total_points',
        DB::RAW('tbl_credits.first_name as credit_first_name'),
        DB::RAW('tbl_credits.last_name as credit_last_name'),
        'contact_no',
        'address'
        )
        ->JOIN('tbl_transactions','tbl_transactions.transaction_id','=','tbl_sales.transaction_id')      
        ->JOIN('tbl_products','tbl_products.product_id','=','tbl_sales.product_id')
        ->JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_sales.price_id')
        ->JOIN('tbl_users','tbl_users.id','=','tbl_transactions.user_id')
        ->LEFTJOIN('tbl_promos','tbl_promos.promo_id','=','tbl_sales.promo_id')
        ->LEFTJOIN('tbl_green_perks','tbl_green_perks.card_num','=','tbl_transactions.perk_id')
        ->LEFTJOIN('tbl_extra_charges','tbl_extra_charges.transaction_id','=','tbl_transactions.transaction_id')
        ->LEFTJOIN('tbl_credits','tbl_credits.transaction_id','=','tbl_transactions.transaction_id')
        ->WHERE('tbl_sales.transaction_id',$req['transaction_id'])
        ->GET();

      return response()->json($sales);
    }

    public function setNewCredit(Request $req) {

      $credit = new CREDITS;
      $credit->transaction_id = $req['transaction_id'];
      $credit->last_name = $req['credit_ln'];
      $credit->first_name = $req['credit_fn'];
      $credit->contact_no = $req['credit_co'];
      $credit->address = $req['credit_ad'];
      $credit->SAVE();

      $transaction = TRANSACTIONS::FIND($req['transaction_id']);
      $transaction->is_paid = 2;
      $transaction->SAVE();
    }

    public function setBeginningBalance(Request $req) {

      $beginning = new BEGINNING;
      $beginning->user_id = Auth::User()->id;
      $beginning->balance = $req['bb'];
      $beginning->SAVE();

    }

    public function setClosingDetails(Request $req) {

      $ddate = Carbon::parse($req['cinCoutDate'])->toDateTimeString();
      $beginning = BEGINNING::WHEREDATE('created_at','=',$ddate)->FIRST();
      $beginning->cash_count = $req['cash_count'];
      $beginning->is_active = 0;
      $beginning->SAVE();
    }

    public function setExpense(Request $req) {

      SettersController::checkBeginningBalance($req['expenseDate']);

      $expreport = new EXPENSEREPORTS;
      $expreport->expense_name_id = $req['expense_category'];
      $expreport->expense_amount = $req['expense_amt'];
      $expreport->measurement = $req['expense_unit'];
      $expreport->qty = $req['expense_qty'];
      $expreport->payment_type = $req['payment_type'];
      $expreport->report_date = $req['expenseDate'];
      $expreport->SAVE();
    }

    public function deleteSales(Request $req) {

      $trans = TRANSACTIONS::FIND($req['id']);
      $trans->DELETE();
    }

    public function deleteProduct(Request $req) {

      $product = PRODUCTS::FIND($req['id']);
      $product->DELETE();
    }

    public function openSalesMenu(Request $req) {

      if($req['id']!=null) {
        $bb = BEGINNING::FIND($req['id']);
        $bb->DELETE();
      }
      else {
        $d = Carbon::now()->toDateString();
        $bb = BEGINNING::WHEREDATE('created_at','=',$d)->FIRST();
        $bb->DELETE();
      } 
    }

    public function setNewUser(Request $req) {

      if($req['flag']==0) {

        if($req['useridInput']==null)
          $user = new USERS;
        else
          $user = USERS::FIND($req['useridInput']);

        if($req['passwordInput']!=null)
            $user->password = bcrypt($req['passwordInput']);

        $user->last_name = $req['lastnameInput'];
        $user->first_name = $req['firstnameInput'];
        $user->user_level = $req['userlevelSelect'];
        $user->birthdate = Carbon::parse($req['bdayInput'])->toDateString();
        $user->username = $req['usernameInput'];
        $user->SAVE();
      
      } else {

        if($req['useridInput']==null)
          $user = new GREENPERKS;
        else
          $user = GREENPERKS::FIND($req['useridInput']);      
        
        $user->card_num = $req['cardNumInput'];
        $user->last_name = $req['lastnameInput'];
        $user->first_name = $req['firstnameInput'];
        $user->contact_num = $req['contactNumInput'];
        $user->birthday = Carbon::parse($req['bdayInput'])->toDateString();
        $user->total_points = $req['pointsInput'];
        $user->SAVE();
      }
    }

    public function deleteUser(Request $req) {

      if($req['flag']==0)
        $user = USERS::FIND($req['id']);
      else
        $user = GREENPERKS::FIND($req['id']);

      $user->DELETE();
    }

    public function addExpenseNames(Request $req) {

      $expenseName = new EXPENSENAMES;
      $expenseName->expense_name = $req['expense_name'];
      $expenseName->expense_category_id = $req['expense_cat'];
      $expenseName->SAVE();
    }

    public function addExpenseCategories(Request $req) {

      $expenseCat = new EXPENSECATEGORIES;
      $expenseCat->category_name = $req['cat'];
      $expenseCat->SAVE();
    }

    public function delExpenseCategories(Request $req) {

      $expenseCat = EXPENSECATEGORIES::FIND($req['id']);
      $expenseCat->DELETE();
    }

    public function deleteExpense(Request $req) {

      $exp = EXPENSEREPORTS::FIND($req['id']);
      $exp->DELETE();
      
    }

    public static function checkBeginningBalance($ddate) {

      $beginning = BEGINNING::WHEREDATE('created_at','=',$ddate)->GET()->COUNT();
      
      if($beginning==0) {
        $bg = new BEGINNING;
        $bg->user_id = Auth::User()->id;
        $bg->balance = 0;
        $bg->is_active = 1;
        $bg->created_at = Carbon::parse($ddate)->toDateTimeString();
        $bg->SAVE();
      }
    }

    public function addNewSalesReport(Request $req) {

      $ttype = $req['selectTransType'];
      $ddate = Carbon::parse($req['inputAddSalesDate'])->toDateString();
      
      SettersController::checkBeginningBalance($ddate);
 
      $product = PRODUCTS::FIND($req['selectProduct']);
      $price = PRODUCTPRICES::FIND($product->unit_price_id);

      $trans = new TRANSACTIONS;
      $trans->user_id = Auth::User()->id;
      $trans->is_paid = 1;
      $trans->created_at = $ddate;
      $trans->updated_at = $ddate;
      $trans->save();

      $sales = new SALES;
      $sales->product_id = $req['selectProduct'];;
      $sales->qty = $req['inputQtyAddSales'];
      $sales->price_id = $price->price_id;
      $sales->promo_id = 0;
      $sales->discount_amount = 0;
      $sales->transaction_id = $trans->transaction_id;
      $sales->SAVE();
      /*
      if($ttype=='1') {

        
      } else {

        $expreport = new EXPENSEREPORTS;
        $expreport->expense_name_id = $req['selectExpense'];
        $expreport->expense_amount = $req['inputExpenseAmt'];
        $expreport->payment_type = 0;
        $expreport->report_date = $ddate;
        $expreport->SAVE();
      }
      */
    }
}
?>
