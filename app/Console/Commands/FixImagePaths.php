<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixImagePaths extends Command
{
    protected $signature = 'fix:image-paths';
    protected $description = 'Заменяет пути с /images/realflame/ на /assets/products/realflame/ в полях image_url';

    public function handle()
    {
        $this->info("Начинаем обновление путей к изображениям...");

        $imageFields = ['image_url'];
        for ($i = 1; $i <= 20; $i++) {
            $imageFields[] = "image_url_{$i}";
        }

        $products = DB::table('products')
            ->select(['product_id', ...$imageFields])
            ->cursor();

        $updatedCount = 0;

        foreach ($products as $product) {
            $updateData = [];

            foreach ($imageFields as $field) {
                $oldPath = $product->$field ?? '';

                if (str_starts_with($oldPath, '/images/realflame/')) {
                    $newPath = str_replace('/images/realflame/', '/assets/products/realflame/', $oldPath);
                    $updateData[$field] = $newPath;
                }
            }

            if (!empty($updateData)) {
                DB::table('products')
                    ->where('product_id', $product->product_id)
                    ->update($updateData);

                $updatedCount++;
            }
        }

        $this->info("Обновлено записей: {$updatedCount}");
        $this->info("Завершено.");
    }
}
