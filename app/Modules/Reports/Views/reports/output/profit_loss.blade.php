@extends('reports.layouts.master')

@section('content')

    <h1 style="margin-bottom: 0;">@lang('ip.profit_and_loss')</h1>
    <h3 style="margin-top: 0;">{{ $results['from_date'] }} - {{ $results['to_date'] }}</h3>
    <br>
    <table class="alternate">
        <thead>
        <tr>
            <th></th>
            <th class="amount">@lang('ip.total')</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-weight: bold;">@lang('ip.income')</td>
            <td class="amount" style="font-weight: bold;">{{ $results['income'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">@lang('ip.expenses')</td>
            <td></td>
        </tr>
        @foreach ($results['expenses'] as $category => $amount)
            <tr>
                <td style="text-indent: 15px;">{{ $category }}</td>
                <td class="amount">{{ $amount }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;">@lang('ip.total_expenses')</td>
            <td class="amount" style="font-weight: bold;">{{ $results['total_expenses'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">@lang('ip.net_income')</td>
            <td class="amount" style="font-weight: bold;">{{ $results['net_income'] }}</td>
        </tr>
        </tbody>
    </table>

@stop