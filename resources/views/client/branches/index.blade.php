@extends('layouts.client')

@section('title', 'Hệ thống chi nhánh')

@section('content')

<section class="branch-page">
  <div class="branch-shell">
    <div class="branch-list-panel">
      <div class="branch-top">
        <div>
          <span class="branch-eyebrow">Pet Hotel</span>
          <h1>Hệ thống chi nhánh</h1>
          <p>Tìm chi nhánh phù hợp theo khu vực, tên hoặc địa chỉ.</p>
        </div>
      </div>

      {{-- BranchesFilter: bộ lọc quyết định dữ liệu được render bên dưới --}}
      @include('client.branches.partials.branches-filter', [
      'districts' => $districts,
      'filters' => $filters,
      ])

      {{-- BranchesDetail: chỉ render các chi nhánh còn lại sau khi lọc --}}
      @include('client.branches.partials.branches-detail', [
      'branches' => $branches,
      ])
    </div>

    {{-- BranchesMap: marker được render từ cùng danh sách với BranchesDetail --}}
    @include('client.branches.partials.branches-map', [
    'branches' => $branches,
    ])
  </div>
</section>

@endsection