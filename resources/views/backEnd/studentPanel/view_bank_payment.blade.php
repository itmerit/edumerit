<div class="container-fluid">
<style>
hr {
  border: 0;
  clear:both;
  display:block;
  width: 96%;               
  background-color:#369dff;
  height: 1px;
}
</style>
    <div class="row">
        @foreach ($fees_payments as $fees_payment)
        <div class="col-lg-12">
            <div class="row mt-25">
                <div class="col-lg-6  mt-20">
                    <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                @if(moduleStatusCheck('University'))
                                    <div class="name">
                                        @lang('university::un.installment'):
                                    </div>
                                    <div class="value">
                                        {{@$fees_payment->feesInstallment->installment->title}}
                                    </div>
                                @else 
                                    <div class="name">
                                        @lang('fees.fees_type'):
                                    </div>
                                    <div class="value">
                                        {{@$fees_payment->feesType->name}}
                                    </div>
                                @endif 
                            </div>
                        </div>
                </div>
                <div class="col-lg-6 mt-20">
                    <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('common.date'):
                                </div>
                                <div class="value">
                                    {{ !empty($fees_payment->date)? dateConvert(@$fees_payment->date):''}}
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-lg-6 mt-20">
                    <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('accounts.amount'):
                                </div>
                                <div class="value">
                                  {{currency_format(@$fees_payment->amount)}}
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-lg-6 mt-20">
                    <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('common.note'):
                                </div>
                                <div class="value">
                                    {{@$fees_payment->note}}
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-lg-12 mt-20">
                    <div class="single-meta">
                            <div class="justify-content-between">
                                <div class="name">
                                    @lang('accounts.slip')
                                </div>
                                <div class="value text-center">
                                    <img class="student-meta-img" width="100%" src="{{asset(@$fees_payment->slip)}}" alt="">
                                </div>
                            </div>
                        </div>
                </div>
            </div>


        </div>
        <hr/>
        @endforeach
        <div class="d-flex align-items-center justify-content-center">
            <h3>
                Total :  {{currency_format($amount)}}
            </h3>
        </div>
        <div class="col-lg-12 text-center mt-40">
            <div class="mt-40 d-flex justify-content-between">
                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
            </div>
        </div>
    </div>
</div>
