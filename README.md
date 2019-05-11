# ActiveTable
ActiveTable Engine, grid+form

## Простая таблица с формой

```php
//создаем объект таблицы, передаем репозитрий реализующий интерфейс Repo/CrudRepository из пакета dimaxz/repository
$table = new DataTableSimple(new UserRepository,"users");
$table->setSearchCriteria( new UserCriteria() );

//колонки
$table->addColumn( new ColumnTable("id","№"));
$table->addColumn( new ColumnTable("name","Наименование"));
$table->addColumn( new ColumnTable("email","E-mail"));

//поля
$table->addField( new FormInput("id"));
$table->addField( new FormInput("name"));
$table->addField( new FormInput("email"));

//вывод в html
dump($table->render());

```

Пример проекта https://github.com/dimaxz/active-table-project
