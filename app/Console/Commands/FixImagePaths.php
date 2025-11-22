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

        // Поля, в которых нужно заменить пути
        $imageFields = ['image_url'];
        for ($i = 1; $i <= 20; $i++) {
            $imageFields[] = "image_url_{$i}";
        }

        // Получаем все товары
        $products = DB::table('products')
            ->select(['product_id', ...$imageFields])
            ->cursor(); // Для работы с большими объемами данных

        $updatedCount = 0;

        foreach ($products as $product) {
            $updateData = [];

            foreach ($imageFields as $field) {
                $oldPath = $product->$field ?? '';

                // Если путь начинается с /images/realflame/
                if (str_starts_with($oldPath, '/images/realflame/')) {
                    $newPath = str_replace('/images/realflame/', '/assets/products/realflame/', $oldPath);
                    $updateData[$field] = $newPath;
                }
            }

            // Если есть что обновить
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