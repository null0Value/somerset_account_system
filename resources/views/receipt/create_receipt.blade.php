@extends('master_layout.master_page_layout')
@section('content')
   <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><i class="fa fa-money"></i> Receipt</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Receipt Details</h2>
          <div align="center">
            @include('errors.validator')
          </div>
          <ul class="nav navbar-right panel_toolbox">
            <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <section class="content receipt">
            <!-- title row -->
            <div class="row">
              <div class="col-xs-12 invoice-header">
                <div class="col-md-4">
                  <h4>Receipt #: {{sprintf("%'.07d\n",$receiptNumber)}}</h4>
                </div>
                <div class="col-md-4">
                  <h4>Invoice #: {{sprintf("%'.07d\n",$invoiceNumber)}}</h4>
                </div>
                <div class="col-md-4">
                   <h4 class="pull-right">Date: {{date('F d, y')}}</h4>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
                <div class="form-group">
                   <label for="" class="control-label">Cashier</label>
                   @if(Auth::user()->home_owner_id != NULL)
                      {{Auth::user()->homeOwner->first_name}} {{Auth::user()->homeOwner->last_name}}</h5> 
                    @else
                      <h5>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                    @endif
                </div>
              </div>
              <div class="col-sm-4 invoice-col">
                <div class="form-group">
                   <label class="control-label" for="homeowner">To</label>
                   <h5>{{$homeOwnerInvoice->homeOwner->first_name}} {{$homeOwnerInvoice->homeOwner->middle_name}} {{$homeOwnerInvoice->homeOwner->last_name}}</h5>
                </div>
              </div>
               <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                <label class="control-label" for="homeowner">Payment Due:</label>
                <div class="form-group">
                   <h5>{{date('Y-m-d',strtotime($homeOwnerInvoice->payment_due_date))}}</h5>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- Table row -->
            <div class="row">
              <div class="col-md-6">
                <table class="table table-striped">
                 <thead>
                  <tr>
                    <th style="width: 30%">Payment Type</th>
                   <th style="width: 59%">Covering Month/s</th>
                   <th>Amount (PHP)</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($homeOwnerInvoice->invoiceItems as $pendingPayment)
                    <tr>
                      <td>{{$pendingPayment->accountTitle->account_sub_group_name}}</td>
                      <td>{{$pendingPayment->remarks}}</td>
                      <td>{{$pendingPayment->amount}}</td>
                    </tr>
                  @endforeach
                </tbody>
                </table>
              </div>
               <!-- /.col -->

              <div class="col-xs-6 pull-right">
                <p class="lead">Amount Due</p>
                <div class="table-responsive">
                  <table class="table" id="amountCalc">
                      <tbody>
                        <tr>
                          <th style="width:50%">Total:</th>
                          <td>PHP {{$homeOwnerInvoice->total_amount}}</td>
                        </tr>
                      </tbody>
                  </table>
                </div>
                {!! Form::open(['url'=>'receipt','method'=>'POST','class'=>'form-horizontal form-label-left form-wrapper']) !!}
                 	<input type="hidden" name="payment_id" value="{{$homeOwnerInvoice->id}}">
                  <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="first-name">Amount Paid:
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <input name="amount_paid" type="number" step="0.01" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="first-name">Upload a File:
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <input name="file_related" type="file" class="form-control">
                    </div>
                  </div>
              </div>
              <!-- /.col -->
              <!-- this row will not appear when printing -->
              <div class="row no-print">
                 <div class="col-xs-12">
                    <button type="submit" class="btn btn-success pull-right" onclick="return validateDateInForm();"><i class="fa fa-money"></i> Create Receipt</button>
                    <script type="text/javascript">
                      function validateDateInForm(){
                        var totalAmount = 0;
                        var paidAmount = 0;
                        $("#amountCalc tbody td").each(function(){
                          totalAmount = parseFloat(($(this).text().replace('PHP ','').trim()));
                        });
                        paidAmount = parseFloat($("input[name='amount_paid']").val().trim());
                        
                        if(paidAmount<totalAmount){
                          alert('Paid amount must be greater than total amount');
                          return false;
                        }else{
                          return true;
                        }
                      }
                    </script>
                 </div>
              </div>
            {!! Form::close() !!}
          </section>
        </div>
      </div>
    </div>
   </div>
@endsection