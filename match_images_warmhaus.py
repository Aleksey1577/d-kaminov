#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import os
from pathlib import Path
import pymysql
import difflib
import re

# ================== НАСТРОЙКИ ==================

# Параметры БД — ПОДСТАВЬ ИЗ .env
DB_HOST = "localhost"
DB_PORT = 3306
DB_USER = "admin"      # например "kaminov"
DB_PASSWORD = "123"       # обязательно в кавычках, даже если цифры
DB_NAME = "shop"             # имя базы
TABLE_NAME = "products"           # имя таблицы

# Производитель, которого обрабатываем
MANUFACTURER = "Warmhaus"

# Корень проекта (где лежит сам скрипт и папка public)
PROJECT_ROOT = Path(__file__).resolve().parent

# Папка public
PUBLIC_DIR = PROJECT_ROOT / "public"

# Папка с картинками производителя
IMAGES_ROOT = PUBLIC_DIR / "assets" / "products" / MANUFACTURER

# Максимум картинок на один товар (у тебя 20 полей image_url_1...image_url_20)
MAX_IMAGES_PER_PRODUCT = 20

# Минимальный процент похожести, чтобы учитывать файл (0..100)
MIN_SCORE_PERCENT = 25


# ================== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ==================


def normalize(s: str) -> str:
    """
    Нормализация строки для сравнения:
    - нижний регистр
    - замена ё -> е
    - оставляем только буквы/цифры/пробел
    - сжимаем пробелы
    """
    if not s:
        return ""

    s = s.lower()
    s = s.replace("ё", "е")

    # разрешаем латиницу, кириллицу, цифры, пробел
    s = re.sub(r"[^a-z0-9а-я\s]+", " ", s)
    s = re.sub(r"\s+", " ", s)
    return s.strip()


# Правила: какая футеровка -> какая папка
ART_RULES = [
    {
        # наименования артикула
        "art_norm_sub": normalize("Футеровка 3D-панели черные"),
        # часть имени папки
        "folder_norm_sub": normalize("фото с 3 D футеровкой"),
    },
    {
        "art_norm_sub": normalize("Футеровка шамот светлый"),
        "folder_norm_sub": normalize("фото с шамотым светлым"),
    },
]


def similarity(a: str, b: str) -> int:
    """
    Возвращает похожесть строк в процентах (0..100)
    Используем difflib.SequenceMatcher + небольшой бонус, если одна строка содержит другую.
    """
    a = a.strip()
    b = b.strip()
    if not a or not b:
        return 0

    ratio = difflib.SequenceMatcher(None, a, b).ratio() * 100

    # небольшой бонус, если одно содержится в другом
    if a in b or b in a:
        ratio += 10

    ratio = max(0, min(100, int(round(ratio))))
    return ratio


def build_search_string(row: dict) -> str:
    """
    Строка для поиска картинок по товару.
    Используем: наименование + артикула + производитель.
    """
    parts = [
        row.get("naimenovanie") or "",
        row.get("naimenovanie_artikula") or "",
        row.get("proizvoditel") or "",
    ]
    return " ".join(p for p in parts if p).strip()


def collect_image_files() -> list:
    """
    Обход всех файлов картинок в IMAGES_ROOT.
    Возвращает список словарей:
    {
        "path": "/assets/products/Warmhaus/фото/файл.jpg",  # путь для записи в БД
        "normalized": "нормализованное имя для сравнения (файл+папка)",
        "folder_norm": "нормализованное имя папки",
        "label": "читаемое имя (имя файла + подпапка)"
    }
    """
    if not IMAGES_ROOT.exists():
        raise FileNotFoundError(f"Папка с картинками не найдена: {IMAGES_ROOT}")

    image_entries = []
    allowed_ext = {".jpg", ".jpeg", ".png", ".gif", ".webp"}

    for root, dirs, files in os.walk(IMAGES_ROOT):
        for fname in files:
            ext = Path(fname).suffix.lower()
            if ext not in allowed_ext:
                continue

            full_path = Path(root) / fname

            # Путь относительно public (для URL)
            rel_public = full_path.relative_to(PUBLIC_DIR)
            url_path = "/" + rel_public.as_posix()  # например: /assets/products/Warmhaus/...

            # Название файла без расширения
            stem = Path(fname).stem

            # Путь папок относительно IMAGES_ROOT, превращаем в строку для сравнения
            rel_from_images_root = full_path.relative_to(IMAGES_ROOT)
            folder_part = rel_from_images_root.parent.as_posix()  # "фото с 3 D футеровкой" / "фото с шамотым светлым у поставщика"

            if folder_part == ".":
                folder_part = ""

            full_name_for_match = (stem + " " + folder_part).strip()

            image_entries.append(
                {
                    "path": url_path,
                    "normalized": normalize(full_name_for_match),
                    "folder_norm": normalize(folder_part),
                    "label": full_name_for_match,
                }
            )

    return image_entries


# ================== ОСНОВНАЯ ЛОГИКА ==================


def main():
    print("PROJECT_ROOT:", PROJECT_ROOT)
    print("IMAGES_ROOT:", IMAGES_ROOT)

    print("Подключаемся к БД...")

    connection = pymysql.connect(
        host=DB_HOST,
        port=DB_PORT,
        user=DB_USER,
        password=DB_PASSWORD,
        database=DB_NAME,
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor,
    )

    try:
        with connection.cursor() as cursor:
            # 1. Загружаем товары производителя
            sql = f"""
                SELECT
                    product_id,
                    naimenovanie,
                    naimenovanie_artikula,
                    proizvoditel,
                    image_url_1,
                    image_url_2,
                    image_url_3,
                    image_url_4,
                    image_url_5,
                    image_url_6,
                    image_url_7,
                    image_url_8,
                    image_url_9,
                    image_url_10,
                    image_url_11,
                    image_url_12,
                    image_url_13,
                    image_url_14,
                    image_url_15,
                    image_url_16,
                    image_url_17,
                    image_url_18,
                    image_url_19,
                    image_url_20
                FROM {TABLE_NAME}
                WHERE proizvoditel = %s
            """
            cursor.execute(sql, (MANUFACTURER,))
            products = cursor.fetchall()

        print(f"Найдено товаров производитель={MANUFACTURER}: {len(products)}")

        # 2. Собираем список картинок
        print(f"Сканируем картинки в {IMAGES_ROOT} ...")
        images = collect_image_files()
        print(f"Найдено файлов картинок: {len(images)}")

        if not images:
            print("Картинок не найдено, скрипт завершён.")
            return

        # 3. Для каждого товара подбираем подходящие картинки
        with connection.cursor() as cursor:
            for idx, row in enumerate(products, start=1):
                prod_id = row["product_id"]
                search_str = build_search_string(row)
                norm_search = normalize(search_str)
                norm_artikul = normalize(row.get("naimenovanie_artikula") or "")

                print("\n-----------------------------")
                print(f"[{idx}/{len(products)}] Товар ID={prod_id}")
                print(f"  Наименование: {row['naimenovanie']}")
                print(f"  Артикул:      {row['naimenovanie_artikula']}")
                print(f"  Производитель:{row['proizvoditel']}")
                print(f"  Строка поиска: {search_str}")

                if not norm_search:
                    print("  Пустая строка поиска (пропускаем).")
                    continue

                # 3.1. Фильтруем изображения по папке в зависимости от артикула
                filtered_images = images
                for rule in ART_RULES:
                    if rule["art_norm_sub"] and rule["art_norm_sub"] in norm_artikul:
                        # оставляем только картинки из нужной папки
                        tmp = [
                            img for img in images
                            if rule["folder_norm_sub"] in img["folder_norm"]
                        ]
                        if tmp:
                            filtered_images = tmp
                            print(
                                f"  Применено правило папки: {rule['folder_norm_sub']}, файлов: {len(filtered_images)}"
                            )
                        else:
                            print(
                                f"  Правило папки найдено, но файлов не нашли (folder_norm_sub={rule['folder_norm_sub']})"
                            )
                        break  # нашли нужное правило – другие не смотрим

                # 3.2. Считаем похожесть для отфильтрованных картинок
                scored = []
                for img in filtered_images:
                    score = similarity(norm_search, img["normalized"])
                    if score >= MIN_SCORE_PERCENT:
                        scored.append(
                            {"score": score, "path": img["path"], "label": img["label"]}
                        )

                if not scored:
                    print("  Подходящих картинок не найдено.")
                    continue

                # сортируем по убыванию похожести и оставляем MAX_IMAGES_PER_PRODUCT
                scored.sort(key=lambda x: x["score"], reverse=True)
                best_matches = scored[:MAX_IMAGES_PER_PRODUCT]

                print(f"  Найдено совпадений (>= {MIN_SCORE_PERCENT}%): {len(scored)}")
                print(
                    f"  Будет записано в image_url_1..{min(len(best_matches), MAX_IMAGES_PER_PRODUCT)}"
                )

                # просто выводим топ-3 в консоль для контроля
                for i, m in enumerate(best_matches[:3], start=1):
                    print(f"    TOP{i}: {m['score']}%  {m['label']}  -> {m['path']}")

                # формируем значения для полей image_url_1..image_url_20
                image_urls = [None] * MAX_IMAGES_PER_PRODUCT
                for i, m in enumerate(best_matches):
                    if i >= MAX_IMAGES_PER_PRODUCT:
                        break
                    image_urls[i] = m["path"]  # путь вида /assets/products/...

                # формируем UPDATE по product_id
                set_parts = []
                params = []
                for i in range(1, MAX_IMAGES_PER_PRODUCT + 1):
                    col = f"image_url_{i}"
                    set_parts.append(f"{col} = %s")
                    params.append(image_urls[i - 1])

                params.append(prod_id)

                update_sql = (
                    f"UPDATE {TABLE_NAME} SET "
                    + ", ".join(set_parts)
                    + " WHERE product_id = %s"
                )

                cursor.execute(update_sql, params)
                connection.commit()
                print("  -> обновлено в БД.")

        print("\nГотово!")

    finally:
        connection.close()


if __name__ == "__main__":
    main()
