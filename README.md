# yii2tariffs

# Запуск проекта

1. Клонируйте репозиторий:

git clone https://github.com/valeriaulyamaeva/tariffs.git
cd tariffs

2. Соберите и запустите контейнеры:

docker-compose up --build

Это поднимет контейнер с Yii2-приложением на http://localhost:8080 и MySQL-базу данных (порт 3307)

3. Выполните миграции

docker exec -it tariffs-app-1 php yii migrate

4. Заполните таблицу тарифов

docker exec -i tariffs-db-1 mysql -u root -proot --default-character-set=utf8mb4 yii2tariffs < tariffs.sql

Приложение запускается локально на порту 8080:
http://localhost:8080
