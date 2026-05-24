<?php

namespace App\Http\Controllers\Web\Default;

use App\Http\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;

class RoomController extends WebController
{
    public function index(): View
    {
        return view('client.rooms.dog');
    }

    public function dog(): View
    {
        return view('client.rooms.dog');
    }

    public function cat(): View
    {
        return view('client.rooms.cat');
    }

    public function show(string $roomId): View
    {
        $view = $roomId === 'cat' ? 'client.rooms.cat' : 'client.rooms.dog';

        return view($view, [
            'id' => $roomId,
            'roomId' => $roomId,
        ]);
    }

    public function typeRoom(string $typeRoomId): View
    {
        $typeRooms = [
            '1' => [
                'id' => 1,
                'name' => 'Phòng Thường',
                'label' => 'Tiêu chuẩn',
                'price' => 'Từ 180.000đ/ngày',
                'area' => '2m² - 3m²',
                'capacity' => '1 - 2 bé',
                'weight' => 'Dưới 10kg',
                'description' => 'Không gian nghỉ ngơi cơ bản, sạch sẽ, yên tĩnh và phù hợp với thú cưng nhỏ cần lưu trú ngắn ngày.',
                'features' => ['Điều hòa', 'Bát ăn riêng', 'Đệm nằm êm ái', 'Camera/Theo dõi hằng ngày', 'Vệ sinh định kỳ'],
                'care' => 'Trước khi nhận phòng, nhân viên kiểm tra sức khỏe cơ bản, ghi nhận vaccine, cân nặng và thói quen sinh hoạt.',
                'imageFolders' => [
                    'assets/client/images/type-room/normal/dog',
                    'assets/client/images/type-room/normal/cat',
                ],
            ],
            '2' => [
                'id' => 2,
                'name' => 'Phòng VIP',
                'label' => 'VIP',
                'price' => 'Từ 320.000đ/ngày',
                'area' => '4m² - 5m²',
                'capacity' => '1 - 2 bé',
                'weight' => '10kg - 25kg',
                'description' => 'Phòng rộng hơn, có thêm đồ chơi, khu vận động riêng và lịch chăm sóc linh hoạt theo thói quen của từng bé.',
                'features' => ['Không gian rộng', 'Đồ chơi riêng', 'Camera theo dõi', 'Chải lông/Tắm nhẹ', 'Ghi chú chăm sóc riêng'],
                'care' => 'Nhân viên theo dõi ăn uống, vệ sinh, vận động và tinh thần của thú cưng theo từng ca chăm sóc.',
                'imageFolders' => [
                    'assets/client/images/type-room/vip/dog',
                    'assets/client/images/type-room/vip/cat',
                ],
            ],
            '3' => [
                'id' => 3,
                'name' => 'Phòng Luxury',
                'label' => 'Luxury',
                'price' => 'Từ 450.000đ/ngày',
                'area' => '6m² - 8m²',
                'capacity' => '1 bé/phòng',
                'weight' => '25kg - 45kg',
                'description' => 'Không gian cao cấp, riêng tư, phù hợp với thú cưng cần chăm sóc cá nhân hóa và theo dõi sát hơn.',
                'features' => ['Suite riêng biệt', 'Khu chơi riêng', 'Thực đơn cao cấp', 'Chăm sóc 1-1', 'Cập nhật tình trạng hằng ngày'],
                'care' => 'Mọi thay đổi về ăn uống, vận động hoặc dấu hiệu bất thường đều được ghi nhận và báo lại cho chủ nuôi khi cần.',
                'imageFolders' => [
                    'assets/client/images/type-room/luxury/dog',
                    'assets/client/images/type-room/luxury/cat',
                ],
            ],
        ];

        abort_unless(isset($typeRooms[$typeRoomId]), 404);

        $typeRoom = $typeRooms[$typeRoomId];
        $typeRoom['images'] = $this->typeRoomImages($typeRoom['imageFolders']);

        return view('client.rooms.type-room', [
            'typeRoom' => $typeRoom,
        ]);
    }

    private function typeRoomImages(array $directories): array
    {
        $images = [];
        $publicRoot = str_replace('\\', '/', public_path());

        foreach ($directories as $directory) {
            $files = glob(public_path($directory).'/*.{jpg,jpeg,png,webp,avif}', GLOB_BRACE) ?: [];

            foreach ($files as $file) {
                $normalizedFile = str_replace('\\', '/', $file);
                $relativePath = ltrim(str_replace($publicRoot, '', $normalizedFile), '/');
                $images[] = asset($relativePath);
            }
        }

        return $images;
    }
}
