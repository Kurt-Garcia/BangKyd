@extends('layouts.navbar')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Hello, {{ auth()->user()->username ?? auth()->user()->name }}</h2>
                <p class="text-secondary small mt-2">Quick overview</p>
                <div class="row row-cols-3 g-2 mt-2 text-center">
                    <div class="col"><div class="border rounded p-3"><div class="small text-muted">Orders</div><div class="fs-5 fw-semibold">0</div></div></div>
                    <div class="col"><div class="border rounded p-3"><div class="small text-muted">In Progress</div><div class="fs-5 fw-semibold">0</div></div></div>
                    <div class="col"><div class="border rounded p-3"><div class="small text-muted">Delivered</div><div class="fs-5 fw-semibold">0</div></div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5">Recent activity</h2>
                <div class="text-secondary small mt-2">No activity yet.</div>
            </div>
        </div>
    </div>
</div>
@endsection
