@extends('frontend.layouts.app')

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('labels.frontend.auth.verify_mobile_title') }}</div>

                <div class="panel-body text-center">

                    <p>
                        Please enter the 4 digit code that was sms'd to
                        <strong>
                            {{ access()->user()->phone_number}}
                        </strong>

                    </p>
                    <p>
                        <small>If you do not get this sms, please contact us for assistance</small>
                    </p>
                    {{ Form::open(['route' => 'frontend.confirm.mobile.show', 'class' => 'form-horizontal']) }}

                    <div class="form-group">

                        <div class="col-md-4 col-md-offset-4">
                            {{ Form::text(
                                'confirmation_code',
                                null,
                                [
                                    'class' => 'form-control',
                                    'maxlength' => '191',
                                    'required' => 'required',
                                    'autofocus' => 'autofocus',
                                    'placeholder' => trans('validation.attributes.frontend.confirmation_number')
                                    ]
                                ) }}
                        </div><!--col-md-6-->

                    </div><!--form-group-->



                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-4">
                            {{ Form::submit(trans('labels.frontend.auth.verify_button'), ['class' => 'btn
                            btn-info',
                           ]) }}
                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    {{ Form::close() }}

                </div>
            </div><!-- panel body -->


            <div class="panel-body text-center">


                <p>
                    <small>If you have not received the sms, you may request a resend </small>
                </p>

                <div class="row text-center">
                    {{ Form::open(['route' => 'frontend.confirm.mobile.resend', 'class' => 'form-horizontal']) }}
                    {{ Form::submit(trans('labels.frontend.auth.resend'), ['class' => 'btn
                                                btn-info
                                                btn-primary',
                                                ]) }}
                    {{ Form::close() }}



                </div>
            </div><!-- panel body -->
        </div><!-- col-md-8 -->

    </div><!-- row -->

@endsection
