# Biotus_ProductQty Module

## Описание
Модуль **Biotus_ProductQty** для Magento 2.4.7 демонстрирует интеграцию с Varnish через ESI блоки, работу с sectionData и AJAX в теме Hyva. Модуль отображает количество продукта на складе в реальном времени, без перезагрузки страницы.

## Установка

1. Скопируйте содержимое модуля в папку `app/code/Biotus/ProductQty/`.
2. Активируйте модуль командой:
   ```bash
   php bin/magento module:enable Biotus_ProductQty
   ```
3. Выполните команду для обновления конфигурации:
   ```bash
   php bin/magento setup:upgrade
   ```
4. Очистите кэш:
   ```bash
   php bin/magento cache:clean
   ```

## Настройка Varnish для ESI

Для того чтобы ваш блок кэшировался отдельно с использованием ESI, настройте Varnish следующим образом:

1. Откройте конфигурацию Varnish и добавьте кастомный ESI тег в файл `/etc/varnish/default.vcl`:
   ```vcl
   sub vcl_recv {
       if (req.url ~ "^/biotus/ajax/getqty") {
           set req.hash_always_miss = true;
           return (pass);
       }
   }
   ```

2. Перезагрузите конфигурацию Varnish и Magento:
   ```bash
   systemctl reload varnish
   php bin/magento cache:flush
   ```

## Тестирование

1. Перейдите на страницу любого продукта в вашем магазине.
2. Откройте консоль разработчика в браузере и убедитесь, что запросы на получение количества товара выполняются успешно.
3. Проверьте корректность работы динамического обновления количества продукта на странице.

## Поддержка

Если у вас возникли вопросы или проблемы с модулем, пожалуйста, свяжитесь с разработчиком salionovych@gmail.com.
