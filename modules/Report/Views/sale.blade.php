@extends("Base::layouts.master")
<?php
$month = (isset($filter['month'])) ? strtotime(\Carbon\Carbon::createFromFormat('m-Y', $filter['month'])) : time();
?>
@section("content")
    <div id="service-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Report") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Sales Report") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Sales Report") }}</h3></div>
            <div class="group-btn">
                <a href="{{ route('get.report.sale', array_merge(request()->query(), ['export' => true])) }}"
                   class="btn btn-info">{{ trans('Export') }}</a>
            </div>
        </div>
    </div>
    <div class="search-box">
        <div class="card">
            <div class="card-header" data-toggle="collapse" data-target="#form-search-box" aria-expanded="false"
                 aria-controls="form-search-box">
                <div class="title">{{ trans("Search") }}</div>
            </div>
            <div class="card-body collapse show" id="form-search-box">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Invoice code") }}</label>
                                <input type="number" class="form-control" id="text-input" name="code"
                                       value="{{ $filter['code'] ?? NULL }}">
                            </div>
                        </div>
                        <div class="col-md-3 d-none">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Invoice Type") }}</label>
                                {!! Form::select('order_type', ["" => trans("All")] + $order_types, $filter['order_type'] ?? NULL, ['class' => 'form-control select2 w-100']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Client") }}</label>
                                {!! Form::select('member_id', ["" => trans("All")] + $members, $filter['member_id'] ?? NULL, ['class' => 'form-control select2 w-100']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Status") }}</label>
                                {!! Form::select('status', ["" => trans("All")] + $statuses, $filter['status'] ?? NULL, ['class' => 'form-control select2 w-100']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Month") }}</label>
                                <input type="text" name="month" class="form-control month"
                                       value=" {{ formatDate($month, 'm-Y') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Creator") }}</label>
                                {!! Form::select('creator', ["" => trans("All")] + $creators, $filter['creator'] ?? NULL, ['class' => 'form-control select2 w-100']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn btn-main-color mr-2">{{ trans("Search") }}</button>
                        <button type="button" class="btn btn-default clear">{{ trans("Cancel") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="listing">
        <div class="card">
            <div class="card-body">
                <div class="sumary d-flex justify-content-between">
                    <span class="listing-information">
                        {!! summaryListing($orders) !!}
                    </span>
                    <span class="total-price">
                         <h4>
                             {{ trans('Month') }}:
                             <span class="text-danger font-size-clearfix">
                                 {{ formatDate($month, 'm-Y') }}
                             </span>
                         </h4>
                         <h4>
                             {{ trans('Total:') }}
                             <span class="text-danger font-size-clearfix">
                                 {{ moneyFormat($orders->sum('total_price')) }}
                             </span>
                         </h4>
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>{{ trans("Order Creator") }}</th>
                            <th>{{ trans("Invoice Code") }}</th>
                            {{--<th>{{ trans("Type") }}</th>--}}
                            <th>{{ trans("Status") }}</th>
                            <th>{{ trans("Client ID") }}</th>
                            <th>{{ trans("Client Name") }}</th>
                            <th>{{ trans("Purchase/Abort At") }}</th>
                            <th>{{ trans("Payment Method") }}</th>
                            <th>{{ trans("Total Price") }}</th>
                            <th class="text-center">{{ trans("Detail") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $key = ($orders->currentpage()-1)*$orders->perpage()+1 ?>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td>{!! $order->creator->name ?? $order->name." <span class='text-danger'>(".trans('This data has been deleted').")</span>" ?? "N/A" !!}</td>
                                <td><h5>{{ (is_numeric($order->code)) ? 'CWB'.$order->code : $order->code }}</h5></td>
                                {{--<td>{{ $order_types[$order->order_type] }}</td>--}}
                                @php
                                    $bg_status = "bg-danger";
                                    if($order->status === \Modules\Order\Model\Order::STATUS_DRAFT)
                                        $bg_status = 'bg-warning';
                                    elseif($order->status === \Modules\Order\Model\Order::STATUS_PAID)
                                       $bg_status = 'bg-success';
                                @endphp
                                <td>
                                    <span class="status-box {{ $bg_status }}">
                                        {{ $statuses[$order->status] }}
                                    </span>
                                </td>
                                <td>{{ empty($order->member->id_number) ? null : "CWB".$order->member->id_number }}</td>
                                <td>
                                    @if(isset($order->member->id))
                                        <a href="{{ route('get.member.update', $order->member->id) }}"
                                           target="_blank">{{ $order->member->name  }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ formatDate(strtotime($order->updated_at), 'd-m-Y H:i') }}</td>
                                <td>{{ $order->paymentMethod->name ?? NULL }}</td>
                                <td>{{ moneyFormat($order->total_price) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('get.order.order_detail',$order->id) }}"
                                       class="btn btn-outline-primary"
                                       data-toggle="modal" data-title="{{ trans('Invoice Detail') }}"
                                       data-target="#form-modal">
                                        <i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        {{ $orders->withQueryString()->render('vendor.pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax', 'size' => 'modal-lg'])  !!}
@endsection
