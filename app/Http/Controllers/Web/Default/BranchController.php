<?php

namespace App\Http\Controllers\Web\Default;

use App\Http\Controllers\Web\WebController;
use App\Models\Branch;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class BranchController extends WebController
{
    public function index(Request $request): View
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'district' => trim((string) $request->query('district', 'all')) ?: 'all',
        ];

        $allBranches = collect($this->branches())->values();
        $filteredBranches = $this->filterBranches($allBranches, $filters)->values();
        $districts = $allBranches
            ->pluck('district')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('client.branches.index', [
            'branches' => $filteredBranches,
            'districts' => $districts,
            'filters' => $filters,
            'totalBranches' => $allBranches->count(),
        ]);
    }

    public function show(string $branchId): View
    {
        $branch = collect($this->branches())->firstWhere('id', (int) $branchId);

        return view('client.branches.show', [
            'id' => $branchId,
            'branchId' => $branchId,
            'branch' => $branch,
            'bannerImage' => $this->randomBranchImage(),
        ]);
    }

    private function filterBranches(Collection $branches, array $filters): Collection
    {
        $search = $this->searchable($filters['search'] ?? '');
        $district = trim((string) ($filters['district'] ?? 'all')) ?: 'all';
        $normalizedDistrict = $this->searchable($district);

        return $branches->filter(function (array $branch) use ($search, $district, $normalizedDistrict): bool {
            $matchesDistrict = $district === 'all'
                || $this->searchable($branch['district'] ?? '') === $normalizedDistrict;
            $matchesSearch = $search === '' || Str::contains(
                $this->searchable(implode(' ', [
                    $branch['name'] ?? '',
                    $branch['district'] ?? '',
                    $branch['address'] ?? '',
                    $branch['phone'] ?? '',
                    $branch['email'] ?? '',
                ])),
                $search
            );

            return $matchesDistrict && $matchesSearch;
        });
    }

    private function branches(): array
    {
        try {
            $branches = Branch::query()
                ->where('is_active', true)
                ->orderBy('branch_name')
                ->get();

            if ($branches->isNotEmpty()) {
                $branchImages = $this->randomBranchImages($branches->count());

                return $branches
                    ->values()
                    ->map(fn (Branch $branch, int $index): array => $this->formatBranch(
                        $branch,
                        $index,
                        $branchImages[$index] ?? $this->randomBranchImage()
                    ))
                    ->all();
            }
        } catch (Throwable) {
            return $this->fallbackBranches();
        }

        return $this->fallbackBranches();
    }

    private function formatBranch(Branch $branch, int $index, string $imageUrl): array
    {
        $district = $this->districtFrom($branch->branch_name.' '.$branch->address);
        $meta = $this->branchMeta($district, $index);

        return [
            'id' => (int) $branch->branch_id,
            'name' => $branch->branch_name,
            'address' => $branch->address,
            'phone' => $branch->phone ?: 'Đang cập nhật',
            'email' => $branch->email,
            'district' => $district,
            'hours' => $meta['hours'],
            'rating' => $meta['rating'],
            'reviews' => $meta['reviews'],
            'image' => $imageUrl,
            'map' => $meta['map'],
            'detailUrl' => route('branches.show', $branch->branch_id),
            'bookingUrl' => url('/booking/branch/'.$branch->branch_id),
        ];
    }

    private function fallbackBranches(): array
    {
        $branchImages = $this->randomBranchImages(4);

        return collect([
            [
                'id' => 1,
                'name' => 'Pet Hotel Quận 7',
                'address' => '123 Đường Nguyễn Văn Linh, Quận 7, TP.HCM',
                'phone' => '1900 1234',
                'district' => 'Quận 7',
                'hours' => '8:00 - 20:00',
                'rating' => '4.8',
                'reviews' => 127,
                'map' => ['x' => 72, 'y' => 68],
            ],
            [
                'id' => 2,
                'name' => 'Pet Hotel Quận 1',
                'address' => '45 Đường Lê Lợi, Quận 1, TP.HCM',
                'phone' => '1900 5678',
                'district' => 'Quận 1',
                'hours' => '7:30 - 21:00',
                'rating' => '4.6',
                'reviews' => 89,
                'map' => ['x' => 34, 'y' => 34],
            ],
            [
                'id' => 3,
                'name' => 'Pet Hotel Bình Thạnh',
                'address' => '321 Đường Xô Viết Nghệ Tĩnh, Bình Thạnh, TP.HCM',
                'phone' => '1900 3456',
                'district' => 'Bình Thạnh',
                'hours' => '8:00 - 20:00',
                'rating' => '4.7',
                'reviews' => 203,
                'map' => ['x' => 49, 'y' => 48],
            ],
            [
                'id' => 4,
                'name' => 'Pet Hotel Thủ Đức',
                'address' => '789 Đường Võ Văn Ngân, TP. Thủ Đức, TP.HCM',
                'phone' => '1900 9012',
                'district' => 'Thủ Đức',
                'hours' => '8:00 - 19:00',
                'rating' => '4.5',
                'reviews' => 61,
                'map' => ['x' => 72, 'y' => 28],
            ],
        ])->map(function (array $branch, int $index) use ($branchImages): array {
            return $branch + [
                'email' => null,
                'image' => $branchImages[$index] ?? $this->randomBranchImage(),
                'detailUrl' => route('branches.show', $branch['id']),
                'bookingUrl' => url('/booking/branch/'.$branch['id']),
            ];
        })->all();
    }

    private function branchMeta(string $district, int $index): array
    {
        $districtMeta = [
            'Quận 7' => [
                'hours' => '8:00 - 20:00',
                'rating' => '4.8',
                'reviews' => 127,
                'map' => ['x' => 72, 'y' => 68],
            ],
            'Quận 1' => [
                'hours' => '7:30 - 21:00',
                'rating' => '4.6',
                'reviews' => 89,
                'map' => ['x' => 34, 'y' => 34],
            ],
            'Bình Thạnh' => [
                'hours' => '8:00 - 20:00',
                'rating' => '4.7',
                'reviews' => 203,
                'map' => ['x' => 49, 'y' => 48],
            ],
            'Thủ Đức' => [
                'hours' => '8:00 - 19:00',
                'rating' => '4.5',
                'reviews' => 61,
                'map' => ['x' => 72, 'y' => 28],
            ],
            'Gò Vấp' => [
                'hours' => '8:00 - 20:00',
                'rating' => '4.5',
                'reviews' => 73,
                'map' => ['x' => 42, 'y' => 42],
            ],
        ];

        return $districtMeta[$district] ?? [
            'hours' => '8:00 - 20:00',
            'rating' => '4.5',
            'reviews' => 0,
            'map' => [
                'x' => 28 + (($index * 17) % 48),
                'y' => 30 + (($index * 19) % 42),
            ],
        ];
    }

    private function randomBranchImage(): string
    {
        $images = $this->branchImageUrls();

        if ($images === []) {
            return asset('assets/client/images/right-home-500x554.png');
        }

        return $images[array_rand($images)];
    }

    private function randomBranchImages(int $count): array
    {
        if ($count <= 0) {
            return [];
        }

        $images = $this->branchImageUrls();

        if ($images === []) {
            return array_fill(0, $count, asset('assets/client/images/right-home-500x554.png'));
        }

        shuffle($images);

        return collect(range(0, max(0, $count - 1)))
            ->map(fn (int $index): string => $images[$index % count($images)])
            ->all();
    }

    private function branchImageUrls(): array
    {
        static $urls = null;

        if ($urls !== null) {
            return $urls;
        }

        $directory = public_path('assets/client/images/branches');

        if (! is_dir($directory)) {
            return $urls = [];
        }

        $files = [];

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
            $files = [
                ...$files,
                ...(glob($directory.DIRECTORY_SEPARATOR.'*.'.$extension) ?: []),
                ...(glob($directory.DIRECTORY_SEPARATOR.'*.'.strtoupper($extension)) ?: []),
            ];
        }

        $files = array_values(array_unique($files));
        usort($files, 'strnatcasecmp');

        return $urls = collect($files)
            ->take(10)
            ->map(fn (string $path): string => asset('assets/client/images/branches/'.rawurlencode(basename($path))))
            ->values()
            ->all();
    }

    private function districtFrom(string $value): string
    {
        $searchable = $this->searchable($value);

        if (preg_match('/\bquan\s+(\d{1,2})\b/', $searchable, $matches)) {
            return 'Quận '.$matches[1];
        }

        foreach ($this->districtAliases() as $district => $aliases) {
            if (Str::contains($searchable, $aliases)) {
                return $district;
            }
        }

        return 'Khu vực khác';
    }

    private function districtAliases(): array
    {
        return [
            'Thủ Đức' => ['thu duc', 'tp thu duc', 'thanh pho thu duc'],
            'Gò Vấp' => ['go vap'],
            'Bình Thạnh' => ['binh thanh'],
            'Phú Nhuận' => ['phu nhuan'],
            'Tân Bình' => ['tan binh'],
            'Tân Phú' => ['tan phu'],
            'Bình Tân' => ['binh tan'],
            'Bình Chánh' => ['binh chanh'],
            'Nhà Bè' => ['nha be'],
            'Củ Chi' => ['cu chi'],
            'Hóc Môn' => ['hoc mon'],
            'Cần Giờ' => ['can gio'],
        ];
    }

    private function searchable(?string $value): string
    {
        return Str::lower(Str::ascii($value ?? ''));
    }
}
