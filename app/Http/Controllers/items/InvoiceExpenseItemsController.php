<?php

namespace App\Http\Controllers\items;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UtilityHelper;
use App\Http\Requests\items\InvoiceExpenseItemsRequest;

class InvoiceExpenseItemsController extends Controller
{
    use UtilityHelper;

    public function __construct(){
        $this->middleware('user.type:items');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        try{
            $item = $this->setItem();
            $eAccountTitle = $this->getObjectFirstRecord('account_titles',array('id'=>$id));
            return view('invoice_expense_item.create_invoice_expense_item',
                            compact('item',
                                    'eAccountTitle'));
        }catch(\Exception $ex){
            return view('errors.503');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceExpenseItemsRequest $request)
    {
        $input = $this->addAndremoveKey(Request::all(),true);
        if(array_key_exists('subject_to_vat', $input))
            $input['subject_to_vat'] = ($input['subject_to_vat']==='on'?1:0);
        else
            $input['subject_to_vat'] = 0;

        if(!($input['subject_to_vat']))
            $input['vat_percent'] = 0;

        $accountTitleName = $input['account_title_name'];
        if(array_key_exists('account_title_name', $input)){
            unset($input['account_title_name']);
        }

        try{
            $itemId = $this->insertRecord('invoice_expense_items',$input);
            $this->createSystemLogs('Added New Item in account title ' . $accountTitleName);
            flash()->success('Record successfully created');
            return redirect('accounttitle/'.$input['account_title_id']);
        }catch(\Exception $ex){
            return view('errors.503');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $item = $this->getItem($id);
            
            return view('invoice_expense_item.show_invoice_expense_item',
                            compact('item'));
        }catch(\Exception $ex){
            return view('errors.503');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $item = $this->getItem($id);
            $eAccountTitle = $this->getObjectFirstRecord('account_titles',array('id'=>$item->account_title_id));
            return view('invoice_expense_item.update_invoice_expense_item',
                            compact('item',
                                    'eAccountTitle'));
        }catch(\Exception $ex){
            return view('errors.503');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvoiceExpenseItemsRequest $request, $id)
    {
        $input = $this->addAndremoveKey(Request::all(),false);
        if(array_key_exists('subject_to_vat', $input))
            $input['subject_to_vat'] = ($input['subject_to_vat']==='on'?1:0);
        else
            $input['subject_to_vat'] = 0;

        if(!($input['subject_to_vat']))
            $input['vat_percent'] = 0;

        $accountTitleName = $input['account_title_name'];
        if(array_key_exists('account_title_name', $input)){
            unset($input['account_title_name']);
        }

        try{
            $item = $this->getItem($id);
            $item->update($input);
            $this->createSystemLogs('Updated an existing Account Item');
            flash()->success('Record successfully updated');
            return redirect('accounttitle/'.$input['account_title_id']);
        }catch(\Exception $ex){
            return view('errors.503');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}