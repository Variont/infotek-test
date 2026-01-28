# Yii2 Project — Docker (PHP 8.2 + Nginx + MariaDB)

Проект запускается в Docker-контейнерах:
- PHP 8.2 (FPM)
- Nginx
- MariaDB
- Composer

После запуска приложение будет доступно в браузере:
👉 http://localhost:8080

---

## 📦 Требования

Перед началом убедитесь, что установлены:

- Docker >= 20
- Docker Compose >= 2
- Git

Проверка:

```bash
docker -v
docker compose version
git --version
```

🚀 Установка и запуск
1. Клонировать проект с GitHub
git clone https://github.com/Variont/infotek-test.git
cd REPOSITORY
2. Собрать и запустить контейнеры:
   docker compose build
   docker compose up -d

3. Проверить, что контейнеры работают: docker compose ps

4. Установить зависимости Composer: docker compose exec php composer install

5. Применить миграции: docker compose exec php php yii migrate

6. Остановка docker compose down

7. docker compose exec php bash - Войти в контейнер PHP