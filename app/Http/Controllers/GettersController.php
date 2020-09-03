<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Session;
use App\Http\Requests;
use DB;
use App\ProductsModel as PRODUCTS;
use App\SalesModel as SALES;
use App\ExtraChargesModel as EXTRACHARGES;
use App\GreenPerksModel as GREENPERKS;
use App\BeginningBalancesModel as BEGINNING;
use App\TransactionsModel as TRANSACTIONS;
use App\User as USERS;
use App\ExpenseCategoriesModel as EXPENSECATEGORIES;
use App\ExpenseReportsModel as EXPENSEREPORTS;
use App\ExpenseNamesModel as EXPENSENAMES;
use App\ProductCategoryModel as PRODUCTCATEGORIES;

class GettersController extends Controller
{

    public function getProduct(Request $req) {

	    $data = PRODUCTS::JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_products.unit_price_id')->FIND($req['id']);

	    echo $data;
  	}

  	public function getSales(Request $req) {

  		$sales = SALES::WHERE('transaction_id',$req['id']);

  		$extraCharges = EXTRACHARGES::WHERE('transaction_id',$req['id'])->FIRST();
  		
  		if($extraCharges!=NULL)
  			return response()->json(array('charge_name' => $extraCharges->charge_name, 'charge_amt' => $extraCharges->charge_amount ,'sales' => $sales));
  				
  		return response()->json($sales);
  	}

    public function getProductCategoryInfo(Request $req) {

      $productCat = PRODUCTCATEGORIES::FIND($req['id']);
      return $productCat;
    }

  	public function getPerksInfo(Request $req) {

  		$perk = GREENPERKS::WHERE('card_num', $req['card_num'])->FIRST();

  		if($perk!=null) {

        $mbday = Carbon::parse($perk->birthday)->month;
        $dbday = Carbon::parse($perk->birthday)->day;
        
        $month = Carbon::now()->month;
        $day = Carbon::now()->day;

        if($mbday==$month && $dbday==$day)
          $isbday = 1;
        else
          $isbday = 0;        

  			return response()->json(array( 'status'=> 1, 'first_name' => $perk->first_name, 'last_name' => $perk->last_name, 'birthday' => $isbday));
      }

  		return response()->json(array('status'=> 0));
  		
  	}

    public function getTransactionSales(Request $req) {

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
        ->JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_sales.price_id')  
        ->JOIN('tbl_products','tbl_products.product_id','=','tbl_sales.product_id')
        ->JOIN('tbl_users','tbl_users.id','=','tbl_transactions.user_id')
        ->LEFTJOIN('tbl_promos','tbl_promos.promo_id','=','tbl_sales.promo_id')
        ->LEFTJOIN('tbl_green_perks','tbl_green_perks.card_num','=','tbl_transactions.perk_id')
        ->LEFTJOIN('tbl_extra_charges','tbl_extra_charges.transaction_id','=','tbl_transactions.transaction_id')
        ->LEFTJOIN('tbl_credits','tbl_credits.transaction_id','=','tbl_transactions.transaction_id')
        ->WHERE('tbl_sales.transaction_id',$req['transaction_id'])
        ->GET();
        
      return response()->json($sales);

    }

    public static function getBeginningBalance($dd) {

      if($dd==0)
        $dnow = Carbon::now()->toDateString();
      else
        $dnow = $dd;
      $bb = BEGINNING::WHEREDATE('created_at', '=', $dnow)->FIRST();
      return $bb;
    }

    public static function getUnpaidSales($flag) {

      $sales = TRANSACTIONS::WHERE('is_paid',$flag)->GET()->COUNT();
      return $sales;
    }

    public function getClosingDetails(Request $req) {

      //$ddate = Carbon::parse($req['d'])->toDateString();

      $beginning = GettersController::getBeginningBalance($req['d']);
      $cafeSales = GettersController::getTodaySales(0,$req['d']);
      $mercatoSales = GettersController::getTodaySales(5,$req['d']);
      $arPayments = GettersController::getArPayments(1,$req['d']);
      $arReceivables = GettersController::getArPayments(0,$req['d']);
      $cashout = GettersController::getTodayExpenseReports(0,Carbon::parse($req['d'])->toDateString());
      $nCash = GettersController::getTodayExpenseReports(1,Carbon::parse($req['d'])->toDateString());
      $expCredit = GettersController::getTodayExpenseReports(2,Carbon::parse($req['d'])->toDateString());
      
      $retVal = array(
        'beginning' => $beginning,
        'cafe' => $cafeSales,
        'mercato' => $mercatoSales,
        'payment' => $arPayments,
        'credit' => $arReceivables,
        'cashout' => $cashout,
        'ncash' => $nCash,
        'expCredit' => $expCredit
      );

      return response()->json($retVal);
    }

    public static function getTodaySales($cat,$d) {

      $op = '!=';
      $dnow = $d;
      
      if($cat==5)
        $op = '=';

      if($d==0)
        $dnow = Carbon::now()->toDateString();
      
      $sales = SALES::SELECT()
        ->JOIN('tbl_transactions','tbl_transactions.transaction_id','=','tbl_sales.transaction_id')
        ->JOIN('tbl_products','tbl_products.product_id','=','tbl_sales.product_id')
        ->JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_sales.price_id')
        ->LEFTJOIN('tbl_extra_charges','tbl_extra_charges.transaction_id','=','tbl_transactions.transaction_id')
        ->WHERE([
            ['tbl_transactions.is_paid','=',1],
            ['tbl_products.product_category',$op,5]
          ])
        ->WHEREDATE('tbl_transactions.updated_at', '=', $dnow)
        ->GET();

        return $sales;
    }

    public static function getArPayments($flag, $d) {

      $op = '!=';
      //$condi = 'tbl_transactions.updated_at';
      $dnow = $d;

      if($flag==0) {
        $op = '=';
        //$condi = 'tbl_transactions.created_at';
      }

      if($d==0)
        $dnow = Carbon::now()->toDateString();
      
      /*
      $credit = TRANSACTIONS::SELECT()
        ->JOIN('tbl_credits','tbl_credits.transaction_id','=','tbl_transactions.transaction_id')
        ->WHERE('tbl_transactions.is_paid',2)
        ->WHEREBETWEEN('tbl_transactions.created_at', [$dnowFrom, $dnowTo])
        ->GET();
      */

      $credit = SALES::SELECT()
        ->JOIN('tbl_transactions','tbl_transactions.transaction_id','=','tbl_sales.transaction_id')
        ->JOIN('tbl_products','tbl_products.product_id','=','tbl_sales.product_id')
        ->JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_sales.price_id')
        ->JOIN('tbl_credits','tbl_credits.transaction_id','=','tbl_sales.transaction_id')
        ->LEFTJOIN('tbl_extra_charges','tbl_extra_charges.transaction_id','=','tbl_transactions.transaction_id')
        ->WHERE('tbl_credits.date_paid',$op,null)
        ->WHEREDATE('tbl_transactions.updated_at','=', $dnow)
        ->GET();

      return $credit;
    }

    public function getDailyCashinCashout(Request $req) {

      $arrayBeginnings = null;

      if($req['flag']!=null)
        $beginning = BEGINNING::ORDERBY('created_at','DESC')->GET();
      else if($req['id']==null)
        $beginning = BEGINNING::WHERE('is_active',0)->ORDERBY('created_at','DESC')->GET();
      else
        $beginning = BEGINNING::WHERE('beginning_balance_id', $req['id'])->GET();
     
      
      foreach($beginning as $k => $b) {
        $cafeSales = $mercatoSales = $arPayments = $arReceivables = $cashAmt = $nCashAmt = $eCredit = 0;

        $d = Carbon::parse($b->created_at)->toDateString();
        $sales1 = GettersController::getTodaySales(0,$d);
        $sales2 = GettersController::getTodaySales(5,$d);
        $ar1 = GettersController::getArPayments(1,$d);
        $ar2 = GettersController::getArPayments(0,$d);
        $expCash = GettersController::getTodayExpenseReports(0,$d);
        $expNCash = GettersController::getTodayExpenseReports(1,$d);
        $expCredit = GettersController::getTodayExpenseReports(2,$d);

        /*
        foreach($sales1 as $s1) { $cafeSales = $cafeSales + (( $s1->unit_price * $s1->qty ) - $s1->discount_amount); }
        foreach($sales2 as $s2) { $mercatoSales = $mercatoSales + (( $s2->unit_price * $s2->qty ) - $s2->discount_amount); }
        foreach($ar1 as $a1) { $arPayments = $arPayments + (( $a1->unit_price * $a1->qty ) - $a1->discount_amount); }
        foreach($ar2 as $a2) { $arReceivables = $arReceivables + (( $a2->unit_price * $a2->qty ) - $a2->discount_amount); }
        */
        
        $cafeSales = GettersController::calculateSales($sales1);
        $mercatoSales = GettersController::calculateSales($sales2);
        $arPayments = GettersController::calculateSales($ar1);
        $arReceivables = GettersController::calculateSales($ar2);

        foreach($expCash as $exp) { $cashAmt = $cashAmt + $exp->expense_amount; }
        foreach($expNCash as $exp) { $nCashAmt = $nCashAmt + $exp->expense_amount; }
        foreach($expCredit as $exp) { $eCredit = $eCredit + $exp->expense_amount; }

        $user = USERS::FIND($b->user_id);

        $arrayBeginnings[$k]['beginning_id'] = $b->beginning_balance_id;
        $arrayBeginnings[$k]['is_active'] = $b->is_active;
        $arrayBeginnings[$k]['date'] = $d;
        $arrayBeginnings[$k]['beginning'] = $b->balance;
        $arrayBeginnings[$k]['cafe'] = $cafeSales;
        $arrayBeginnings[$k]['mercato'] = $mercatoSales;
        $arrayBeginnings[$k]['payment'] = $arPayments;
        $arrayBeginnings[$k]['credit'] = $arReceivables;
        $arrayBeginnings[$k]['ap'] = $eCredit;
        $arrayBeginnings[$k]['cash_out'] = $cashAmt;
        $arrayBeginnings[$k]['n_cash'] = $nCashAmt;
        $arrayBeginnings[$k]['cash_count'] = $b->cash_count;
        $arrayBeginnings[$k]['cashier'] = $user->last_name.', '.$user->first_name;
      }

      return response()->json($arrayBeginnings);
    }

    public function getExpenseCategories() {

      $ecat = EXPENSECATEGORIES::ORDERBY('category_name')->GET();

      return $ecat;
    }

    public function getExpenseNames(Request $req) {

      if($req['name'])
        $ecat = EXPENSENAMES::WHERE('expense_name','=',$req['name'])->FIRST();
      else
        $ecat = EXPENSENAMES::ORDERBY('expense_name')->GET();

      return $ecat;
    }

    public function getTodayExpenseReports($flag,$d) {

      $dnow = Carbon::parse($d)->toDateString();

      $expreport = EXPENSEREPORTS::SELECT()
        ->WHERE('payment_type',$flag)
        ->WHEREDATE('report_date','=',$dnow)->GET();
      
      return $expreport;
    }

    public function getExpenseReportsByMonth(Request $req) {

      
      /*$expreport = EXPENSEREPORTS::select('*',DB::raw('YEAR(report_date) year, MONTH(report_date) month'))
               ->groupby('year','month')
               ->get();
      */
      //$expreport = EXPENSEREPORTS::WHEREDATE('report_date','=',$ddate)->GET();
      $arrayCats = null;
      $dateFrom = Carbon::parse($req['dd'])->toDateString();
      $dateTo = Carbon::parse($dateFrom)->year.'-'.Carbon::parse($dateFrom)->month.'-'.Carbon::parse($dateFrom)->daysInMonth;
      $arrayCats['date'] = Carbon::parse($dateFrom)->year.'-'.Carbon::parse($dateFrom)->month;

        $expCat = EXPENSECATEGORIES::ORDERBY('category_name')->GET();

        $cGrandTotal = 0;
        $ncGrandTotal = 0;
        $apGrandTotal = 0;
        foreach($expCat as $k => $cat) {

            $eAmt = GettersController::getExpenseReportsByCategory($cat->expense_category_id,$dateFrom,0);
            $eAmt2 = GettersController::getExpenseReportsByCategory($cat->expense_category_id,$dateFrom,1);
            $eAmt3 = GettersController::getExpenseReportsByCategory($cat->expense_category_id,$dateFrom,2);
            //$arrayCats['c'.($k+1)] = $eAmt;
            $cGrandTotal = $cGrandTotal + $eAmt;
            $ncGrandTotal = $ncGrandTotal + $eAmt2;
            $apGrandTotal = $apGrandTotal + $eAmt3;
        }
          $arrayCats['cash_grand_total'] = $cGrandTotal;
          $arrayCats['ncash_grand_total'] = $ncGrandTotal;
          $arrayCats['ap_grand_total'] = $apGrandTotal;

          $totalSales = 0;
          $sales = SALES::SELECT()
            ->JOIN('tbl_transactions','tbl_transactions.transaction_id','=','tbl_sales.transaction_id')
            ->JOIN('tbl_products','tbl_products.product_id','=','tbl_sales.product_id')
            ->JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_sales.price_id')
            ->LEFTJOIN('tbl_extra_charges','tbl_extra_charges.transaction_id','=','tbl_transactions.transaction_id')
            ->WHERE('tbl_transactions.is_paid',1)
            ->WHEREBETWEEN('tbl_transactions.updated_at', [$dateFrom, $dateTo])
            ->GET();

          $payments = SALES::SELECT()
            ->JOIN('tbl_transactions','tbl_transactions.transaction_id','=','tbl_sales.transaction_id')
            ->JOIN('tbl_products','tbl_products.product_id','=','tbl_sales.product_id')
            ->JOIN('tbl_credits','tbl_credits.transaction_id','=','tbl_sales.transaction_id')
            ->LEFTJOIN('tbl_extra_charges','tbl_extra_charges.transaction_id','=','tbl_transactions.transaction_id')
            ->WHERE('tbl_credits.date_paid','!=',null)
            ->WHEREBETWEEN('tbl_credits.date_paid', [$dateFrom, $dateTo])
            ->GET();

          
          //foreach($sales as $s1) { $totalSales = $totalSales + (( $s1->unit_price * $s1->qty ) - $s1->discount_amount); }

          //foreach($payments as $s1) { $totalSales = $totalSales + (( $s1->unit_price * $s1->qty ) - $s1->discount_amount); }
          
          $totalSales = $totalSales + GettersController::calculateSales($sales) + GettersController::calculateSales($payments);

          $arrayCats['sales'] = $totalSales;
          $arrayCats['items'] = GettersController::getExpenseList($dateFrom);
          //} 

      return response()->json($arrayCats);
        
    }

    public function getExpenseReports() {

      $expreport = EXPENSEREPORTS::JOIN('tbl_expenses','tbl_expenses.expense_id','=','tbl_expense_reports.expense_name_id')
        ->JOIN('tbl_expense_categories','tbl_expense_categories.expense_category_id','=','tbl_expenses.expense_category_id')
        ->GET();
      return $expreport;
    }

    public static function getExpenseReportsByCategory($cat,$d,$ptype){

      $dateFrom = Carbon::parse($d)->toDateString();
      $dateTo = Carbon::parse($d)->year.'-'.Carbon::parse($d)->month.'-'.Carbon::parse($d)->daysInMonth;
      $total = 0;
      $eitems = EXPENSEREPORTS::JOIN('tbl_expenses','tbl_expenses.expense_id','=','tbl_expense_reports.expense_name_id')
        ->JOIN('tbl_expense_categories','tbl_expense_categories.expense_category_id','=','tbl_expenses.expense_category_id')
        ->WHERE('tbl_expenses.expense_category_id',$cat)
        ->WHERE('payment_type',$ptype)
        ->WHEREBETWEEN('report_date', [$dateFrom, $dateTo])
        ->GET();
      
      if($eitems) {
        foreach($eitems as $j => $eit) { $total = $total + $eit->expense_amount; }
      }

      return $total;
    }

    public static function getExpenseList($d) {

      $arrExpenseList = null;

      $dateFrom = Carbon::parse($d)->toDateString();
      $dateTo = Carbon::parse($dateFrom)->year.'-'.Carbon::parse($dateFrom)->month.'-'.Carbon::parse($dateFrom)->daysInMonth;
      
      $expcat = EXPENSEREPORTS::SELECT()
        ->JOIN('tbl_expenses','tbl_expenses.expense_id','=','tbl_expense_reports.expense_name_id')
        ->JOIN('tbl_expense_categories','tbl_expense_categories.expense_category_id','=','tbl_expenses.expense_category_id')
        ->WHEREBETWEEN('report_date', [$dateFrom, $dateTo])
        ->GROUPBY('tbl_expenses.expense_category_id')
        ->ORDERBY('tbl_expenses.expense_category_id')
        ->GET();

      foreach($expcat as $k => $exp) {
          
          $expreport = EXPENSEREPORTS::SELECT()
            ->JOIN('tbl_expenses','tbl_expenses.expense_id','=','tbl_expense_reports.expense_name_id')
            ->JOIN('tbl_expense_categories','tbl_expense_categories.expense_category_id','=','tbl_expenses.expense_category_id')
            ->WHERE('tbl_expenses.expense_category_id',$exp->expense_category_id)
            ->WHEREBETWEEN('report_date', [$dateFrom, $dateTo])
            ->GET();

            $arrExpenseNames = null;
            foreach($expreport as $j => $er) {
              $etots = 0;
              
              $ee = EXPENSEREPORTS::SELECT()
                ->JOIN('tbl_expenses','tbl_expenses.expense_id','=','tbl_expense_reports.expense_name_id')
                ->WHERE('expense_name_id',$er->expense_name_id)
                ->WHEREBETWEEN('report_date', [$dateFrom, $dateTo])
                ->GET();

                foreach ($ee as $e) {
                  $etots = $etots + $e->expense_amount;
                } 
              
              $arrExpenseNames[$j]['category_name'] = $er->category_name;
              $arrExpenseNames[$j]['expense_name_id'] = $er->expense_name_id;
              $arrExpenseNames[$j]['expense_name'] = $er->expense_name;
              $arrExpenseNames[$j]['expense_amount'] = $e->expense_amount;
              $arrExpenseNames[$j]['grand_expense_total'] = $etots;    
            }
            
            $arrExpenseList[$k]['cat'] = $arrExpenseNames;
            //$arrExpenseList[$k]['cat'] = $expreport;
      }
      
      return $arrExpenseList;
    }


    public function getExpenseSummary(Request $req) {
      
      $dateFrom = Carbon::parse($req['dd'])->toDateString();
      $dateTo = Carbon::parse($dateFrom)->year.'-'.Carbon::parse($dateFrom)->month.'-'.Carbon::parse($dateFrom)->daysInMonth;
      
      $eitems = EXPENSEREPORTS::JOIN('tbl_expenses','tbl_expenses.expense_id','=','tbl_expense_reports.expense_name_id')
        ->JOIN('tbl_expense_categories','tbl_expense_categories.expense_category_id','=','tbl_expenses.expense_category_id')
        ->GROUPBY('tbl_expense_reports.expense_name_id')
        ->WHEREBETWEEN('report_date', [$dateFrom, $dateTo])
        ->GET();

      $arrExpenseSummary = null;
      foreach($eitems as $k => $e) {
        
        $ee = EXPENSEREPORTS::JOIN('tbl_expenses','tbl_expenses.expense_id','=','tbl_expense_reports.expense_name_id')
        ->JOIN('tbl_expense_categories','tbl_expense_categories.expense_category_id','=','tbl_expenses.expense_category_id')
        ->WHERE('tbl_expense_reports.expense_name_id',$e->expense_name_id)
        ->WHEREBETWEEN('report_date', [$dateFrom, $dateTo])
        ->GET();

        $tots = 0;
        $qty = 0;
        foreach($ee as $e) {
          $tots = $tots + $e->expense_amount;
          $qty = $qty + $e->qty;
        }
       
        $arrExpenseSummary[$k]['expense_name'] = $e->expense_name;
        $arrExpenseSummary[$k]['amt'] = $tots;
        $arrExpenseSummary[$k]['qty'] = $qty;
        $arrExpenseSummary[$k]['unit'] = $e->measurement;
         
      }

      return $arrExpenseSummary;
    }

    public function getProductTrends(Request $req) {

      $y = $req['year'];

      $products = PRODUCTS::SELECT('product_id','product_name','category')
        ->JOIN('tbl_product_categories','tbl_product_categories.category_id','=','tbl_products.product_category')
        ->GET();

      $arrProducts = null;

      foreach($products as $k => $p) {

        $arrProducts[$k]['name'] = $p->product_name;
        $arrProducts[$k]['cat'] = $p->category;
        $arrProducts[$k]['m1'] = GettersController::getProductTrendsByYear($p->product_id,$y,1);
        $arrProducts[$k]['m2'] = GettersController::getProductTrendsByYear($p->product_id,$y,2);
        $arrProducts[$k]['m3'] = GettersController::getProductTrendsByYear($p->product_id,$y,3);
        $arrProducts[$k]['m4'] = GettersController::getProductTrendsByYear($p->product_id,$y,4);
        $arrProducts[$k]['m5'] = GettersController::getProductTrendsByYear($p->product_id,$y,5);
        $arrProducts[$k]['m6'] = GettersController::getProductTrendsByYear($p->product_id,$y,6);
        $arrProducts[$k]['m7'] = GettersController::getProductTrendsByYear($p->product_id,$y,7);
        $arrProducts[$k]['m8'] = GettersController::getProductTrendsByYear($p->product_id,$y,8);
        $arrProducts[$k]['m9'] = GettersController::getProductTrendsByYear($p->product_id,$y,9);
        $arrProducts[$k]['m10'] = GettersController::getProductTrendsByYear($p->product_id,$y,10);
        $arrProducts[$k]['m11'] = GettersController::getProductTrendsByYear($p->product_id,$y,11);
        $arrProducts[$k]['m12'] = GettersController::getProductTrendsByYear($p->product_id,$y,12);
      }

      return response()->json($arrProducts);

    }

    public static function getProductTrendsByYear($prod, $y, $m) {

      $total = 0;

      $dateFrom = Carbon::parse($y.'-'.$m.'-01')->toDateString();
      $dateTo = Carbon::parse($y.'-'.$m.'-'.Carbon::parse($dateFrom)->daysInMonth)->toDateString();;

      $sales = SALES::SELECT('tbl_sales.qty')
        ->JOIN('tbl_transactions','tbl_transactions.transaction_id','=','tbl_sales.transaction_id')
        ->JOIN('tbl_products','tbl_products.product_id','=','tbl_sales.product_id')
        ->JOIN('tbl_product_categories','tbl_product_categories.category_id','=','tbl_products.product_category')
        ->WHERE('tbl_sales.product_id',$prod)
        ->WHEREBETWEEN('tbl_transactions.created_at',[$dateFrom, $dateTo])
        ->GET();

      if($sales) {
        foreach($sales as $s) { $total = $total +  $s->qty; }
      }

      return $total;
    }

    public function getProductInfo(Request $req) {

      $prod = PRODUCTS::JOIN('tbl_product_prices','tbl_product_prices.price_id','=','tbl_products.unit_price_id')->FIND($req['id']);
      return $prod;
    }

    public static function calculateSales($salesObj) {
      
      $totalSales = 0;
      $charge = 0;

      foreach($salesObj as $sales) {

        if($sales->charge_amount!=null)
          $charge = $sales->charge_amount;

        $totalSales = $totalSales + ((( $sales->unit_price * $sales->qty ) + $charge) - $sales->discount_amount); 
      }
      return $totalSales;
    }

    public function getUser(Request $req) {

      if($req['flag']==0)
        $users = USERS::FIND($req['id']);
      else
        $users = GREENPERKS::FIND($req['id']);
      return $users;
    }
}
?>
