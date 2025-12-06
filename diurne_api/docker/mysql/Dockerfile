FROM mysql:8.3.0

ENV MYSQL_DATABASE=diurne_db \
    MYSQL_ROOT_PASSWORD=root

COPY ./dumps/*.sql /docker-entrypoint-initdb.d/

EXPOSE 3306
