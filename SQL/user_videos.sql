create table if not exists user_videos
(
    id int auto_increment
        primary key,
    user_id int not null,
    name varchar(255) not null,
    thumbnail varchar(255) not null,
    length varchar(255) not null,
    size varchar(255) not null,
    path text not null,
    created datetime default CURRENT_TIMESTAMP not null
);
