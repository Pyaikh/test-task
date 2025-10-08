## Тестовое задание: служебные автомобили (Laravel + MySQL)

### Требования
- PHP 8.2+
- Composer
- MySQL (или SQLite для быстрого старта)

### Установка
```bash
composer install
cp .env.example .env
php artisan key:generate
```

### Настройка .env
- MySQL:
  - `DB_CONNECTION=mysql`
  - `DB_HOST=127.0.0.1`
  - `DB_PORT=3306`
  - `DB_DATABASE=test_task`
  - `DB_USERNAME=root`
  - `DB_PASSWORD=...`
- Swagger:
  - `L5_SWAGGER_CONST_HOST=http://localhost:8000`

### Миграции и демо-данные
```bash
php artisan migrate:fresh --seed
```
Сидер создаёт:
- пользователя: email `test@example.com`, пароль `password` (с должностью «Менеджер»),
- категории комфорта, должности и их связи,
- ~17 моделей авто (у КАЖДОЙ есть `comfort_category`),
- >20 машин с водителем,
- всё, чтобы проверить фильтры и пагинацию.

### Запуск приложения
```bash
php artisan serve --host=localhost --port=8000
```

### Swagger UI
```bash
php artisan l5-swagger:generate
```
Открой: `http://localhost:8000/api/documentation`

### Авторизация (Sanctum)
1) В Swagger выполните `POST /api/auth/login`:
```json
{ "email": "test@example.com", "password": "password" }
```
2) Нажмите Authorize и вставьте `Bearer <полученный_токен>`.

### Эндпоинт по ТЗ
- `GET /api/cars/available`
- Параметры:
  - `start_time` (ISO 8601), `end_time` (после start_time) — обязательно
  - `model_id` — опционально
  - `category_id` — опционально
  - `page`, `per_page` — пагинация (опц., по умолчанию 15, максимум 100)
- Ответ:
```json
{
  "data": [ { "id": 1, "model": { "id": 2, "name": "...", "comfort_category": {"id": 2, "name": "Вторая"} }, "driver": {"id": 1, "name": "..."}, "license_plate": "..." } ],
  "meta": { "current_page": 1, "per_page": 15, "total": 22, "last_page": 2 }
}
```
Замечание: категория комфорта закреплена за МОДЕЛЬЮ авто (строго по ТЗ).

### Быстрые сценарии проверки ТЗ
1) Доступность по времени:
   - Вызов без фильтров — список доступных машин в интервале.
2) Фильтр по модели:
   - Добавьте `model_id=<id из car_models>` — останутся машины этой модели.
3) Фильтр по категории:
   - Добавьте `category_id=<id из comfort_categories>` — машины, чьи МОДЕЛИ имеют эту категорию.
4) Пагинация:
   - `per_page=5&page=1`, затем `page=2` — разные страницы, корректные метаданные.
5) Доступность vs занятость (локально):
   - Временный эндпоинт (только в `local`): `POST /api/trips`
   - Тело:
```json
{ "car_id": 1, "start_time": "2025-10-08T10:30:00Z", "end_time": "2025-10-08T11:30:00Z" }
```
   - После создания поездки вызов `GET /api/cars/available` на пересекающемся интервале исключит эту машину из списка.

### Примечания по реализации
- Категория комфорта хранится в `car_models.comfort_category_id`; фильтрация идёт через модель (`whereHas('model', ...)`).
- Роль пользователя через `users.position_id`; допустимые категории берутся через пивот `position_comfort_category`.
- Скоуп доступности: исключает машины, у которых есть `trips`, пересекающие интервал.

### Полезные команды
- Перегенерировать Swagger: `php artisan l5-swagger:generate`
- Полная пересборка БД: `php artisan migrate:fresh --seed`

### Система
- Laravel + Sanctum
- L5 Swagger (OpenAPI 3)
