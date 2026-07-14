FROM richarvey/nginx-php-fpm:latest

# Install Node so we can build Vite/Tailwind assets during the image build
RUN apk add --no-cache nodejs npm

COPY . .

# Build frontend assets (Vite + Tailwind) — equivalent to `collectstatic` in Django
RUN npm install && npm run build

ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
