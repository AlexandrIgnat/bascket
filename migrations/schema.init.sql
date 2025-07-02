create table if not exists products
(
    id int auto_increment primary key,
    uuid  varchar(255) not null comment 'UUID товара',
    category  varchar(255) not null comment 'Категория товара',
    is_active tinyint default 1  not null comment 'Флаг активности',
    name varchar(255) default '' not null comment 'Тип услуги',
    description text null comment 'Описание товара',
    thumbnail  varchar(255) null comment 'Ссылка на картинку',
    price float not null comment 'Цена'

    foreign key (category) references categories(id) on delete cascade
)
    comment 'Товары';

create index is_active_idx on products (is_active);


create table if not exists categories
(
    id int auto_increment primary key,
    title varchar(255) default '' not null comment 'Название категории'
)
    comment 'Категории';

create index title_idx on categories (is_title) comment 'Индекс для быстрого поиска по категориям';

create table if not exists customers
{
    id int auto_increment primary key,
    uuid varchar(36) not null comment,
    name varchar(255) default '' not null comment 'Имя покупателя'
}
    comment 'Покупатели'

