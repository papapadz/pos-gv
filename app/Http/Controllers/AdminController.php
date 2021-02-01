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
use App\BeginningBalancesModel as BEGINNING;
use App\ExpensesModel as EXPENSES;
use App\ExpenseNamesModel as EXPENSENAMES;
use App\User as USERS;
use App\GreenPerksModel as GREENPERKS;
use App\ProductCategoryModel as PRODUCTCATEGORIES;
use App\CustomerCreditsModel as CUSTOMERCREDITS;
use PDF;

class AdminController extends Controller
{

     public function logout() {

      Session::flush();

      return redirect()->back();
    }
    
    public function checkLogin(Request $req) {

      $this->validate($req, [
        'username'   => 'required',
        'password'  => 'required|alphaNum|min:3'
       ]);

       $user_data = array(
        'username'  => $req->get('username'),
        'password' => $req->get('password')
       );

       if(Auth::attempt($user_data)) {

         $bb = GettersController::getBeginningBalance(0);
         $b_isActive = 0;

         $countUnpaid = GettersController::getUnpaidSales(0);
          $countCredit = GettersController::getUnpaidSales(2);

          if($bb!=null) {

            return redirect()->route('view.menu');
            //AdminController::viewMenu();
            /*
            $prodPasta = PRODUCTS::ORDERBY('product_name')->WHERE('product_category',1)->GET();
            $prodDrink = PRODUCTS::ORDERBY('product_name')->WHERE('product_category',2)->GET();
            $prodSandwich = PRODUCTS::ORDERBY('product_name')->WHERE('product_category',3)->GET();
            $prodDessert = PRODUCTS::ORDERBY('product_name')->WHERE('product_category',4)->GET();
            $prodMercato = PRODUCTS::ORDERBY('product_name')->WHERE('product_category',5)->GET();
            $promoList = DISCOUNTS::WHERE('is_active',1)->ORDERBY('promo_name')->GET();

            return view('menu/home', compact('prod','prodPasta','prodDrink','prodSandwich','prodDessert','prodMercato','promoList'));
            */

          } else {

            return view('welcome', compact('countUnpaid','countCredit'));
          }
                  
       } else
        return back()->with('error', 'Wrong Login Details');
    }

    public static function viewMenu() {

      $bb = GettersController::getBeginningBalance(0);
      $countUnpaid = GettersController::getUnpaidSales(0);
      $countCredit = GettersController::getUnpaidSales(2);

      if($bb) {
        
        if($bb->is_active==0)
          return view('welcome2', compact('countUnpaid','countCredit'));

        $cat = PRODUCTCATEGORIES::ORDERBY('category')->GET();
       
        $arrCat;
        foreach($cat as $k => $c) {
          $arrCat[$k] = PRODUCTS::JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_products.unit_price_id')
            ->ORDERBY('product_name')
            ->WHERE('product_category',$c->category_id)->GET();
        }

        $promoList = DISCOUNTS::WHERE('is_active',1)->ORDERBY('promo_name')->GET();

            return view('menu/home', compact('cat','arrCat','promoList','countUnpaid','countCredit'));    
      }
      return view('welcome', compact('countUnpaid','countCredit'));
    }

    public function viewSales() {
      $b_isActive = 0;
      
      $bb = GettersController::getBeginningBalance(0);
      
      if($bb)
        $b_isActive = $bb->is_active;

        $countUnpaid = GettersController::getUnpaidSales(0);
        $countCredit = GettersController::getUnpaidSales(2);

        $transactions = TRANSACTIONS::SELECT('transaction_id','first_name','last_name','tbl_transactions.created_at','is_paid')
          ->JOIN('tbl_users','tbl_users.id','=','tbl_transactions.user_id')
          ->WHERE('is_paid','!=',2)
          ->GET();
        $totals = array();
        
        foreach ($transactions as $k => $t) {
          
          $tdate = Carbon::parse($t->created_at)->toDateString();
          $beginning = GettersController::getBeginningBalance($tdate);
          
          if($beginning)
            $flag_isActive = $beginning->is_active;
          else
            $flag_isActive = null;

          $sumTotal = 0;
          $sumItems = 0;
          $sales = SALES::SELECT('sales_id','transaction_id','qty','unit_price','discount_amount')
            ->JOIN('tbl_products','tbl_products.product_id','=','tbl_sales.product_id')
            ->JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_sales.price_id')
            ->WHERE('transaction_id', $t->transaction_id)
            ->GET();
          
          foreach ($sales as $s) {
            $sumTotal = ($sumTotal + ($s->unit_price * $s->qty)) - $s->discount_amount;
            $sumItems = $sumItems +  $s->qty;
          }

          $extraCharges = EXTRACHARGES::WHERE('transaction_id',$t->transaction_id)->GET();

          foreach ($extraCharges as $e) {
            $sumTotal = $sumTotal + $e->charge_amount;
            $sumItems = $sumItems + 1;
          }

          $totals['sum'][$k] = number_format((float)$sumTotal, 2, '.', '');
          $totals['items'][$k] = $sumItems;
          $totals['is_active'][$k] = $flag_isActive;

        }
        return view('menu/sales', compact('transactions','totals','countUnpaid','b_isActive','countCredit'));  
      
    }

    public function viewProducts() {

        $countUnpaid = GettersController::getUnpaidSales(0);
        $countCredit = GettersController::getUnpaidSales(2);

        $products = PRODUCTS::SELECT()
          ->JOIN('tbl_product_categories','tbl_product_categories.category_id','=','tbl_products.product_category')
          ->JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_products.unit_price_id')
          ->ORDERBY('product_name')
          ->GET();

        $prodCat = PRODUCTCATEGORIES::ORDERBY('category')->GET();

        return view('menu/products', compact('products','countUnpaid','countCredit','prodCat'));
      
    }

    public function viewProductCategories() {
      
        $countUnpaid = GettersController::getUnpaidSales(0);
        $countCredit = GettersController::getUnpaidSales(2);
      
        $productCat = PRODUCTCATEGORIES::GET();
  
        return view('menu/product-categories', compact('productCat','countUnpaid','countCredit'));
      
    }

    public function viewUsers() {

      $countUnpaid = GettersController::getUnpaidSales(0);
      $countCredit = GettersController::getUnpaidSales(2);

      $users = USERS::GET();
      return view('menu/users', compact('users','countUnpaid','countCredit'));
    }

    public function viewCustomers() {

      $countUnpaid = GettersController::getUnpaidSales(0);
      $countCredit = GettersController::getUnpaidSales(2);

      $customers = GREENPERKS::GET();
      return view('menu/customers', compact('customers','countUnpaid','countCredit'));
    }

    public function viewCustomerCredits() {

      $countUnpaid = GettersController::getUnpaidSales(0);
      $countCredit = GettersController::getUnpaidSales(2);

      $customers = CUSTOMERCREDITS::SELECT('*',DB::RAW('tbl_transactions.created_at as credit_date'))
        ->JOIN('tbl_transactions','tbl_transactions.transaction_id','=','tbl_credits.transaction_id')
        ->GET();

      $arrCredits=null;
      foreach($customers as $k => $c) {

        $sales = SALES::SELECT()
          ->JOIN('tbl_transactions','tbl_transactions.transaction_id','=','tbl_sales.transaction_id')
          ->JOIN('tbl_products','tbl_products.product_id','=','tbl_sales.product_id')
          ->JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_sales.price_id')
          ->LEFTJOIN('tbl_extra_charges','tbl_extra_charges.transaction_id','=','tbl_transactions.transaction_id')
          ->WHERE('tbl_transactions.transaction_id',$c->transaction_id)
          ->GET();
    
        $totalSales = GettersController::calculateSales($sales);
      
        $arrCredits['customers'][$k] = $c;
        $arrCredits['sales'][$k] = $totalSales;
      }
      
      return view('menu/credits', compact('arrCredits','countUnpaid','countCredit'));
    }

    public function viewReports() {

      $bb = GettersController::getBeginningBalance(0);
      $countUnpaid = GettersController::getUnpaidSales(0);
      $countCredit = GettersController::getUnpaidSales(2);  

      $products = PRODUCTS::SELECT()
          ->JOIN('tbl_product_categories','tbl_product_categories.category_id','=','tbl_products.product_category')
          ->JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_products.unit_price_id')
          ->ORDERBY('product_name')
          ->GET();

      $expenses = EXPENSENAMES::ORDERBY('expense_name')->GET();
      
      return view('menu/reports', compact('countUnpaid','countCredit','products','expenses'));
    }

    public function addTransactionItems($tid) {

      return redirect()->route('view.menu')->with('transaction_id',$tid);
    }

    public static function checkAdmin() {

      if(Auth::User()->user_level==1)
        return true;

      return false;
    }

    public function generatereceipt($id) {
      $data = TRANSACTIONS::find($id);
      $or = 'transaction_'.$id.'.pdf';
      $pdf = PDF::loadView('layouts.receipt',compact('data'))->setPaper(array(0,0,204,650))->setOptions(['dpi' => 72,'isHtml5ParserEnabled' => true]);
      $pdf->download('asdf.pdf');
    }
}
?>
