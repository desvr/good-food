## Интернет-магазин GoodFood

![GoodFood.png](GoodFood.png)

Для запуска необходимо наличие 👉 [Docker Compose](https://docs.docker.com/compose/install/) 👈

1. Склонируйте репозиторий:

   ```sh
   git clone https://github.com/desvr/goodfood.git
   ```

2. В терминале перейдите в корневой каталог проекта и запустите стартовый [shell скрипт](docker-compose/init.sh): 

   ```sh
   sh docker-compose/init.sh
   ```

    > Скрипт автоматически скопирует .env файл окружения, поднимет требуемые контейнеры, установит необходимые библиотеки, сгенерирует секретный ключ, создаст storage-ссылку, запустит миграции и заполнит БД тестовыми данными.

---

### Начало работы

Для использования некоторых сервисов необходимо заполнение [.env] файла в корневом каталоге:
* Stripe Checkout;
* ЮKassa;
* SmsAero;
* Telegram Bot token;
* TinyPng.

### Данные аутентификации

| 🛍&nbsp; [Витрина](http://127.0.0.1:8080/) | 🧑🏻‍💻&nbsp; [Панель администратора](http://127.0.0.1:8080/admin/index) |
|--------------------------------------------|----------|
| 📞 +7 (111) 111-1111                       | 📩 admin@example.com      |
| 🔑 000000                                  | 🔑 admin     |

---

### Вспомогательные команды

Поднять все контейнеры проекта:

```sh
docker-compose up --build
```

Запустить компиляцию Webpack.mix.js:

```sh
docker-compose run --rm npm run dev
```

Перезапустить миграции:

```sh
docker-compose run --rm artisan migrate:refresh
```

Запустить seeder с тестовыми данными:

```sh
docker-compose run --rm artisan db:seed
```

---

### Порты проекта

| **Контейнер** | **Порт** |
|---------------|----------|
| nginx         | 8080     |
| websocket     | 6001     |
| mysql         | 3306     |
| php           | 9000     |
| xdebug        | 9001     |
| redis         | 6379     |
