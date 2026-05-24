@php
$searchValue = $filters['search'] ?? '';
$selectedDistrict = $filters['district'] ?? 'all';
$hasActiveFilter = $searchValue !== '' || $selectedDistrict !== 'all';
@endphp

<form class="branch-filter" method="GET" action="{{ route('branches.index') }}">
  <div class="branch-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="search" value="{{ $searchValue }}" placeholder="Tìm theo tên, địa chỉ hoặc số điện thoại">
  </div>

  <select name="district" aria-label="Lọc theo khu vực">
    <option value="all" @selected($selectedDistrict==='all' )>Tất cả quận/huyện</option>
    @foreach ($districts as $district)
    <option value="{{ $district }}" @selected($selectedDistrict===$district)>
      {{ $district }}
    </option>
    @endforeach
  </select>

  <button type="submit" class="branch-filter-btn">
    <i class="fa-solid fa-filter"></i>
    Lọc
  </button>

  @if ($hasActiveFilter)
    <a href="{{ route('branches.index') }}" class="branch-reset-btn">Xóa lọc</a>
  @endif
</form>
