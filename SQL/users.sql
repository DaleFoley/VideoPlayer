create table if not exists users
(
    id int unsigned auto_increment
        primary key,
    name varchar(255) not null,
    email varchar(255) not null,
    password text null,
    isAccountActive tinyint(1) default 0 not null,
    isEmailVerified tinyint(1) default 0 not null,
    isTwoFactorAuthEnabled tinyint(1) default 0 not null,
    constraint email_UNIQUE
        unique (email),
    constraint name_UNIQUE
        unique (name)
);
