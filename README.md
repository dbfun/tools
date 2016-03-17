# Описание

Набор вспомогательных консольных утилит.


## apache-log

Дамп из `stdin` лога апача в таблицу БД: `cat access.log | apache-log`

# checkuri

Отправление email при смене кода ответа сервера с определенного URI, например 404 > 200: `checkuri 'http://my.site.ru/index.php' 'my@mail.ru'`

## droptables

Удаление всех таблиц в базе данных: `droptables database`

## findcommit

Поиск коммита в файле по тексту и вывод информации о коммите: `findcommit www/index.php bugtext`

## getsite

Загрузка сайта с помощью `wget` в `~/tmp/sites`: `getsite http://ya.ru`

## imgoptimize

Оптимизация изображений в текущем каталоге

## optima

Рекурсивная оптимизация изображений начиная с текущего каталога

## mymount

Монтирование файловой системы с помощью `sshfs`, конфиг в `etc/projects`

## optima

Реккурсивная оптимизация изображений начиная с текущего каталога

## roket

Интерактивное копирование измененных файлов по номеру коммита во временный каталог

## testdeploy

Копирование файлов и дампа БД на тестовый сервер

## excel-importer

Импортирование Excel-файла в БД: `excel-importer excel.xls schema.json`. Примеры в `samples/excel-importer`

## levenshtein

Поиск неточного соответствий между строками в двух списках:

```
www.airliquide.com
www.hayat.com.tr
www.ikea.com
www.isuzu.com
www.indykpol.pl
```

```
image/logos/l-air-liquide.gif
image/logos/l-hayat.gif
image/logos/l-ikea.gif
image/logos/l-isuzu.gif
image/logos/l-indykpol.gif
```

Примеры в `samples/excel-importer`: `levenshtein samples/levenshtein/weblinks.txt samples/levenshtein/files.txt -file`

## iconvcp1251

Преобразование файлов из windows-1251 в UTF-8 по маске: `iconvcp1251 '*.html'`

## import-cert

Импорт SSL сертификата с сайта в ОС: `import-cert remote.host.name [port]`

## lastchange

Дата последнего модификации проекта под git

## stathive

Cборка истории из git по проектам

## whodoit

Сборка статистики из git по проектам

## tablediff

Вычисление разницы между таблицами в базе данных: `tablediff database table1 table2`

## testmail

Проверка прафильности настройки почтового сервера: `testmail domain.com`

TODO: утилита не доработана



# Дополнительные утилиты

* composer - композер
* csso - структурная минификация СSS
* yuicompressor.jar - минификация СSS от Yahoo
* facedetect - поиск лиц на фотографиях
* markdown - преобразование md разметки в html
* phpdoc - phpDocumentor


# Установка

Переименовать/скопировать каталог etc.dist в etc, внести необходимые изменения в конфигурацию