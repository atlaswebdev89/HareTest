FROM php:7.2-cli
MAINTAINER atlas <web.dev89@yandex.by>

RUN apt-get update -y \
    && docker-php-ext-install pdo_mysql

ARG DB_HOST=db
ARG DB_DB=hare
ARG DB_USER=user
ARG DB_PASS=user123

ENV DB_HOST $DB_HOST
ENV DB_DB $DB_DB
ENV DB_USER $DB_USER
ENV DB_PASS $DB_PASS

WORKDIR init
COPY ./init/ /init 


ENTRYPOINT ["php"]
CMD ["initData.php"]
