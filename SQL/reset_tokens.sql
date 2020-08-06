create table if not exists reset_tokens
(
    id int not null
        primary key,
    userId int not null,
    expiration datetime not null
);
